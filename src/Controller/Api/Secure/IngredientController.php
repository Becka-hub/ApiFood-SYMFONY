<?php
namespace App\Controller\Api\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use App\Shared\Reponses;
use App\Shared\Messages;
use App\Service\Repository;
use App\Entity\Ingredient;
#[Route('/api', name: 'ctrl_ingredient')]
#[Security("is_granted('ROLE_USER')")]
class IngredientController extends AbstractController
{
	private Repository $repository;
    private Reponses $reponses;

    public function __construct(Repository $repository, Reponses $reponses)
    {
        $this->repository = $repository;
        $this->reponses = $reponses;
    }

    #[Route('/ingredient/{id}', name: 'afficheIngredient', methods: 'GET')]
    public function afficheIngredient($id)
    {
        $ingredients = $this->repository->IngredientRepository()->findByFood($id);
        return $this->reponses->success(array_map(function (Ingredient $ingredient) {
            return $ingredient->tojson();
        }, $ingredients), count($ingredients), Messages::SUCCESS);
    }
}