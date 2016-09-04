<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

                               //la page a la même structure que inscription.php, voir cette page pour les commentaires.
require'../BDD.php';
$bdd = getBdd();

require '../formulaire.php';   

require '../droits.php';

test_passager(); //on verifie que c'est un passager
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {
    $imageFileType = strtolower(substr(strrchr($_FILES['pic']['name'], '.'), 1));
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['marque']) && !empty($_POST['marque'])) && (isset($_POST['modele']) && !empty($_POST['modele'])) && (isset($_POST['annee']) && !empty($_POST['annee'])) && (isset($_POST['couleur']) && !empty($_POST['couleur'])) && ($_FILES["pic"]["size"] < 500000000) && ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" )) {
        $contenu = action();
        $_SESSION["pilote"] = TRUE;
        
    } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $contenu = "Format du fichier incorrect, accepté : jpg, jpeg, png, gif";
    } else if ($_FILES["pic"]["size"] > 500000000) {
        $contenu = "Taille de l'image trop importante, elle doit être inférieur à 5 Mo";
    } else {
        $contenu = "Certains champs ne sont pas remplis";
    }
} else {
    $contenu = formulaire();
}

function formulaire() {
    ob_start();
    ?>
    <h1>Description de votre véhicule</h1>
    <?php
    form_debut("form", "POST", "inscription_voiture.php");
    form_label("Marque");
    form_input_text("marque", TRUE, "", "", 20, "verifmarque();");
    echo"<br><br>";
    form_label("Modèle");
    form_input_text("modele", TRUE, "", "", 20, "verifmodele();");
    echo"<br><br>";
    form_label('Année');
    form_input_text("annee", TRUE, "", "", 10, "verifannee();");
    echo"<br><br>";
    form_label("Couleur");
    form_input_text("couleur", TRUE, "", "", 25, "verifcouleur();");
    echo"<br><br>";
    form_label("Photo");
    form_file("pic", "image/*", "");
    echo"<br><br>";
    form_submit("Soumettre", "Soumettre", FALSE);
    form_reset("Reset", "Reinitialiser", FALSE, FALSE);
    form_fin();
    ?>
        <?php
        return ob_get_clean();
    }

    function action() {
        $chemin_destination = '../photo_voiture/';
        move_uploaded_file($_FILES["pic"]['tmp_name'], $chemin_destination . $_SESSION["login"] . strrchr($_FILES['pic']['name'], '.'));
        global $bdd;
        $sql = 'INSERT INTO pilote (pilote_user_id,voiture_marque,voiture_annee,voiture_modele,voiture_couleur,photo) VALUES(:pilote_user_id,:voiture_marque,:voiture_annee,:voiture_modele,:voiture_couleur,:photo)';
        $statement = $bdd->prepare($sql);
        $statement->execute(array(
            ":pilote_user_id" => $_SESSION["id"],
            ":voiture_marque" => $_POST["marque"],
            ":voiture_annee" => $_POST["annee"],
            ":voiture_modele" => $_POST["modele"],
            ":voiture_couleur" => $_POST["couleur"],
            ":photo" => $chemin_destination . $_SESSION["login"] . strrchr($_FILES['pic']['name'], '.'),
        ));

         return "<div class='alert alert-success'>Inscription de la voiture réussie.</div>";
    }

    $title = "Inscription voiture";
    gabarit();

    