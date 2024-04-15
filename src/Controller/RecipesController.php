<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class RecipesController extends AbstractController
{
    #[Route('/recipes', name: 'app_recipes')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/RecipesController.php',
        ]);
    }
}


// RecipeController.php
// <?php

// namespace App\Controller;

// use App\Entity\Recipe;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\Routing\Annotation\Route;

// class RecipeController extends AbstractController
// {
//     #[Route('/recipes', name: 'new_recipe', methods: ['POST'])]
//     public function newRecipe(Request $request): JsonResponse
//     {
//         $data = json_decode($request->getContent(), true);

//         // Crear una nueva instancia de Recipe y asignar los datos recibidos
//         $recipe = new Recipe();
//         $recipe->setTitle($data['title']);
//         $recipe->setNumberDiner($data['number-diner']);

//         // Agregar ingredientes
//         foreach ($data['ingredients'] as $ingredientData) {
//             $ingredient = new Ingredient();
//             $ingredient->setName($ingredientData['name']);
//             $ingredient->setQuantity($ingredientData['quantity']);
//             $ingredient->setUnit($ingredientData['unit']);
//             $recipe->addIngredient($ingredient);
//         }

//         // Agregar pasos
//         foreach ($data['steps'] as $stepData) {
//             $step = new Step();
//             $step->setOrder($stepData['order']);
//             $step->setDescription($stepData['description']);
//             $recipe->addStep($step);
//         }

//         // Guardar la nueva receta en la base de datos
//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->persist($recipe);
//         $entityManager->flush();

//         // Devolver la nueva receta como respuesta
//         return $this->json($recipe, 201);
//     }
// }
