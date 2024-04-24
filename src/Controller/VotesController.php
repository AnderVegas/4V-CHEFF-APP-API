<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RecipeRepository;

class VotesController extends AbstractController
{
    #[Route('/recipes/{recipeId}/rating/{rate}', name: 'add_recipe_rating', methods: ['POST'])]
    public function addRecipeRating(int $recipeId, int $rate, EntityManagerInterface $entityManager, RecipeRepository $recipeRepository, Request $request): JsonResponse
    {


        // // Obtener la dirección IP del usuario
        // $userIp = $request->getClientIp();

        // // Verificar si la dirección IP ya ha votado por esta receta
        // $existingVote = $entityManager->getRepository(Vote::class)->findOneBy(['ipAddress' => $userIp, 'recipe' => $recipeId]);

        // if ($existingVote) {
        //     return $this->json(['error' => 'No se permite la repetición de votos'], 401);
        // }

        
        // Verificar que el rating esté dentro del rango válido (0-5)
        if ($rate < 0 || $rate > 5) {
            return new JsonResponse(['error' => 'El rating debe ser un número entero entre 0 y 5'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Obtener la receta correspondiente al ID proporcionado
        $recipe = $entityManager->getRepository(Recipe::class)->find($recipeId);

        // Verificar si la receta existe
        if (!$recipe) {
            return new JsonResponse(['error' => 'La receta no existe'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Crear un nuevo voto
        $vote = new Vote();
        $vote->setRate($rate);
        $vote->setIdRecipe($recipe);

        // Guardar el voto en la base de datos
        $entityManager->persist($vote);
        $entityManager->flush();

        // Recalcular los ratings de la receta
        $votes = $recipe->getVotes();
        $totalVotes = count($votes);
        $totalRating = 0;
        foreach ($votes as $vote) {
            $totalRating += $vote->getRate();
        }
        $averageRating = $totalVotes > 0 ? $totalRating / $totalVotes : 0;

        // Devolver la información actualizada de la receta incluyendo los ratings
        $responseData = [
            'id' => $recipe->getId(),
            'title' => $recipe->getTitle(),
            'number_diner' => $recipe->getNumberDiner(),
            'ingredients' => $recipe->getIngredients(),
            'steps' => $recipe->getSteps(),
            'nutrients' => $recipe->getNutrients(),
            'rating' => [
                'number_votes' => $totalVotes,
                'average_rating' => $averageRating,
            ],
        ];

        return new JsonResponse($responseData);
    }
    
}


