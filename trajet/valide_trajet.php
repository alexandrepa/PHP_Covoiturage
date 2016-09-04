<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require'../BDD.php';
$bdd = getBdd();

require '../droits.php';
require '../formulaire.php';
session_start();
if (empty($_POST)) {

    header('Location: ../index.php');
    exit();
}
ob_start();
$trajet_id = $_POST["valide"]; //on recupere le trajet 
$bdd->exec("UPDATE trajet SET effectue = 1 WHERE id = " . $trajet_id . ";"); // on recupere le trajet
$reponse = $bdd->query("SELECT username, user_id, prenom, nom, nb_places, prix, date FROM trajet_passager TP, trajet T, user U WHERE TP.trajet_id = T.id AND TP.user_id = U.id AND TP.trajet_id = " . $trajet_id . ";");
echo"<h1>Versements recus</h1>"; // on inscrit dans un tableau les versements reçus par les differents passagers du trajet
while ($donnee = $reponse->fetch()) { //on parcours chaque passager
    echo"<div class='panel panel-success'>";
    echo"<div class='panel-heading'>";

    echo"<b>Nom :</b> " . $donnee["prenom"] . " " . $donnee["nom"];
    echo" - <b>Nombre de places prises : </b>" . $donnee["nb_places"];
    echo"</div>";
    echo "<div class='panel-body'>";
    echo"<p> Somme reçue : " . $donnee["prix"] * $donnee["nb_places"] . " €.";

    echo"</div>";
    echo"</div>";

    $bdd->exec("UPDATE user SET compte = compte - (" . $donnee["prix"] * $donnee["nb_places"] . ") WHERE id = " . $donnee["user_id"] . ";"); //on met a jour la variable compte du passager
    $bdd->exec("UPDATE user SET compte = compte + (" . $donnee["prix"] * $donnee["nb_places"] . ") WHERE id = " . $_SESSION["id"] . ";"); //on met a jour la variable compte du pilote
    $sql = 'INSERT INTO transaction (credit_user_id,debit_user_id,somme) VALUES(:credit_user_id,:debit_user_id,:somme)'; //on ajoute la transaction dans la table transaction de la BDD
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":credit_user_id" => $_SESSION["id"],
        ":debit_user_id" => $donnee["user_id"],
        ":somme" => ($donnee["nb_places"] * $donnee["prix"])
    )); //fin de l'insertion
    $sql = 'INSERT INTO messagerie (expediteur_user_id,destinataire_user_id,titre, message,date) VALUES(:expediteur_user_id,:destinataire_user_id,:titre,:message,:date)'; //on envoie le message au passager pour l'informer du versement et du fait qu'il peut desormais l'evaluer
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":destinataire_user_id" => $donnee["user_id"],
        ":expediteur_user_id" => $_SESSION["id"],
        ":message" => "Le conducteur " . $_SESSION["prenom"] . " " . $_SESSION["nom"] . " a valide le trajet du " . $donnee["date"] . ". Vous avez ete debite de " . $donnee["nb_places"] * $donnee["prix"] . " €, et vous pouvez desormais l'evaluer",
        ":titre" => "Validation trajet",
        ":date" => date('Y-m-d H:i:s')
    )); //fin de l'insertion dans la table messagerie de la BDD
}
form_debut("form", "POST", "../membre/fiche_eval.php"); //on invite le pilote a evaluer ses passagers
form_hidden("trajet_id", $trajet_id); //on fait suivre l'id du trajet
form_submit("acces_form_eval_pass", "Evaluer les passagers", FALSE); //on transmet le formulaire avecu n nom spécifique pour attester de la provenance du formulaire
form_fin();
$contenu = ob_get_clean();


$title = "Compte rendu du trajet";
gabarit();



