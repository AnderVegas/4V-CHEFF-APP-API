<?php

namespace App\Controller;

use App\Entity\Recipe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecipesController extends AbstractController
{
    #[Route('/recipes', name: 'new_recipe', methods: ['POST'])]
    public function newRecipe(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validar que hay al menos 1 Ingrediente
        if (empty($data['ingredients'])) {
            return $this->json(['error' => 'Debe especificar al menos un ingrediente'], 400);
        }

        // Validar que hay al menos 1 Paso
        if (empty($data['steps'])) {
            return $this->json(['error' => 'Debe especificar al menos un paso'], 400);
        }

        foreach ($data['nutrients'] as $nutrientData) {
            // Verificar si el tipo de nutriente existe en la BBDD
            $nutrientType = $entityManager->getRepository(NutrientType::class)->find($nutrientData['id']);
            if (!$nutrientType) {
                return $this->json(['error' => 'El tipo de nutriente especificado no existe'], 400);
            }
        }


        // Crear una nueva instancia de Recipe y asignar los datos recibidos
        $recipe = new Recipe();
        $recipe->setTitle($data['title']);
        $recipe->setNumberDiner($data['number-diner']);

        // Agregar ingredientes
        foreach ($data['ingredients'] as $ingredientData) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredientData['name']);
            $ingredient->setQuantity($ingredientData['quantity']);
            $ingredient->setUnit($ingredientData['unit']);
            $recipe->addIngredient($ingredient);
        }

        // Agregar pasos
        foreach ($data['steps'] as $stepData) {
            $step = new Step();
            $step->setOrder($stepData['order']);
            $step->setDescription($stepData['description']);
            $recipe->addStep($step);
        }

        // Agregar nutrientes
        $entityManager = $this->getDoctrine()->getManager();
        $nutrientTypeRepository = $entityManager->getRepository(NutrientType::class);
        foreach ($data['nutrients'] as $nutrientData) {
            $nutrientTypeId = $nutrientData['id'];
            $quantity = $nutrientData['quantity'];
            $nutrientType = $nutrientTypeRepository->find($nutrientTypeId);
            if ($nutrientType) {
                $recipe->addNutrientType($nutrientType);
            }
        }

        // Guardar la nueva receta en la base de datos
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Devolver la nueva receta como respuesta
        return $this->json($recipe, 201);
    }



    #[Route('/recipes', name: 'get_recipe', methods: ['GET'])]
    public function getRecipe(Request $request): JsonResponse
    {
        $entityManager = $this->getDoctrine()->getManager();
        $recipeRepository = $entityManager->getRepository(Recipe::class);

        // Obtener los parámetros opcionales minCalories y maxCalories
        $minCalorias = $request->query->get('minCalorias');
        $maxCalorias = $request->query->get('maxCalorias');

        // Filtrar las recetas por las calorías por persona si los parámetros están presentes
        $recipes = $recipeRepository->createQueryBuilder('r')
            ->andWhere('r.caloriesPerPerson >= :minCalories')
            ->andWhere('r.caloriesPerPerson <= :maxCalories')
            ->setParameter('minCalories', $minCalories)
            ->setParameter('maxCalories', $maxCalories)
            ->getQuery()
            ->getResult();

        // Devolver la lista de recetas como respuesta
        return $this->json($recipes, 200);

    }
}
