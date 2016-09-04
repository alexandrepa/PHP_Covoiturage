<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//sur cette page on va afficher les differents trajets de l'utilisateur
require'../BDD.php';
$bdd = getBdd();
                               //voir inscription.php
require '../droits.php';
require '../formulaire.php';

function disabled($int) {//fonction qui permet de mettre un boutton en gris (disabled)
    if ($int == 1) {
        return "disabled='disabled'";
    } else {
        return "";
    }
}

test_membre();
ob_start();

//si l'utilisateur est un pilote alors on affiche ses trajets en tant que pilote
if ($_SESSION["pilote"]) {
    $rep_nb_trajet_pilote = $bdd->query("SELECT count(pilote_user_id) FROM trajet WHERE pilote_user_id = " . $_SESSION["id"]);
    $nb_trajet_pilote = $rep_nb_trajet_pilote->fetch();
    //si le pilote n'a aucun trajet en cours en tant que conducteur on l'affiche
    if ($nb_trajet_pilote[0] == '0') {
        echo "<br><br>Vous n'êtes actuellement inscrit pour aucun trajet en tant que pilote, rendez-vous dans la section \"Ajouter un trajet\"";
    } else { //sinon on affiche les trajets
        echo "<h1>Mes trajets en tant que pilote :</h1> ";
       
        //on fait un tableau contenant les infos des trajets
        echo "<p><table class='table table-hover'><tr><th>Départ</th><th>Arrivée</th><th>Places prises</th><th>Date</th><th>Heure</th><th>Prix</th><th></th><th></th><th></th></tr>";
        $reponse = $bdd->query("SELECT * FROM trajet WHERE effectue = FALSE AND pilote_user_id = " . $_SESSION["id"]);//on affiche que les trajets non effectue
       //on parcourt tous les trajets
        while ($donnee = $reponse->fetch()) {
            echo"<tr><td>" . $donnee["lieu_depart"] . "</td><td>" . $donnee["lieu_arrivee"] . "</td><td>" . $donnee["places_prises"] . "/" . $donnee["places_max"] . "</td><td>" . $donnee["date"] . "</td><td>" . $donnee["heure_dep"] . "</td><td>" . $donnee["prix"] . "</td>";
            
            //on va faire un formulaire pour chaque boutton submit (supprimer, valider, liste) afin d'envoyer par la methode POST l'id du trajet aux differentes pages
            echo '<td><form action="delete_trajet.php" method="post">';
            echo "<button type='submit' name='suppr' class='btn btn-danger' value='" . $donnee["id"] . "'>Supprimer</button></form></td>";
            echo '<td><form action="valide_trajet.php" method="post">';
            echo "<button type='submit' name='valide' class='btn btn-success' value='" . $donnee["id"] . "'>Valider</button></form></td>";
            echo '<td><form action="liste_user_trajet.php" method="post">';
            echo "<button type='submit' name='liste' class='btn btn-info' value='" . $donnee["id"] . "'>Liste Passagers</button></form></td>";
            echo"</tr>";
        }
        echo"</table></p>";
    }
}


//on va maintenant afficher les trajets de l'utilisateur en tant que pilote et on utilise le meme fonctionnement
$rep_nb_trajet = $bdd->query("SELECT count(user_id) FROM trajet_passager WHERE user_id = " . $_SESSION["id"]);
$nb_trajet = $rep_nb_trajet->fetch();
if ($nb_trajet[0] == '0') {
    echo "<br><br>Vous n'êtes actuellement inscrit pour aucun trajet, rendez-vous dans la section \"Rechercher un trajet\"";
} else {
    echo "<h1>Mes trajets en tant que passager : </h1>";
    echo "<p><table class='table table-hover'><tr><th>Départ</th><th>Arrivée</th><th>Places prises</th><th>Date</th><th>Heure</th><th>Prix</th><th>Pilote</th><th></th><th></th></tr>";
    $reponse = $bdd->query("SELECT * FROM user as U, trajet_passager as TP, trajet as T WHERE TP.trajet_id = T.id and TP.user_id = ".$_SESSION["id"]." AND T.pilote_user_id = U.id");
    while ($donnee = $reponse->fetch()) {
        $reponse2 = $bdd->query("SELECT * FROM evaluation WHERE trajet_id = " . $donnee["id"] . " AND evaluateur_user_id = " . $_SESSION["id"]);
        $nombre = $reponse2->fetchColumn();
        if ($nombre == 0) {
            echo"<tr><td>" . $donnee["lieu_depart"] . "</td><td>" . $donnee["lieu_arrivee"] . "</td><td>" . $donnee["nb_places"] . "/" . $donnee["places_max"] . "</td><td>" . $donnee["date"] . "</td><td>" . $donnee["heure_dep"] . "</td><td>" . $donnee["prix"] . "</td><td>" . $donnee["prenom"] . " " . $donnee["nom"] . "</td>";

            echo '<td><form action="../membre/envoyer_message.php" method="post">';
            echo "<button type='submit' name='destinataire' class='btn btn-info' value='" . $donnee["username"] . "'>Envoyer message au conducteur</button></form></td>";

            echo '<td><form action="../membre/fiche_eval.php" method="post">';
            echo "<button type='submit' " . disabled(!$donnee['effectue']) . " name='evaluer' class='btn btn-success' value='" . $donnee["id"] . "'>Evaluer</button></form></td>";
        }
    }
    echo"</table></p>";
}

$contenu = ob_get_clean();


$title = "Mes trajets";
gabarit();

