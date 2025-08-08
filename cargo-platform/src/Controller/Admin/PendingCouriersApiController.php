<?php

namespace App\Controller\Admin;

use App\Entity\CourierProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PendingCouriersApiController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/couriers/pending', name: 'api_admin_couriers_pending', methods: ['GET'])]
    public function list(EntityManagerInterface $em): JsonResponse
    {
        $profiles = $em->getRepository(CourierProfile::class)->createQueryBuilder('c')
            ->andWhere('c.passportPath IS NOT NULL')
            ->getQuery()->getResult();

        $items = array_map(function (CourierProfile $c) {
            return [
                'id' => $c->getId(),
                'userId' => $c->getUser()->getId(),
                'passportPath' => $c->getPassportPath(),
            ];
        }, $profiles);

        return $this->json(['items' => $items]);
    }
}