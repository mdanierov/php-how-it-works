<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PayPalController extends AbstractController
{
    private function getClient(): Client
    {
        $base = 'sandbox' === ($_ENV['PAYPAL_MODE'] ?? 'sandbox') ? 'https://api-m.sandbox.paypal.com' : 'https://api-m.paypal.com';
        return new Client(['base_uri' => $base]);
    }

    private function getAccessToken(Client $client): string
    {
        $clientId = $_ENV['PAYPAL_CLIENT_ID'] ?? '';
        $secret = $_ENV['PAYPAL_CLIENT_SECRET'] ?? '';
        $resp = $client->post('/v1/oauth2/token', [
            'auth' => [$clientId, $secret],
            'form_params' => ['grant_type' => 'client_credentials']
        ]);
        $data = json_decode((string) $resp->getBody(), true);
        return $data['access_token'];
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/api/paypal/capture', name: 'api_paypal_capture', methods: ['POST'])]
    public function capture(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $paypalOrderId = $data['paypalOrderId'] ?? null;
        $orderId = $data['orderId'] ?? null;
        if (!$paypalOrderId || !$orderId) {
            return $this->json(['error' => 'paypalOrderId and orderId required'], 400);
        }
        $order = $em->getRepository(Order::class)->find($orderId);
        if (!$order) { return $this->json(['error' => 'Order not found'], 404); }

        $client = $this->getClient();
        $access = $this->getAccessToken($client);
        $resp = $client->post("/v2/checkout/orders/{$paypalOrderId}/capture", [
            'headers' => ['Authorization' => "Bearer {$access}", 'Content-Type' => 'application/json']
        ]);
        $payload = json_decode((string) $resp->getBody(), true);

        $status = $payload['status'] ?? 'COMPLETED';
        $amount = $payload['purchase_units'][0]['payments']['captures'][0]['amount']['value'] ?? $order->getPrice();
        $currency = $payload['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'] ?? $order->getCurrency();

        $payment = new Payment();
        $payment->setOrder($order)
            ->setPaypalId($paypalOrderId)
            ->setStatus($status)
            ->setAmount($amount)
            ->setCurrency($currency);
        $em->persist($payment);

        $order->setStatus('paid');
        $em->flush();

        return $this->json(['status' => 'captured', 'paymentId' => $payment->getId()]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/paypal/refund', name: 'api_paypal_refund', methods: ['POST'])]
    public function refund(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $paypalCaptureId = $data['paypalCaptureId'] ?? null;
        if (!$paypalCaptureId) { return $this->json(['error' => 'paypalCaptureId required'], 400); }

        $client = $this->getClient();
        $access = $this->getAccessToken($client);
        $resp = $client->post("/v2/payments/captures/{$paypalCaptureId}/refund", [
            'headers' => ['Authorization' => "Bearer {$access}", 'Content-Type' => 'application/json'],
            'json' => new \stdClass()
        ]);
        $payload = json_decode((string) $resp->getBody(), true);

        return $this->json(['status' => 'refunded', 'payload' => $payload]);
    }
}