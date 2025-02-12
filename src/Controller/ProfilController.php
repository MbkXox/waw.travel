<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Service\FileUploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController{
    #[Route('/profil', name: 'app_profil')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();

        return $this->render('profil/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit')]
    public function edit(Security $security, Request $request, EntityManagerInterface $em, FileUploadService $fileUploadService): Response
    {
        $user = $security->getUser();
        
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $oldName = $user->getPhoto();
                $newFileName = $fileUploadService->uploadFile($photo, 'photo_user', $oldName);
                $user->setPhoto($newFileName);
            }


            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

}
