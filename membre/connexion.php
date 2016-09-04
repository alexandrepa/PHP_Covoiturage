<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require'../BDD.php';
$bdd = getBdd();
require'../formulaire.php';         //voir inscription.php pour commentaires
require '../droits.php';
test_visiteur();

if (isset($_POST['Connexion']) && $_POST['Connexion'] == 'Connexion') {
    // on teste l'existence de nos variables. On teste également si elles ne sont pas vides
    if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['password']) && !empty($_POST['password']))) {
        if($_POST['login']=="admin" && $_POST['password']=="admin"){
         $_SESSION["admin"]= TRUE;              //si l'utilisateur rentre les identifiants admin alors on le redirige vers la page admin
         header ('Location: ../admin/librairies_admin.php');
	exit();
        }
        else {
        $sql = 'SELECT count(*) FROM user WHERE username="' . $_POST['login'] . '"AND password="' . md5($_POST['password']) . '"';
        $reponse = $bdd->query($sql);
        $donnee = $reponse->fetch();   // on regarde si il y a une correspondance entre le login et le password dans la BDD
        if ($donnee[0] == 1) {
            $contenu = action(); //si oui alors on appelle la fonction action qui va initialiser les variables de session
        } else if ($donnee[0] == 0) {

            $contenu = "<div class='alert alert-error'>Login ou mot de passe erroné</div>";
        } else {
            $contenu = "<div class='alert alert-error'>Problème dans la base de données : plusieurs membres ont les mêmes identifiants de connexion.</div>";
        }
    }
    } else {
        $contenu = "<div class='alert alert-error'>Certains champs ne sont pas remplis</div>";
    }
} else {
    $contenu = formulaire(); //si l'utilisateur vient d'arriver on affiche le formulaire de connexion
}

function formulaire() {
    ob_start(); //on met en tampon le html
    ?>
    <h1>Connexion</h1>
    <?php
    form_debut("form", "POST", "connexion.php");
    form_label("Login");
    form_input_text("login", TRUE, "", "", 30, "");
    echo "<br><br>";
    form_label("Mot de passe");
    form_input_mdp("password", TRUE, "", "", 30, "");
    echo "<br><br>";
    form_submit("Connexion", "Connexion", FALSE);
    form_reset("reset", "", FALSE, TRUE);
    form_fin()
    ?>

    <?php
    return ob_get_clean();
}

function action() {
    global $bdd;
    $sql = 'SELECT * FROM user WHERE username="' . $_POST['login'] . '"';
    $reponse = $bdd->query($sql);
    $donnees = $reponse->fetch(); //on recupere dans la base les infos de l'utilisateur
    
    $_SESSION["admin"]=FALSE; //ce n'est pas un admin
    $_SESSION['id'] = $donnees['id'];
    $_SESSION['login'] = $donnees['username'];
    $_SESSION['prenom'] = $donnees['prenom'];        //on met toutes les infos de l'utilisateur dans la session
    $_SESSION['nom'] = $donnees['nom'];
    $_SESSION['email'] = $donnees['email'];
    $_SESSION['birthday'] = $donnees['birthday'];
    $_SESSION['photo'] = $donnees['photo'];
    $_SESSION['compte'] = $donnees['compte'];
    $_SESSION['note'] = $donnees['note'];

    $sql = 'SELECT count(*) FROM pilote WHERE pilote_user_id="' . $_SESSION['id'] . '"';
    $reponse = $bdd->query($sql);
    $test_user_pilote = $reponse->fetch(); 
    if ($test_user_pilote[0] == 0) {              //on regarde si c'est un pilote dans la base de donnée, et on met le resultat dans la variable de session pilote
        $_SESSION["pilote"] = FALSE;
    } else {

        $_SESSION["pilote"] = TRUE;
    }

    header('Location: ../index.php'); //on redirige vers l'index
    exit();
    return "Connexion Reussie";
}

$title = "Connexion";
gabarit();
