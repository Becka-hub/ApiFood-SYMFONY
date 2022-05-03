<?php 
namespace App\Service;

use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentaireRepository;
use App\Repository\FoodRepository;
use App\Repository\IngredientRepository;
use App\Repository\JaimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class Repository extends AbstractController
{
    private UserRepository $userRepository;
    private CategoryRepository $categoryRepository;
    private CommentaireRepository $commentaireRepository;
    private FoodRepository $foodRepository;
    private IngredientRepository $ingredientRepository;
    private JaimeRepository $jaimeRepository;

   
    public function __construct(CategoryRepository $categoryRepository,IngredientRepository $ingredientRepository,FoodRepository $foodRepository,CommentaireRepository $commentaireRepository,UserRepository $userRepository,JaimeRepository $jaimeRepository)
    {
        $this->userRepository=$userRepository;
        $this->categoryRepository=$categoryRepository;
        $this->commentaireRepository=$commentaireRepository;
        $this->foodRepository=$foodRepository;
        $this->ingredientRepository=$ingredientRepository;
        $this->jaimeRepository=$jaimeRepository;
    }

    public function UserRepository()
    {
        return $this->userRepository;
    }
    public function JaimeRepository()
    {
        return $this->jaimeRepository;
    }
    public function CategoryRepository()
    {
        return $this->categoryRepository;
    }
    public function CommentaireRepository()
    {
        return $this->commentaireRepository;
    }
    public function FoodRepository()
    {
        return $this->foodRepository;
    }
    public function IngredientRepository()
    {
        return $this->ingredientRepository;
    }



}