<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\NutrientesType;
use App\Entity\Step;
use App\Entity\Ingredient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Repository\RecipeRepository;
use App\Repository\NutrientesTypeRepository;

class RecipesController extends AbstractController
{
    
    #[Route('/recipes', name: 'new_recipe', methods: ['POST'])]
    public function newRecipe(Request $request, EntityManagerInterface $entityManager, PersistenceManagerRegistry $doctrine): JsonResponse
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
            $nutrientType = $entityManager->getRepository(NutrientesType::class)->find($nutrientData['id']);
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
            $step->setOrderr($stepData['order']);
            $step->setDescription($stepData['description']);
            $recipe->addStep($step);
        }

        // Agregar nutrientes
        $entityManager = $doctrine->getManager();
        $nutrientTypeRepository = $entityManager->getRepository(NutrientesType::class);
        foreach ($data['nutrients'] as $nutrientData) {
            $nutrientTypeId = $nutrientData['id'];
            $quantity = $nutrientData['quantity'];
            $nutrientType = $nutrientTypeRepository->find($nutrientTypeId);
            if ($nutrientType) {
                $recipe->addNutrient($nutrientType);
            }
        }

        // Guardar la nueva receta en la base de datos
        $entityManager = $doctrine->getManager();
        $entityManager->persist($recipe);
        $entityManager->flush();

        // Devolver la nueva receta como respuesta
        return $this->json($recipe, 201);
    }


    #[Route('/recipes', name: 'get_recipes_with_ratings', methods: ['GET'])]
    public function getRecipesWithRatings(RecipeRepository $recipeRepository): JsonResponse
    {
        // Obtener todas las recetas
        $recipes = $recipeRepository->findAll();
    
        $recipesWithRatings = [];
    
        // Iterar sobre cada receta para calcular el número total de votos y la valoración media
        foreach ($recipes as $recipe) {
            $totalVotes = $recipe->getVotes()->count();
    
            if ($totalVotes > 0) {
                // Calcular la valoración media
                $totalRating = 0;
                foreach ($recipe->getVotes() as $vote) {
                    $totalRating += $vote->getRate();
                }
                $averageRating = $totalRating / $totalVotes;
            } else {
                $averageRating = 0;
            }
    
            // Construir un array con la información de la receta y las votaciones
            $recipeData = [
                'id' => $recipe->getId(),
                'title' => $recipe->getTitle(),
                'number-diner' => $recipe->getNumberDiner(),
                'ratings' => [
                    'total-votes' => $totalVotes,
                    'average-rating' => $averageRating
                ]
            ];
    
            $recipesWithRatings[] = $recipeData;
        }
    
        // Devolver la lista de recetas con las votaciones como respuesta
        return $this->json($recipesWithRatings, 200);
    }
}       