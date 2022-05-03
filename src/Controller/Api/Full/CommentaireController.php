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
use App\Entity\Commentaire;
class CommentaireController extends AbstractController
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

    #[Route('/commentaire/{id}', name: 'afficherCommentaire', methods: 'GET')]
    public function afficherCommentaire($id):Response
    {
       $commentaires=$this->repository->CommentaireRepository()->findByFood($id);
       return $this->reponses->success(array_map(function (Commentaire $commentaire) {
        return $commentaire->tojson();
    }, $commentaires),count($commentaires),Messages::SUCCESS);
    }


    #[Route('/commentaire', name: 'commentaire', methods: 'GET')]
    public function commentaire():Response
    {
       $commentaires=$this->repository->CommentaireRepository()->findAll();
       return $this->reponses->success(array_map(function (Commentaire $commentaire) {
        return $commentaire->tojson();
    }, $commentaires),count($commentaires),Messages::SUCCESS);
    }

       #[Route('/food/{id}', name: 'afficheFood', methods: 'GET')]
    public function afficheFood($id)
    {
        $foods = $this->repository->FoodRepository()->findBy(['user'=>$id],['id'=>'DESC']);
        return $this->reponses->success(array_map(function (Food $food) {
            return $food->tojson(true,true,true);
        }, $foods), count($foods), Messages::SUCCESS);
    }


}