<?php
namespace App\Controller\Api\Auth;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Shared\Reponses;
use App\Shared\Messages;
use App\Service\Service;
use App\Service\Repository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
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

    
    #[Route('/inscription', name: 'inscription', methods: 'POST')]
    public function inscription(): Response
    {
        $data = $this->service->json_decode();
        if(!isset($data->nom,$data->prenom,$data->email,$data->password,$data->photo) || ($data->nom==="" || $data->prenom==="" ||$data->email===""||$data->password===""||$data->photo==="")){
            return $this->reponses->error(Messages::FORM_INVALID);
        }
        if(strlen($data->password) < 6){
            return $this->reponses->error(Messages::PASSWORD_SHORT);
        }
        $user = $this->repository->UserRepository()->findOneByEmail($data->email);
        if ($user) {
            return $this->reponses->error(Messages::MAILUSED);
        }

        $imageName = $this->service->fichier64($this->getParameter('brochures_directory_user'), $data->photo);
        $imageUrl = '/uploads/user/' . $imageName;

        $user = new User();
        $user->setNom($data->nom);
        $user->setPrenom($data->prenom);
        $user->setEmail($data->email);
        $user->setPassword($this->service->hasher()->hashPassword($user, $data->password));
        $user->setRoles(["ROLE_USER"]);
        $user->setPhoto($imageName);
        $user->setPhotoUrl($imageUrl);

        $this->service->em()->persist($user);
        $this->service->em()->flush();
        return $this->reponses->success($user->tojson(), 1, Messages::REGISTER_SUCCESS);
    }

    #[Route('/login', name: 'login', methods: 'POST')]
    public function login(JWTTokenManagerInterface $tokenManager): Response
    {
        $data = $this->service->json_decode();
        if(!isset($data->email,$data->password) || ($data->email==="" || $data->password==="")){
            return $this->reponses->error(Messages::FORM_INVALID);
        }
        $user = $this->repository->UserRepository()->findOneByEmail($data->email);
        if (!$user) {
            return $this->reponses->error(Messages::USER_NOT_FOUND);
        }

        if (!$this->service->hasher()->isPasswordValid($user, $data->password)) {
            return $this->reponses->error(Messages::PASSWORD_WRONG);
        }

        return $this->reponses->successLogin($user->tojson(true,true,true), 1, $tokenManager->create($user), Messages::SUCCESS);
    }
}