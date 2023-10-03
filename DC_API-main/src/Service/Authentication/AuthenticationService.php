<?php

namespace App\Service\Authentication;

class AuthenticationService {
  
  //Fonction checkEmail
  //Vérifie si l'adresse email envoyée n'est pas vide et si elle est valide.
  public function checkEmail(string $email) {
    if(empty($email)){
      return [
        'status' => 'error',
        'message' => "L'adresse email est vide."
      ];
    }

    if(!preg_match('/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/', $email)){
      return [
        'status' => 'error',
        'message' => "L'adresse mail n'est pas valide"
      ];
    }

    return true;
  }

  //Fonction checkPassword
  //Vérifie si le mot de passe n'est pas vide et si il correspond bien niveau sécurité
  public function checkPassword(strign $password) {
    if(empty($password)) {
      return [
        'status' => 'error',
        'message' => 'Le mot de passe est vide.'
      ];
    }

    if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+{}\[\]:;<>,.?~\\-]).{8,}$/', $password)){
      return [
        'status' => 'error',
        'message' => "Le mot de passe n'est pas assez sécurisé"
      ];
    }

    return true;
  }

}
