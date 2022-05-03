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
use App\Entity\Commentaire;

#[Route('/api', name: 'ctrl_commentaire')]
#[Security("is_granted('ROLE_USER')")]
class CommentaireController extends AbstractController
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

    #[Route('/commentaire', name: 'ajouteCommentaire', methods: 'POST')]
    public function ajouteCommentaire()
    {
        $data=$this->service->json_decode();
        if(!isset($data->commentaire,$data->idUser,$data->idFood) || ($data->commentaire==="" || $data->idUser==="" || $data->idFood==="")){
            return $this->reponses->error(Messages::FORM_INVALID);
        }

        $user=$this->repository->UserRepository()->findOneById($data->idUser);
        $food=$this->repository->FoodRepository()->findOneById($data->idFood);

        $commentaire=new Commentaire();
        $commentaire->setCommentaire($data->commentaire);
        $commentaire->setUser($user);
        $commentaire->setFood($food);
        $this->service->em()->persist($commentaire);
        $this->service->em()->flush();
        return $this->reponses->success($commentaire->tojson(),1,Messages::SUCCESS_INSERT);

    }

    #[Route('/commentaire/{idUser}/{idFood}/{idCommentaire}', name: 'suprimerCommentaire', methods: 'DELETE')]
    public function suprimerCommentaire($idUser,$idFood,$idCommentaire)
    {
       $commentaire=$this->repository->CommentaireRepository()->findOneBy(["id"=>$idCommentaire,"user"=>$idUser,"food"=>$idFood]);
       $this->service->em()->remove($commentaire);
       $this->service->em()->flush();
       return $this->reponses->success(null,null,Messages::SUCCESS_DELETE);
    }
}