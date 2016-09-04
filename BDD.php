<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//fonction qui permet de crée un objet PDO pour manipuler dans la BDD
function getBdd() {
  $bdd = new PDO('mysql:host=localhost;dbname=covoiturage;charset=utf8', 'root', '');
  return $bdd;
}