<?php

namespace App\Controller;

use App\Entity\CheckPoint;
use App\Entity\Media;
use App\Entity\Photo;
use App\Entity\RoadTrip;
use App\Form\RoadTripType;
use App\Repository\RoadTripRepository;
use App\Service\FileUploadService;
use App\Service\FindCountryByCoordinates;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/road-trip')]
final class RoadTripController extends AbstractController{
    #[Route(name: 'app_road_trip_index', methods: ['GET'])]
    public function index(RoadTripRepository $roadTripRepository): Response
    {

        $roadtrips = $roadTripRepository->findAll();

        foreach ($roadtrips as $roadtrip) {
            $checkPoints = $roadtrip->getCheckPoints();
            
            if (count($checkPoints) > 0) {
                $startCheckPoint = $checkPoints[0];
                $endCheckPoint = $checkPoints[0];
                
                foreach ($checkPoints as $checkPoint) {
                    if ($checkPoint->getDateStart() < $startCheckPoint->getDateStart()) {
                        $startCheckPoint = $checkPoint;
                    }
                    if ($checkPoint->getDateEnd() > $endCheckPoint->getDateEnd()) {
                        $endCheckPoint = $checkPoint;
                    }
                }
                
                $roadtrip->startCheckPoint = $startCheckPoint;
                $roadtrip->endCheckPoint = $endCheckPoint;
            }
        }

        return $this->render('road_trip/index.html.twig', [
            'road_trips' => $roadtrips,
        ]);
    }

    #[Route('/new', name: 'app_road_trip_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploadService $fileUploadService, FindCountryByCoordinates $findCountryByCoordinates): Response
    {
        $roadTrip = new RoadTrip();
        $form = $this->createForm(RoadTripType::class, $roadTrip);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($roadTrip->getCheckPoints() as $checkPoint) {

                $startCity = $findCountryByCoordinates->getCountryFromCoordinates(
                    $checkPoint->getLatitude(),
                    $checkPoint->getLongitude()
                );

                $checkPoint->setCountry($startCity);
                $checkPoint->setRoadTrip($roadTrip);

                $entityManager->persist($checkPoint);
            }

            $mediaRoadTrip = $form->get('medias');
            $coverRoadTrip = $form->get('coverImage')->getData();

            if ($coverRoadTrip) {
                $orgiginalFileName = $coverRoadTrip->getClientOriginalName();
                $newFileName = $fileUploadService->uploadFile($coverRoadTrip, 'cover_road_trip');

                $photoCover = new Media();
                $photoCover->setName($orgiginalFileName);
                $photoCover->setPath($newFileName);
                $photoCover->setRoadTrip($roadTrip);
                $photoCover->setIsCover(true);

                $entityManager->persist($photoCover);
            }
            
            foreach ($mediaRoadTrip as $mediaForm) {
                $files = $mediaForm->get('path')->getData();
                
                if ($files) {
                    foreach ($files as $file) {
                        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                        $file->move($this->getParameter('photo_road_trip'), $fileName);
            
                        $mediaRoadTrip = new Media();
                        $mediaRoadTrip->setName($file->getClientOriginalName());
                        $mediaRoadTrip->setPath($fileName);
                        $mediaRoadTrip->setRoadTrip($roadTrip);
            
                        $entityManager->persist($mediaRoadTrip);
                    }
                }
            }
        
            $entityManager->persist($roadTrip);
            $entityManager->flush();

            return $this->redirectToRoute('app_road_trip_index', [], Response::HTTP_SEE_OTHER);        
        }
                        
