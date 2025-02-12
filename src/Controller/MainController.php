<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\RoadTrip;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
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

        return $this->render('main/index.html.twig', [
            'controller_name' => 'Accueil',
            'roadtrips' => $roadtrips,
        ]);
    }
}
