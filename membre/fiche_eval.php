<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../formulaire.php';
require'../BDD.php';
$bdd = getBdd();
session_start();
$values = array(//tableau de correspondance des notes/evaluations
    1 => "a eviter",
    2 => "decevant",
    3 => "bien",
    4 => "excellent",
    5 => "extraordinaire"
);
$hiddens = array(//tableaux pour les attributs hidden et disabled des boutons radios de formulaire.php
    1 => TRUE,
    2 => TRUE,
    3 => TRUE,
    4 => TRUE,
    5 => TRUE
);
$disableds = array(
    1 => TRUE,
    2 => TRUE,
    3 => TRUE,
    4 => TRUE,
    5 => TRUE
);


if (isset($_POST["evaluer"])) { //si l'utilisateur est un passager (provenance verifiee a partir du bouton "evaluer" de la page mes trajets
    if (isset($_POST["form_eval_pilote"])) { //si le formulaire d'evaluation a ete soumis
        $reponse = $bdd->query("SELECT pilote_user_id FROM trajet  WHERE id = " . $_POST["trajet_id"] . ";"); //on trouve l'id du pilote
        while ($donnee = $reponse->fetch()) { //on donne la correspondance entre l'evaluation et la note
            if ($_POST["note"] == "a eviter") {
                $note = 1;
            }
            if ($_POST["note"] == "decevant") {
                $note = 2;
            }
            if ($_POST["note"] == "bien") {
                $note = 3;
            }
            if ($_POST["note"] == "excellent") {
                $note = 4;
            }
            if ($_POST["note"] == "extraordinaire") {
                $note = 5;
            }
            $sql = 'INSERT INTO evaluation (evaluateur_user_id,evalue_user_id,evaluation, trajet_id) VALUES (:evaluateur_user_id,:evalue_user_id,:evaluation,:trajet_id)'; //on insere la note dans la BDD
            $statement = $bdd->prepare($sql);
            $statement->execute(array(
                ":evaluateur_user_id" => $_SESSION["id"],
                ":evalue_user_id" => $donnee["pilote_user_id"],
                ":evaluation" => $note,
                ":trajet_id" => $_POST["trajet_id"]
            )); //fin de l'insertion
            $query_evals = $bdd->query("SELECT evaluation FROM evaluation E WHERE E.evalue_user_id = " . $donnee["pilote_user_id"]); //on calcule la moyenne de la note, puis on l'insere dans la table user pour l'id correspondante
            $moy = 0;
            $nb_eval = 0;
            while ($tab_evals = $query_evals->fetch()) {
                $moy = $moy + $tab_evals["evaluation"];
                $nb_eval = $nb_eval + 1;
            }
            $moy = ($moy / $nb_eval);
            $bdd->exec("UPDATE user SET note = " . $moy . " WHERE id = " . $donnee["pilote_user_id"] . ";"); //fin de l'insertion

            header('Location: ../index.php');
            exit();
        }
    } else { //sinon, on propose à l'utilisateur de soumettre le formulaire d'evaluation
        ob_start();
        form_debut("form", "POST", "fiche_eval.php"); //creation du formmulaire
        $reponse = $bdd->query("SELECT pilote_user_id FROM trajet T, user U WHERE T.pilote_user_id = U.id AND T.id = " . $_POST["evaluer"]);
        while ($donnee = $reponse->fetch()) {
            echo "<br>Attribuez une note au pilote:<br>";
            form_radio("note", $values, $hiddens, $disableds); //appel de la fonction de formulaire.php avec les trois tableaux déclaré au début de la page
        }
        form_hidden("trajet_id", $_POST["evaluer"]); //on retransmet l'id du trajet pour l'insertion
        form_hidden("evaluer", ""); ////on transmet à la page lors de la soumission pour rappeller que l'utilisateur est un passager qui evalue un pilote
        form_submit("form_eval_pilote", "Soumettre", FALSE);
        form_fin();
        $contenu = ob_get_clean();
        $title = "Evaluation du pilote";
        require '../gabarit/gabarit_passager.php';
    }
} else if (isset($_POST["acces_form_eval_pass"])) { //sinon, c'est un pilote qui vient de la page valide_trajet.php
    if (isset($_POST["form_eval_pass"])) {  //si il a soumis le formulaire: (meme principe que precedemment, sauf qu'ici, il y a une insertion par passager
        $i = 1;
        $reponse = $bdd->query("SELECT user_id FROM trajet_passager TP, trajet T WHERE TP.trajet_id = T.id AND T.id = " . $_POST["trajet_id"] . ";");
        while ($donnee = $reponse->fetch()) {
            if ($_POST["note" . $i] == "a eviter") {
                $note = 1;
            }
            if ($_POST["note" . $i] == "decevant") {
                $note = 2;
            }
            if ($_POST["note" . $i] == "bien") {
                $note = 3;
            }
            if ($_POST["note" . $i] == "excellent") {
                $note = 4;
            }
            if ($_POST["note" . $i] == "extraordinaire") {
                $note = 5;
            }
            $sql = 'INSERT INTO evaluation (evaluateur_user_id, evalue_user_id, evaluation, trajet_id) VALUES (:evaluateur_user_id, :evalue_user_id, :evaluation, :trajet_id)';
            $statement = $bdd->prepare($sql);
            $statement->execute(array(
                ":evaluateur_user_id" => $_SESSION["id"],
                ":evalue_user_id" => $donnee["user_id"],
                ":evaluation" => $note,
                ":trajet_id" => $_POST["trajet_id"]
            ));
            $query_evals = $bdd->query("SELECT evaluation FROM evaluation E WHERE E.evalue_user_id = " . $donnee["user_id"]);
            $moy = 0;
            $nb_eval = 0;
            while ($tab_evals = $query_evals->fetch()) {
                $moy = $moy + $tab_evals["evaluation"];
                $nb_eval = $nb_eval + 1;
            }
            $moy = ($moy / $nb_eval);
            $bdd->exec("UPDATE user SET note = " . $moy . " WHERE id = " . $donnee["user_id"] . ";");
            $i = $i + 1;
        }
        header('Location: ../index.php');
        exit();
    } else { //le formulaire d'evaluation de passager n'a pas ete soumis, on l'affiche, pour chaque passager, de la meme manière que pour l'evaluation des pilotes, et on concatène le nom du bouton radio avec un indice correspondant à chaque passager
        ob_start();
        form_debut("form", "POST", "fiche_eval.php");
        $reponse = $bdd->query("SELECT nom, prenom FROM trajet_passager TP, user U WHERE TP.user_id = U.id AND TP.trajet_id = " . $_POST["trajet_id"]);
        $index = 0;
        while ($donnee = $reponse->fetch()) {
            $index = $index + 1;
            echo "<br>Attribuez une note a " . $donnee["nom"] . " " . $donnee["prenom"] . ":<br>";
            form_radio("note" . $index, $values, $hiddens, $disableds);
        }
        form_hidden("trajet_id", $_POST["trajet_id"]);
        form_hidden("acces_form_eval_pass", "");
        form_hidden("index", $index);
        form_submit("form_eval_pass", "Soumettre", FALSE);
        form_fin();
        $contenu = ob_get_clean();
        $title = "Evaluation des passagers";
        require '../gabarit/gabarit_passager.php';
    }
} else {
    header('Location: ../indexx.php');
    exit();
}



