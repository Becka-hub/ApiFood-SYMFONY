<?php
namespace App\Controller\Api\Full;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Shared\Reponses;
use App\Shared\Messages;
use App\Service\Repository;
use App\Entity\User;

class UserController extends AbstractController
{
    private Reponses $reponses;
    private Repository $repository;

    public function __construct(Reponses $reponses, Repository $repository)
    {
        $this->reponses = $reponses;
        $this->repository = $repository;
    }

    #[Route('/user', name: 'afficheUser', methods: 'GET')]
    public function afficheUser()
    {
        $users=$this->repository->UserRepository()->findAll();
        return $this->reponses->success(array_map(function (User $user) {
            return $user->tojson();
        }, $users),count($users),Messages::SUCCESS);
    }

    #[Route('/user/{id}', name: 'afficheOneUser', methods: 'GET')]
    public function afficheOneUser($id)
    {
        $user=$this->repository->UserRepository()->findOneById($id);
        return $this->reponses->success($user->tojson(true,true,true),1,Messages::SUCCESS);
    }
}