        return $this->render('road_trip/new.html.twig', [
            'road_trip' => $roadTrip,
            'form' => $form,
            'is_edit' => false,
        ]);
    }

    #[Route('/{id}', name: 'app_road_trip_show', methods: ['GET'])]
    public function show(RoadTrip $roadTrip): Response
    {

        $medias = $roadTrip->getMedia();
        $checkPoints = $roadTrip->getCheckPoints()->toArray();

        usort($checkPoints, function($a, $b) {
            return $a->getDateStart() <=> $b->getDateStart();
        });

        return $this->render('road_trip/show.html.twig', [
            'road_trip' => $roadTrip,
            'medias' => $medias,
            'checkpoints' => $checkPoints,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_road_trip_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RoadTrip $roadTrip, EntityManagerInterface $entityManager, FileUploadService $fileUploadService, FindCountryByCoordinates $findCountryByCoordinates): Response
    {
        $form = $this->createForm(RoadTripType::class, $roadTrip);
        $form->handleRequest($request);
        $medias = $roadTrip->getMedia();

        $checkPoints = $roadTrip->getCheckPoints();

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($roadTrip->getCheckPoints() as $checkPoint) {

                $startCity = $findCountryByCoordinates->getCountryFromCoordinates(
                    $checkPoint->getLatitude(),
                    $checkPoint->getLongitude()
                );

                $checkPoint->setCountry($startCity);
                $checkPoint->setRoadTrip($roadTrip);

                $entityManager->persist($checkPoint);
            }

            $mediasRoadTrip = $form->get('medias');
            $coverRoadTrip = $form->get('coverImage')->getData();

            if ($coverRoadTrip) {
                $oldName = $entityManager->getRepository(Media::class)->findOneBy(['road_trip' => $roadTrip->getId(), 'is_cover' => true]);
                $orgiginalFileName = $coverRoadTrip->getClientOriginalName();
                $newFileName = $fileUploadService->uploadFile($coverRoadTrip, 'cover_road_trip', $oldName);

                $photoCover = new Media();
                $photoCover->setName($orgiginalFileName);
                $photoCover->setPath($newFileName);
                $photoCover->setRoadTrip($roadTrip);
                $photoCover->setIsCover(true);

                $entityManager->persist($photoCover);
            }
            
            foreach ($mediasRoadTrip as $mediaForm) {
                $files = $mediaForm->get('path')->getData();
                
                if ($files) {
                    foreach ($files as $file) {
                        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                        $file->move($this->getParameter('photo_road_trip'), $fileName);
            
                        $mediaRoadTrip = new Media();
                        $mediaRoadTrip->setName($file->getClientOriginalName());
                        $mediaRoadTrip->setPath($fileName);
                        $mediaRoadTrip->setRoadTrip($roadTrip);
            
                        $entityManager->persist($mediaRoadTrip);
                    }
                }
            }
        
            $entityManager->persist($roadTrip);
            $entityManager->flush();

            return $this->redirectToRoute('app_road_trip_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('road_trip/edit.html.twig', [
            'road_trip' => $roadTrip,
            'form' => $form,
            'medias' => $medias,
            'checkpoints' => $checkPoints,
            'is_edit' => true,
        ]);
    }

    #[Route('/{id}', name: 'app_road_trip_delete', methods: ['POST'])]
    public function delete(Request $request, RoadTrip $roadTrip, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        if ($this->isCsrfTokenValid('delete'.$roadTrip->getId(), $request->getPayload()->getString('_token'))) {
            
            
                foreach ($roadTrip->getMedia() as $media ) {
                    if ($media->isCover()) {
                        $fileUploadService->deleteFile($media->getPath(), 'cover_road_trip');

                    } else {
                        $fileUploadService->deleteFile($media->getPath(), 'photo_road_trip');
                    }
                    $entityManager->remove($media);
                }

                foreach ($roadTrip->getCheckPoints() as $checkPoint) {
                    $entityManager->remove($checkPoint);
                }

                $entityManager->remove($roadTrip);
                $entityManager->flush();
            }
        

        return $this->redirectToRoute('app_road_trip_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/photo/{id}', name: 'app_photo_road_trip_delete', methods: ['POST'])]
    public function deleteMedia(Request $request, Media $media, EntityManagerInterface $entityManager, FileUploadService $fileUploadService): Response
    {
        $csrfToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$media->getId(), $csrfToken)) {
            $fileUploadService->deleteFile($media->getPath(), 'photo_road_trip');
            
            $entityManager->remove($media);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_road_trip_edit', ['id' => $media->getRoadTrip()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/checkpoint/{id}', name: 'app_checkpoint_road_trip_delete', methods: ['POST'])]
    public function deleteCheckPoint(Request $request, CheckPoint $checkpoint, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$checkpoint->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($checkpoint);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_road_trip_edit', ['id' => $checkpoint->getRoadTrip()->getId()], Response::HTTP_SEE_OTHER);
    }
}
