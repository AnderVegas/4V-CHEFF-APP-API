<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class NutrientesTypesController extends AbstractController
{
    #[Route('/nutrientes/types', name: 'app_nutrientes_types')]
    public function index(): JsonResponse
    {
       
        
        // return $this->json([
        //     'message' => 'Welcome to your new controller!',
        //     'path' => 'src/Controller/NutrientesTypesController.php',
        // ]);

        $entityManager = $this->getDoctrine()->getManager();
        $nutrients = $entityManager->getRepository(Nutrient::class)->findAll();

        $formattedNutrients = [];
        foreach ($nutrients as $nutrient) {
            $formattedNutrients[] = [
                'id' => $nutrient->getId(),
                'name' => $nutrient->getName(),
                'unit' => $nutrient->getUnit(),
            ];
        }

        return $this->json($formattedNutrients);
        
    }
}
