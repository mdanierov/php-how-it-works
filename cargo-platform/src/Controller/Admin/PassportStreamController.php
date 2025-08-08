<?php

namespace App\Controller\Admin;

use App\Entity\CourierProfile;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PassportStreamController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/passport/{id}', name: 'api_admin_passport_stream', methods: ['GET'])]
    public function stream(int $id, EntityManagerInterface $em): BinaryFileResponse
    {
        $profile = $em->getRepository(CourierProfile::class)->find($id);
        if (!$profile || !$profile->getPassportPath()) {
            throw $this->createNotFoundException('Passport not found');
        }

        $projectDir = $this->getParameter('kernel.project_dir');
        $path = $projectDir . '/var/private_uploads/passports/' . ltrim($profile->getPassportPath(), '/');
        $fs = new Filesystem();
        if (!$fs->exists($path)) {
            throw $this->createNotFoundException('File not found');
        }

        $response = new BinaryFileResponse($path);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_INLINE, basename($path));
        $response->headers->set('Content-Type', 'image/jpeg');
        return $response;
    }
}