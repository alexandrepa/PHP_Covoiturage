    <?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    
 //les fonctions test permettent de s'assurer que seul les utilisateurs habilités peuvent acceder à certaines pages, et dans le cas contraires sont redirigés vers la page d'accueil   
  //pas besoin de commenter, les tests parlent d'eux-memes  
    
function test_visiteur(){
    session_start();
if (isset($_SESSION['login'])) {    
    
	header ('Location: ../index.php');
	exit();
}
}
function test_passager(){
    session_start();

if(!isset($_SESSION['login'])){
    header ('Location: ../index.php');
	exit(); 
}
else if(isset($_SESSION['pilote'])){
    if($_SESSION['pilote']){
       header ('Location: ../index.php');
	exit(); 
    }
}

}

function test_membre(){
    session_start();
if (!isset($_SESSION['login'])) {
    
	header ('Location: ../index.php');
	exit();
}
    
}

function test_membre_admin(){//on test si c'est un membre ou un admin
    session_start();
if (!isset($_SESSION['login'])&&!isset($_SESSION['admin'])) {
    
	header ('Location: ../index.php');
	exit();
}
    
}

function test_pilote(){
     session_start();

if(!isset($_SESSION['login'])){
    header ('Location: ../index.php');
	exit(); 
}
else if(isset($_SESSION['pilote'])){
    if(!$_SESSION['pilote']){
       header ('Location: ../index.php');
	exit(); 
    }
} 
}

function test_admin(){
    session_start();
if (!isset($_SESSION['admin'])) {
    
	header ('Location: ../index.php');
	exit();
}
else {
    if($_SESSION['admin']==FALSE){
       header ('Location: ../index.php');
	exit(); 
    }
}
    
}

//la fonction gabarit permet de choisir qu'elle gabarit html la page doit afficher, avec deux variables $contenu qui est le contenu de la page et $title
function gabarit(){
    global $contenu, $title;
    if (session_status() == PHP_SESSION_NONE) {//on initialise la session que si cela n'a pas deja été fait.
    session_start();
}
if (!isset($_SESSION['login'])) {
    
	require '../gabarit/gabarit_visiteur.php';
}
else if(isset($_SESSION['login'])){
 if(isset($_SESSION['pilote'])){
    if($_SESSION['pilote']){
       require '../gabarit/gabarit_pilote.php';
    }
    else{
        require '../gabarit/gabarit_passager.php';
    }
}
else{
   require '../gabarit/gabarit_passager.php'; 
}

}
}