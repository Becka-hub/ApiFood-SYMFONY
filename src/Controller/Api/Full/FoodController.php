<?php
namespace App\Controller\Api\Full;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Shared\Reponses;
use App\Shared\Messages;
use App\Service\Service;
use App\Service\Repository;
use App\Entity\Food;

class FoodController extends AbstractController
{
    private Reponses $reponses;
    private Service $service;
    private Repository $repository;

    public function __construct(Reponses $reponses, Service $service, Repository $repository)
    {
        $this->reponses = $reponses;
        $this->service = $service;
        $this->repository = $repository;
    }
    #[Route('/food', name: 'afficheFood', methods: 'GET')]
    public function afficheFood()
    {
        $foods=$this->repository->FoodRepository()->findBy([],['id'=>'DESC']);
        return $this->reponses->success(array_map(function (Food $food) {
            return $food->tojson(true,true,true);
        }, $foods),count($foods),Messages::SUCCESS);
    }

    #[Route('/food/{id}', name: 'afficheCategoryFood', methods: 'GET')]
    public function afficheCategoryFood($id)
    {
        $foods=$this->repository->FoodRepository()->findByCategory($id);
        return $this->reponses->success(array_map(function (Food $food) {
            return $food->tojson(true,true,true);
        }, $foods),count($foods),Messages::SUCCESS);
    }


    #[Route('/oneFood/{id}', name: 'afficheOneFood', methods: 'GET')]
    public function afficheOneFood($id)
    {
        $food=$this->repository->FoodRepository()->findOneById($id);
        return $this->reponses->success($food->tojson(true,true,true),1,Messages::SUCCESS);
    }


    #[Route('/foodUser/{id}', name: 'afficheFoodUser', methods: 'GET')]
    public function afficheFoodUser($id)
    {
        $foods = $this->repository->FoodRepository()->findBy(['user'=>$id],['id'=>'DESC']);
        return $this->reponses->success(array_map(function (Food $food) {
            return $food->tojson(true,true,true);
        }, $foods), count($foods), Messages::SUCCESS);
    }
}