function verifnom() { //on teste si le nom est en majuscule et si il n'est pas vide
    if (document.form.nom.value == "") {
        alert("Veuillez saisir votre nom");
    }
    if (document.form.nom.value.toUpperCase() != document.form.nom.value) { //si le nom n'est pas en majuscule, on le met en majuscule automatiquement
        document.form.nom.value = document.form.nom.value.toUpperCase();
    }
}

function verifprenom() { //meme principe que pour verifnom()
    if (document.form.prenom.value == "") {
        alert("Veuillez saisir votre prenom");
    }
    var str_tab = new Array();
    for (i = 0; i < document.form.prenom.value.length; i++) { //boucle pour parcourir les caractères de la chaine un a un
        if (i == 0) {
            str_tab[i] = document.form.prenom.value.substr(i, 1).toUpperCase(); //si c'est le premier caractère, on le passe en majuscule
        }
        else {
            str_tab[i] = document.form.prenom.value.substr(i, 1).toLowerCase(); //si s'en est un autre, on le passe en minuscule
        }
    }
    document.form.prenom.value = str_tab.join(''); //on modifie la valeur du champs prenom
}

function verifemail() {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(document.form.email.value)) //on verifie que l'email à un format valide (syntaxe trouvée sur internet)
    {
        return (true)
    }
    alert("Veuillez saisir un email valide")
}

function veriflogin() { //meme principe que verifnom()
    if (document.form.login.value == "") {
        alert("Veuillez saisir un login");
    }
}

function verifpassword() {
    var solide = new Array(//table de correspondance mot/niveau de solidite
            "très faible",
            "faible",
            "correcte",
            "moyenne",
            "forte"
            );
    var score = 0;
    if (document.form.password.value.length >= 5) { //si la chaine est superieur a 5 caractères
        score = score + 1;
    }
    var str = document.form.password.value;
    if ((str.match(/[a-z]/))) { //si la chaine contient une minuscule
        score = score + 1;
    }
    if ((str.match(/[A-Z]/))) { //si la chaine contient une majuscule
        score = score + 1;
    }
    if (str.match(/\d+/)) { //si la chaince contient un chiffre
        score = score + 1;
    }
    if (str.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) { //si la chaine contient un caractère spécial
        score = score + 1;
    }
    if (score === 0) { //affichage de la solidité du mot de passe dans la balise nommée "lvlsecure"
        document.getElementById("lvlsecure").innerHTML = "La sécurité de votre mot de passe n'est pas suffisante";
    } else if (score === 1) {
        document.getElementById("lvlsecure").innerHTML = "La sécurité de votre mot de passe est " + solide[0];
    } else if (score === 2) {
        document.getElementById("lvlsecure").innerHTML = "La sécurité de votre mot de passe est " + solide[1];
    } else if (score === 3) {
        document.getElementById("lvlsecure").innerHTML = "La sécurité de votre mot de passe est " + solide[2];
    } else if (score === 4) {
        document.getElementById("lvlsecure").innerHTML = "La sécurité de votre mot de passe est " + solide[3];
    } else if (score === 5) {
        document.getElementById("lvlsecure").innerHTML = "la sécurité de votre mot de passe est " + solide[4];
    }
}

function verifanniversaire() { //on verifie que la date est sous la bonne forme, pour correspondre aux exigences de la base de donnee
    var str = document.form.anniversaire.value;
    var res = str.split("-");
    if ((res[0].length != 4) || (res[1].length != 2) || (res[2].length != 2)) {
        document.getElementById("verifdate").innerHTML = "format de la date non valide";
    } else {
        document.getElementById("verifdate").innerHTML = "";
    }
}

function verifmarque() { //meme principe que verifnom()
    if (document.form.marque.value == "") {
        alert("Veuillez saisir une marque");
    }
}

function verifmodele() { //meme principe que verifnom()
    if (document.form.modele.value == "") {
        alert("Veuillez saisir un modèle");
    }
}

function verifannee() { //meme principe que verifnom()
    var str = document.form.annee.value;
    if (document.form.annee.value == "") {
        alert("Veuillez saisir une annee");
    }
    if ((str.match(/[a-z]/)) || (str.match(/[A-Z]/)) || str.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) {//on verifie que la date ne comporte pas de caractère spéciaux ou de minuscules/majuscules
        alert("Veuillez rentrez une date uniquement composée de chiffres");
    }
    if ((str.length < 4) || (str.length > 4)) { //on verifie que l'annee a bien une longueur de 4 caractères
        alert("Veuillez saisir une date à 4 chiffres");
    }
}

function verifcouleur() { //meme principe que verifnom()
    if (document.form.couleur.value == "") {
        alert("Veuillez saisir une couleur");
    }
}

function verifdate() {  //meme principe que verifanniversaire()
    var str = document.form.date.value;
    var res = str.split("-");
    if ((res[0].length != 4) || (res[1].length != 2) || (res[2].length != 2)) {
        document.getElementById("verifdate").innerHTML = "format de la date non valide";
    } else {
        document.getElementById("verifdate").innerHTML = "";
    }
}