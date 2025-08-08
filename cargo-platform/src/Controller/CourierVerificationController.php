<?php

namespace App\Controller;

use App\Entity\CourierProfile;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Handler\UploadHandler;

class CourierVerificationController extends AbstractController
{
    #[IsGranted('ROLE_COURIER')]
    #[Route('/api/courier/passport', name: 'api_courier_upload_passport', methods: ['POST'])]
    public function upload(Request $request, EntityManagerInterface $em, UploadHandler $uploader): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getCourierProfile() ?? (new CourierProfile())->setUser($user);

        /** @var UploadedFile|null $file */
        $file = $request->files->get('passport');
        if (!$file) {
            return $this->json(['error' => 'File not provided under key "passport"'], 400);
        }

        $profile->setPassportFile($file);
        $em->persist($profile);
        $em->flush();

        // Vich handles upload on flush
        return $this->json(['status' => 'uploaded']);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/admin/courier/{id}/verify', name: 'api_admin_verify_courier', methods: ['POST'])]
    public function verify(int $id, EntityManagerInterface $em): JsonResponse
    {
        $profile = $em->getRepository(CourierProfile::class)->find($id);
        if (!$profile) {
            return $this->json(['error' => 'Profile not found'], 404);
        }
        $user = $profile->getUser();
        $roles = $user->getRoles();
        if (!in_array('ROLE_VERIFIED_COURIER', $roles, true)) {
            $roles[] = 'ROLE_VERIFIED_COURIER';
            $user->setRoles($roles);
        }
        $user->setVerifiedAt(new \DateTimeImmutable());

        // remove stored passport file
        $profile->setPassportPath(null);
        $profile->setPassportDeletedAt(new \DateTimeImmutable());

        $em->flush();

        return $this->json(['status' => 'verified']);
    }
}