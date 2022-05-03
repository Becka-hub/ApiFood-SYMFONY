<?php
namespace App\Shared;

class Messages 
{
    public const SUCCESS  = ['status' => true, 'title' => 'success','message'=>'success','code' => 200];
    public const SUCCESS_INSERT  = ['status' => true, 'title' => 'success','message'=>'Insertion avec success', 'code' => 200];
    public const SUCCESS_UPDATE  = ['status' => true, 'title' => 'success','message'=>'Modification avec success', 'code' => 200];
    public const SUCCESS_DELETE  = ['status' => true, 'title' => 'success','message'=>'Supression avec success', 'code' => 200];
    public const REGISTER_SUCCESS  = ['status' => true, 'title' => 'success','message'=>'Inscription avec success', 'code' => 200];
    public const CHANGE_PASSWORD = ['status' => true, 'title' => 'success','message'=>'Mot de passe a bien changer,voir votre boite email', 'code' => 200];
    public const CHECK_EMAIL = ['status' => true, 'title' => 'success','message'=>'Veuillez verifier votre boite email', 'code' => 200];
    public const SUCCESS_RESETPASSWORD = ['status' => true, 'title' => 'success','message'=>'Mot de passe a bien changer', 'code' => 200];


    public const ERROR  = ['status' => false, 'title' => 'error','message'=>'error','code' => 400];
    public const FORM_INVALID= ['status' => false,'title'=>'error','message'=>'Quelque champ du formulaire est vide','code'=>400];
    public const USER_NOT_FOUND= ['status' => false,'title'=>'error','message'=>'Utilisateur n\'existe pas','code'=>404];
    public const EMAIL_NOT_FOUND= ['status' => false,'title'=>'error','message'=>'Adresse email n\'existe pas','code'=>404];
    public const MAILUSED = ['status'=>false,'title' => 'error', 'message' => 'Email déjà utilisé', 'code' => 400];
    public const PASSWORD_WRONG= ['status' => false,'title'=>'error','message'=>'Mot de passe incorrect','code'=>400];
    public const PASSWORD_SHORT= ['status' => false,'title'=>'error','message'=>'Mot de passe ne doit pas être inferieur a 6 caractères','code'=>400];
    public const TOKEN_INVALID= ['status' => false,'title'=>'error','message'=>'Token invalid','code'=>400];
    public const BAD_URL_TOKEN= ['status' => false,'title'=>'error','message'=>'Cette url ne contient pas de token','code'=>400];
    public const TOKEN_EXISTE= ['status' => false,'title'=>'error','message'=>'Vous ne pouvez pas changez de mot de passe qu\'après une heure','code'=>400];
    public const CATEGORY_EXISTE= ['status' => false,'title'=>'error','message'=>'Categorie déjas existé','code'=>400];
    public const CATEGORY_NOT_FOUND= ['status' => false,'title'=>'error','message'=>'Categorie n\'esiste pas','code'=>400];
    public const INVALID_FORMATE= ['status' => false,'title'=>'error','message'=>'Invalid format file! Format photo accepted png, jpg, jpeg','code'=>400];

}