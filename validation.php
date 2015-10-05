<?php
require('config/config.php');
require('model/functions.fn.php');
session_start();
if(	isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) &&
    !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    // vérifie que tous les champs sont renseignés

    $sql1 = "SELECT id FROM users WHERE email = :email LIMIT 1";
    $sql2 = "SELECT id FROM users WHERE username = :username LIMIT 1";

    $req1 = $db->prepare($sql1);
    $req1->execute(array(':email' => $_POST['email']));
    $result1 = $req1->fetch(PDO::FETCH_ASSOC);

    $req2 = $db->prepare($sql2);
    $req2->execute(array(':username' => $_POST['username']));
    $result2 = $req2->fetch(PDO::FETCH_ASSOC);

    if($result1 && $result2) {

        $request = $db->prepare("INSERT INTO membre(Pseudo, Mot_de_passe, email) VALUES (:pseudo, :mot_de_passe, :email)");
        // ranger les informations dans la table membres de la base donnée 
        $request->execute(
            array(
                'pseudo' => $_POST['pseudo'],
                'mot_de_passe' => $_POST['mot_de_passe'],
                'email' => $_POST['email']
            )
        );

        header('Location:login.php');
        echo "Inscription réussie";
        $request->closeCursor();
    }
    else {
        $_SESSION['message'] = 'Email ou Username déjà pris, veuillez en choisir un autre ';
        header('Location: register.php');
    }
}else{
    $_SESSION['message'] = 'Erreur : Formulaire incomplet';
    header('Location: register.php');
}