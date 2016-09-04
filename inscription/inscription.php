<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require'../BDD.php'; //on inclut le fichier qui contient les infos sur la BDD puis on le met dans la variable bdd à l'aide de la fonction getBdd
$bdd = getBdd();

require'../formulaire.php'; //on inclut les fonctions de formulaires pour construire rapidement le formulaire de la page

require '../droits.php'; //on inclut les fonctions qui permettent de tester et de vérifier qui peut accéder à cette page
test_visiteur(); //on teste si c'est un visiteur si ce n'en est pas un on le redirige vers l'index
// on teste si le visiteur a soumis le formulaire

if (isset($_POST['Soumettre']) && $_POST['Soumettre'] == 'Soumettre') {
    $imageFileType = strtolower(substr(strrchr($_FILES['pic']['name'], '.'), 1)); //on met dans une chaine le type de l'image soumise
   
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['password']) && !empty($_POST['password'])) && (isset($_POST['prenom']) && !empty($_POST['prenom'])) && (isset($_POST['nom']) && !empty($_POST['nom'])) && (isset($_POST['anniversaire']) && !empty($_POST['anniversaire'])) && (isset($_POST['email']) && !empty($_POST['email'])) && ($_FILES["pic"]["size"] < 500000000) && ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif" )) {
        $sql = 'SELECT count(*) FROM user WHERE username="' . $_POST['login'] . '"OR email="' . $_POST['email'] . '"';
        $reponse = $bdd->query($sql);
        $donnee = $reponse->fetch();        //on regarde si il existe deja l'email ou le login soumit
        if ($donnee[0] == 0) {
            $contenu = action();   //si ils n'existent pas alors on appelle la fonction action qui va inscrire l'utilisateur
        } else {

            $contenu = "Login ou adresse email déjà utilisé";
        }
    } else if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $contenu = "<div class='alert alert-error'>Format du fichier incorrect, accepté : jpg, jpeg, png, gif</div>";
    } else if ($_FILES["pic"]["size"] > 500000000) {
        $contenu = "Taille de l'image trop importante, elle doit être inférieur à 5 Mo";
    } else {
        $contenu = "<div class='alert alert-error'>Certains champs ne sont pas remplis</div>";
    }
} else {
    $contenu = formulaire(); //si le formulaire n'est pas soumis alors cela veut dire que le visiteur vient d'arriver et on affiche le formulaire
}

function formulaire() {
    ob_start(); //on met en memoire tampon le code hmtl qui va suivre
    ?>
    <h1>Inscription</h1>
    <?php
    form_debut("form", "POST", "inscription.php");
    form_label("Nom");
    form_input_text("nom", TRUE, "", "", 30, "verifnom();");
    echo"<br><br>";
    form_label("Prénom");
    form_input_text("prenom", TRUE, "", "", 30, "verifprenom();");  //on fait notre formulaire en utilisant les fonctions de formulaire.php
    echo"<br><br>";
    form_label("Email");
    form_input_email("email", TRUE, "", "", 45, "verifemail();");
    echo"<br><br>";
    form_label("Login");
    form_input_text("login", TRUE, "", "", 30, "veriflogin();");
    echo"<br><br>";
    form_label("Mot de passe");
    form_input_mdp("password", TRUE, "", "", 30, "verifpassword();");
    echo "<span style=\"color: red;\" id=\"lvlsecure\"></span>";
    echo"<br><br>";
    form_label("Date de naissance (yyyy-mm-dd)");
    form_input_text("anniversaire", "TRUE", "", "", 30, "verifanniversaire();");
    
    echo "<span style=\"color: red;\" id=\"verifdate\"></span>";
    echo"<br><br>";
    form_label("Photo");
    form_file("pic", "image/*", "");
    echo"<br><br>";
    form_submit("Soumettre", "Soumettre", FALSE);
    form_reset("Reset", "Reinitialiser", FALSE, FALSE);
    form_fin();
    ?>

    <?php
    return ob_get_clean(); //la fonction retourne tout le html qui a été mis en tampon.
}

function action() {
    $chemin_destination = '../photo_profil/'; //chemin pour stocker les photos de profil
    move_uploaded_file($_FILES["pic"]['tmp_name'], $chemin_destination . $_POST["login"] . strrchr($_FILES['pic']['name'], '.')); //on met la photo dans le dossier
    global $bdd;
    $sql = 'INSERT INTO user (password,email,prenom,nom,birthday,photo,compte,note,username) VALUES(:password,:email,:prenom,:nom,:anniversaire,:photo,:compte,:note,:username)';
    $statement = $bdd->prepare($sql);
    $statement->execute(array(
        ":password" => md5($_POST["password"]),
        ":email" => $_POST["email"],
        ":prenom" => $_POST["prenom"],
        ":nom" => $_POST["nom"],
        ":anniversaire" => $_POST["anniversaire"],
        ":photo" => $chemin_destination . $_POST["login"] . strrchr($_FILES['pic']['name'], '.'),       //on inscrit l'utilisateur dans la base de données
        ":compte" => 0,
        ":note" => 0,
        ":username" => $_POST["login"],
    ));
    return "<div class='alert alert-success'>Inscription réussie.</div>"; //on retourne que l'inscritpion a été reussi
}

$title = "Inscription";
gabarit(); //la fonction gabarit va afficher la page du bon gabarit, donc ici visiteur avec comme contenu ce qui est dans la variable $contenu et comme titre la variable $title

