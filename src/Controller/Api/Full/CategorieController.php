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
use App\Entity\Category;

use function PHPUnit\Framework\fileExists;

class CategorieController extends AbstractController
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

    #[Route('/category', name: 'ajouteCategory', methods: 'POST')]
    public function ajouteCategory(Request $request): Response
    {
        $libelle = $request->request->get('libelle');
        $image=$request->files->get('photo');
        if (!isset($libelle,$image) || ($libelle === "" || $image==="")) {
            return $this->reponses->error(Messages::FORM_INVALID);
        }
        $categorie = $this->repository->categoryRepository()->findOneBy(['libelle' => $libelle]);
        if ($categorie) {
            return $this->reponses->error(Messages::CATEGORY_EXISTE);
        }
        $category = new Category();
        $extension = $image->guessExtension();
       
        if (isset($extension) && ($extension !== "png" && $extension !== "jpg" && $extension !== "jpeg")) {
 
         return $this->reponses->error(Messages::INVALID_FORMATE);
         }
       
         $photoName = $this->service->uploadFile($image, $this->getParameter('brochures_directory_category'));
         $photo_url='/uploads/category/'.$photoName;
         
        $category->setLibelle($libelle);
        $category->setPhoto($photoName);
        $category->setPhotoUrl($photo_url);
        $this->service->em()->persist($category);
        $this->service->em()->flush();
        return $this->reponses->success($category->tojson(), 1, Messages::SUCCESS_INSERT);
    }


    #[Route('/category', name: 'afficheCategory', methods: 'GET')]
    public function afficheCategory(): Response
    {
        $categories = $this->repository->CategoryRepository()->findAll();
        return $this->reponses->success(array_map(function (Category $category) {
            return $category->tojson();
        }, $categories), count($categories), Messages::SUCCESS);
    }

 #[Route('/category/{id}', name: 'afficheOneCategory', methods: 'GET')]
    public function afficheOneCategory($id): Response
    {
        $categorie = $this->repository->CategoryRepository()->findOneById($id);
        return $this->reponses->success($categorie->tojson(true),1, Messages::SUCCESS);
    }


    #[Route('/category/{id}', name: 'suprimerCategory', methods: 'DELETE')]
    public function suprimerCategory($id): Response
    {
        $categorie = $this->repository->CategoryRepository()->find($id);
        if (!$categorie) {
            return $this->reponses->error(Messages::CATEGORY_NOT_FOUND);
        }

        $photoName=$this->getParameter('brochures_directory_category').$categorie->getPhoto();
        if(fileExists($photoName)){
            unlink($photoName);
        }

        $this->service->em()->remove($categorie);
        $this->service->em()->flush();
        return $this->reponses->success(null, null, Messages::SUCCESS_DELETE);
    }
}
