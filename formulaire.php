<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//touts les tests de présences d'attributs sont effectués par des ifs successifs qui affichent ou non ce qui correspond a l'attribut

function form_select_sql_attribut_ville($label, $name, $size, $bdd, $attribut, $table) {//permet de créer une liste contenant toutes les valeurs distinces de la requete SQL des villes
    $reponse = $bdd->query("SELECT DISTINCT " . $attribut . " FROM " . $table . " WHERE effectue = FALSE");
    //on crée un tableau contenant toutes les villes
    while ($donnees = $reponse->fetch()) {
        $tab[] = $donnees[$attribut];
    }
    form_select($name, FALSE, $size, $tab);//on utilise la fonction form_select pour crée la liste déroulante
}

function form_select_sql_attribut_user($label, $name, $size, $bdd, $attribut, $table) {//permet de créer une liste contenant toutes les valeurs distinces de la requete SQL des user
    $reponse = $bdd->query("SELECT DISTINCT " . $attribut . " FROM " . $table . " WHERE id!=" . $_SESSION["id"]);
    while ($donnees = $reponse->fetch()) {
        $tab[] = $donnees[$attribut];
    }
    form_select($name, FALSE, $size, $tab);
}

function form_debut($nom, $methode, $action) { //fonction de debut de formulaire, on transmet la methode de traitement, le fichie d'action et le nom du formualaire (ce dernier est utile pour le traitement JS)
    echo ("<form name='$nom' ");
    if ($methode == 'POST') {
        echo("method='POST' enctype='multipart/form-data'");
    }
    if ($methode == 'GET') {
        echo("method='GET' ");
    }
    echo " action = '$action'>";
}

function form_fin() {
    echo("</form>");
}

function form_select($name, $multiple, $size, $hash) { //multiple est un booléén, si il est fixé comme TRUE, il faut que $name soit transmis comme un tableau ('name[]')
    echo ("<select name = '$name'");
    if ($multiple)
        echo "multiple = '$multiple'";
    if ($size)
        echo "size = '$size'";
    echo ">";
    foreach ($hash as $elem_hash) {
        echo("<option>" . $elem_hash . "</option>");
    }
    echo("</select>");
}

function form_input_text($name, $required, $placeholder, $value, $size, $onChange) { //l'attribut placeholder et value sont incompatible, c'est l'attribut value qui prime dans cette fonction.
    echo("<input name = '$name' type = 'text'");

    if ($placeholder)
        echo "placeholder = '$placeholder'";
    if ($value)
        echo "value = '$value'";
    if ($size)
        echo "size = '$size'";
    if ($required)
        echo "required ";
    if ($onChange)
        echo "onChange = '$onChange'";
    echo(">");
}

function form_input_email($name, $required, $placeholder, $value, $size, $onChange) { //on transmet le nom, si il est requis, l'eventuel indice, l'eventuelle valeur, la taille du champs et la fonction pour l'évenement onChange
    echo("<input type = 'email' name = '$name'");
    if ($placeholder)
        echo "placeholder = '$placeholder'";
    if ($value)
        echo "value = '$value'";
    if ($size)
        echo "size = '$size'";
    if ($required)
        echo "required ";
    if ($onChange)
        echo "onChange = \"$onChange\"";
    echo(">");
}

function form_input_mdp($name, $required, $placeholder, $value, $size, $onChange) { //de meme que form_input_email
    echo("<input name='$name' type='password'");
    if ($placeholder)
        echo "placeholder = '$placeholder'";
    if ($value)
        echo "value = '$value'";
    if ($size)
        echo "size = '$size'";
    if ($required)
        echo "required = '$required' ";
    if ($onChange)
        echo " onChange ='$onChange'";
    echo(">");
}

function form_submit($name, $value, $disabled) { //value est l'attribut qui apparaitrait sur le bouton. de meme que form_input_email
    echo("<input name='$name' type='submit' class='btn btn-success'");
    if ($value)
        echo "value = '$value'";
    if ($disabled)
        echo " disabled";
    echo(">");
}

function form_reset($name, $value, $disabled, $hidden) {    
    echo("<input name='$name' type='reset' class='btn btn-primary'");
    if ($value)
        echo "value = '$value'";
    if ($hidden)
        echo "hidden";
    if ($disabled)
        echo " disabled";
    echo("><br>");
}

function form_radio($name, $values, $hiddens, $disableds) { //$values, $hiddens et $disableds doivent etre des tableaux avec des clés similaires si plusieurs boutons radios sont souhaités.
    echo"<div class='btn-group' data-toggle='buttons'>";
    foreach ($values as $key => $valeur) {
        echo" <label class='btn btn-default'>";
        echo("<input name='$name' type='radio'");
        echo "value = '$valeur'";
        if ($hiddens[$key])
            echo "hidden";
        if ($disableds[$key])
            echo "disabled";
        echo(" />" . $valeur);
        echo"</label>";
    }
    echo "</div></br>";
}

function form_checkbox($name, $values, $hiddens, $disableds, $checkeds) { //meme fonctionnement que pour le bouton radio 
    foreach ($values as $key => $valeur) {
        echo("<input name='$name' type='checkbox'");
        echo "value = '$valeur'";
        if ($hiddens[$key])
            echo "hidden";
        if ($disableds[$key])
            echo "disabled";
        if ($checkeds[$key])
            echo "checked";
        echo(">$valeur<br>");
    }
}

function form_file($name, $accept, $text) { //$text est une chaine de caractère pour preciser a l'utilisateur le type de fichier qu'il doit uploader (photo, pdf, ...)
    echo("<input type='file' name='$name' accept='$accept'>$text<br>"); //pour cette fonction, il est necessaire que le formulaire soit en methode POST et qu'il possede : enctype="multipart/form-data"
}

function form_textarea($name, $rows, $cols, $text, $required, $onChange) { //meme fonctionnement que form_input_text
    echo("$text<br>");
    echo("<textarea name ='" . $name . "' rows='$rows' cols='$cols' ");
    if ($required) {
        echo "required ";
    }
    if ($onChange)
        echo "onChange = \"$onChange\"";
    echo("></textarea>");
}

function affiche_tab($array) { //fonction de debug pour afficher les tableau associatifs (clé/valeurs) en php
    foreach ($array as $key => $value) {
        echo($key . "=>" . $value . "<br>");
    }
}

function form_label($text) { //fonction pour afficher le text avant les input dans les formulaires
    echo "<label>" . $text . "</label> : ";
}

function form_date($name, $required, $onChange) { //fonction de l'input date
    echo "<input name='$name' type='date'";

    if ($required) {
        echo "required ";
    }
    if ($onChange)
        echo " onChange ='$onChange'";
    echo ">";
}

function form_hidden($name, $value) { //fonction de l'input hidden
    echo "<input type='hidden' name='$name' value='$value'>";
}

?>