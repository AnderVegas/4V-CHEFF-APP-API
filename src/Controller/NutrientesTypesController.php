<?php

namespace App\Controller;

use App\Entity\NutrientesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NutrientesTypeRepository;

class NutrientesTypesController extends AbstractController
{
    #[Route('/nutrientes/types', name: 'app_nutrientes_types', methods: ['GET'])]
    public function getNutrientes(NutrientesTypeRepository $nutrientesTypeRepository): JsonResponse
    {
       
        
        $nutrients = $nutrientesTypeRepository->findAll();

        $nutrientesTypeArr = [];

        foreach ($nutrients as $nutrient) {
            $NutrientsData  [] = [
                'id' => $nutrient->getId(),
                'name' => $nutrient->getName(),
                'unit' => $nutrient->getUnit(),
            ];

            $nutrientesTypeArr[] = $NutrientsData;
        }

        return $this->json($nutrientesTypeArr,200);
        
    }
}
