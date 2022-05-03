<?php
namespace App\Controller\Api\Secure;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use App\Shared\Reponses;
use App\Shared\Messages;
use App\Service\Service;
use App\Service\Repository;
use App\Entity\Jaime;

#[Route('/api', name: 'ctrl_jaime')]
#[Security("is_granted('ROLE_USER')")]
class JaimeController extends AbstractController
{
    private Repository $repository;
    private Service $service;
    private Reponses $reponses;

    public function __construct(Repository $repository, Service $service, Reponses $reponses)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->reponses = $reponses;
    }

    #[Route('/jaime', name: 'ajouteJaime', methods: 'POST')]
    public function ajouteJaime()
    {
        $data=$this->service->json_decode();
        if (!isset($data->jaime, $data->idUser,$data->idFood) || ($data->jaime === "" || $data->idUser === "" || $data->idFood === "")) {
            return $this->reponses->error(Messages::FORM_INVALID);
        }

        $user=$this->repository->UserRepository()->findOneById($data->idUser);
        $food=$this->repository->FoodRepository()->findOneById($data->idFood);

        $jaime=new Jaime();
        $jaime->setJaime($data->jaime);
        $jaime->setUser($user);
        $jaime->setFood($food);
        $this->service->em()->persist($jaime);
        $this->service->em()->flush();
        return $this->reponses->success($jaime->tojson(),1,Messages::SUCCESS_INSERT);
    }

    #[Route('/jaime/{idUser}/{idFood}', name: 'suprimerJaime', methods: 'DELETE')]
    public function suprimerJaime($idUser,$idFood)
    {
        $jaime=$this->repository->JaimeRepository()->findOneBy(["user"=>$idUser,"food"=>$idFood]);
        $this->service->em()->remove($jaime);
        $this->service->em()->flush();
        return $this->reponses->success(null,null,Messages::SUCCESS_DELETE);
    }
}