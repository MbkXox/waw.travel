<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\RoadTrip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/road-trip')]
class RoadTripController extends AbstractController
{
    #[Route(name: 'app_road_trip_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {

        $roadtrips = $em->getRepository(RoadTrip::class)->findBy(['status' => 0], ['created_at' => 'DESC']);

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

                $coverRoadTrip = $em->getRepository(Media::class)->findOneBy(['road_trip' => $roadtrip->getId(), 'is_cover' => true]);

                $roadtrip->startCheckPoint = $startCheckPoint;
                $roadtrip->endCheckPoint = $endCheckPoint;
                $roadtrip->coverRoadTrip = $coverRoadTrip;

                // Calcul du nombre de jours
                $interval = $startCheckPoint->getDateStart()->diff($endCheckPoint->getDateEnd());
                $roadtrip->numberOfDays = $interval->days + 1; // +1 pour inclure le jour de début
                
            }
        }

        return $this->render('road_trip/index.html.twig', [
            'roadtrips' => $roadtrips,
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
}
