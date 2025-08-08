<?php

namespace App\Controller;

use App\Entity\Order;
use App\Service\MatchingService;
use App\Service\PricingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class OrderController extends AbstractController
{
    #[IsGranted('ROLE_CLIENT')]
    #[Route('/api/orders', name: 'api_orders_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, PricingService $pricingService, MatchingService $matchingService): JsonResponse
    {
        $data = json_decode($request->getContent(), true) ?? [];
        $weight = (int) ($data['weightKg'] ?? 0);
        $from = $data['fromAddress'] ?? '';
        $to = $data['toAddress'] ?? '';
        $direction = $data['direction'] ?? '';

        if ($weight <= 0 || !$from || !$to || !$direction) {
            return $this->json(['error' => 'Invalid input'], 400);
        }

        $priceArr = $pricingService->calculatePrice($weight, $direction);

        $order = new Order();
        $order->setClient($this->getUser());
        $order->setWeightKg($weight);
        $order->setFromAddress($from);
        $order->setToAddress($to);
        $order->setDirection($direction);
        $order->setPrice($priceArr['amount']);
        $order->setCurrency($priceArr['currency']);
        $order->setStatus('awaiting_payment');

        $em->persist($order);
        $em->flush();

        // attempt auto-assign
        $profile = $matchingService->assignBestCourier($order);
        if ($profile) {
            $order->setAssignedCourier($profile->getUser());
            $order->setStatus('assigned');
            $em->flush();
        }

        return $this->json([
            'id' => $order->getId(),
            'price' => $order->getPrice(),
            'currency' => $order->getCurrency(),
            'status' => $order->getStatus(),
        ]);
    }
}