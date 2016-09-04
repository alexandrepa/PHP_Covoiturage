<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../BDD.php';
$bdd = getBdd();

require '../droits.php';    //voir inscription.php
require '../formulaire.php';
session_start();
if (empty($_POST)) {//on verifie que l'utilisateur vient bien de la page (mes trajets)
    header('Location: ../index.php');
    exit();
}
$trajet_id = $_POST["liste"]; //on recupere l'id du trajet

ob_start();
echo"<h1>Liste des passagers</h1>";

//pour chaque passager present sur le trajet on affiche ses infos
$reponse = $bdd->query("SELECT user_id, username, prenom, nom, nb_places FROM trajet_passager, user WHERE trajet_id = " . $trajet_id . " AND id=user_id");
while ($donnee = $reponse->fetch()) {
    echo"<div class='panel panel-success'>";
    echo"<div class='panel-heading'>";

    echo"<b>Nom :</b> " . $donnee["prenom"] . " " . $donnee["nom"];
    echo" - <b>Nombre de places prises : </b>" . $donnee["nb_places"];
    echo"</div>";
    echo "<div class='panel-body'>";
    echo "<div class='btn-group'>";

//on va faire un formulaire pour le bouton envoyer message afin d'envoyer l'username du destinataire a la page envoyer_message.php
    echo '<form action="../membre/envoyer_message.php" method="post">';
    echo "<button type='submit' class='btn btn-success' name='destinataire' value='" . $donnee["username"] . "'>Envoyer message</button>    ";

    //on fait un lien avec la methode GET vers le profil du passager
    echo"  <a href='../membre/profil.php?username=" . $donnee["username"] . "' class='btn btn-primary'>Son profil</a></form>";
    echo"</div>";
    echo"</div>";
    echo "</div>";
}
$contenu = ob_get_clean();


$title = "Liste trajet";
gabarit();

