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
use App\Entity\Jaime;
class JaimeController extends AbstractController
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

    #[Route('/jaime/{id}', name: 'afficherJaime', methods: 'GET')]
    public function afficherJaime($id):Response
    {
       $jaimes=$this->repository->JaimeRepository()->findByFood($id);
       return $this->reponses->success(array_map(function (Jaime $jaime) {
        return $jaime->tojson();
    }, $jaimes),count($jaimes),Messages::SUCCESS);
    }

    #[Route('/jaime', name: 'jaime', methods: 'GET')]
    public function jaime():Response
    {
       $jaimes=$this->repository->JaimeRepository()->findAll();
       return $this->reponses->success(array_map(function (Jaime $jaime) {
        return $jaime->tojson();
    }, $jaimes),count($jaimes),Messages::SUCCESS);
    }
}