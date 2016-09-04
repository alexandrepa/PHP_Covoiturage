<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require "../droits.php";
test_membre_admin(); //on teste si c'est un membre ou admin sinon on redirige vers l'index



session_unset();
session_destroy();            //on detruit la session
header('Location: ../index.php'); //on redirige vers l'index
exit();
