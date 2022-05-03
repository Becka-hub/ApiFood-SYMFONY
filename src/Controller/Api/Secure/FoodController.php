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
use App\Entity\Ingredient;
use App\Entity\Food;

use function PHPUnit\Framework\fileExists;

#[Route('/api', name: 'ctrl_food')]
#[Security("is_granted('ROLE_USER')")]
class FoodController extends AbstractController
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

    #[Route('/food', name: 'ajouteFood', methods: 'POST')]
    public function ajouteFood()
    {
        $data = $this->service->json_decode();
        if (!isset($data->libelle, $data->description, $data->photo, $data->idUser, $data->idCategory, $data->ingredient) || ($data->libelle === "" || $data->photo === "" || $data->description === "" || $data->idUser === "" || $data->idCategory === "" || $data->ingredient === "")) {
            return $this->reponses->error(Messages::FORM_INVALID);
        }

        $category = $this->repository->CategoryRepository()->findOneById($data->idCategory);
        $user = $this->repository->UserRepository()->findOneById($data->idUser);

        $imageName = $this->service->fichier64($this->getParameter('brochures_directory_food'), $data->photo);
        $imageUrl = '/uploads/food/' . $imageName;

        $food = new Food();
        $food->setLibelle($data->libelle);
        $food->setDescription($data->description);
        $food->setPhoto($imageName);
        $food->setPhotoUrl($imageUrl);
        $food->setCategory($category);
        $food->setUser($user);
        $this->service->em()->persist($food);

        $ingredient = new Ingredient();
        $ingredient->setLibelle($data->ingredient);
        $ingredient->setFood($food);
        $this->service->em()->persist($ingredient);

        $this->service->em()->flush();

       return $this->reponses->success($food->tojson(), 1, Messages::SUCCESS_INSERT);
    }


    #[Route('/food/{id}', name: 'afficheFood', methods: 'GET')]
    public function afficheFood($id)
    {
        $foods = $this->repository->FoodRepository()->findBy(['user'=>$id],['id'=>'DESC']);
        return $this->reponses->success(array_map(function (Food $food) {
            return $food->tojson(true,true,true);
        }, $foods), count($foods), Messages::SUCCESS);
    }

    #[Route('/food/{id}', name: 'suprimerFood', methods: 'DELETE')]
    public function suprimerFood($id)
    {
        $food = $this->repository->FoodRepository()->findOneById($id);
        $photoName=$this->getParameter('brochures_directory_food').$food->getPhoto();
        if(fileExists($photoName)){
            unlink($photoName);
        }
        $this->service->em()->remove($food);
        $this->service->em()->flush();
        return $this->reponses->success(null, null, Messages::SUCCESS_DELETE);
    }
}
