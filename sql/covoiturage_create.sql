
-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `password` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `prenom` VARCHAR(45) NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `birthday` VARCHAR(45) NOT NULL,
  `photo` LONGTEXT NOT NULL,
  `compte` DECIMAL(10) NOT NULL,
  `note` DECIMAL(10) NULL,
  `username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `pilote`
-- -----------------------------------------------------
CREATE TABLE `pilote` (
  `pilote_user_id` INT NOT NULL,
  `voiture_marque` VARCHAR(45) NOT NULL,
  `voiture_annee` INT NOT NULL,
  `voiture_modele` VARCHAR(45) NOT NULL,
  `voiture_couleur` VARCHAR(45) NOT NULL,
  `photo` LONGTEXT NULL,
  PRIMARY KEY (`pilote_user_id`),
  CONSTRAINT `fk_pilote_user`
    FOREIGN KEY (`pilote_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `ville_depart`
-- -----------------------------------------------------
CREATE TABLE `ville_depart` (
  `Ville` VARCHAR(30) NOT NULL,
  `Region` VARCHAR(45) NULL,
  PRIMARY KEY (`Ville`));


-- -----------------------------------------------------
-- Table `ville_arrivee`
-- -----------------------------------------------------
CREATE TABLE `ville_arrivee` (
  `Ville` VARCHAR(30) NOT NULL,
  `Region` VARCHAR(45) NULL,
  PRIMARY KEY (`Ville`));


-- -----------------------------------------------------
-- Table `trajet`
-- -----------------------------------------------------
CREATE TABLE `trajet` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `lieu_depart` VARCHAR(45) NOT NULL,
  `lieu_arrivee` VARCHAR(45) NOT NULL,
  `places_max` INT UNSIGNED NOT NULL,
  `places_prises` INT UNSIGNED NOT NULL,
  `date` VARCHAR(45) NOT NULL,
  `pilote_user_id` INT NOT NULL,
  `heure_dep` INT NOT NULL,
  `prix` DECIMAL(5) NOT NULL,
  `effectue` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`, `pilote_user_id`),
  CONSTRAINT `fk_trajet_pilote1`
    FOREIGN KEY (`pilote_user_id`)
    REFERENCES `pilote` (`pilote_user_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lieu_dep`
    FOREIGN KEY (`lieu_depart`)
    REFERENCES `ville_depart` (`Ville`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lieu_arr`
    FOREIGN KEY (`lieu_arrivee`)
    REFERENCES `ville_arrivee` (`Ville`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `trajet_passager`
-- -----------------------------------------------------
CREATE TABLE `trajet_passager` (
  `trajet_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `nb_places` INT NOT NULL,
  PRIMARY KEY (`trajet_id`, `user_id`),
  CONSTRAINT `fk_trajet_has_user_trajet1`
    FOREIGN KEY (`trajet_id`)
    REFERENCES `trajet` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trajet_has_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `evaluation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `evaluation` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `evaluateur_user_id` INT NOT NULL,
  `evalue_user_id` INT NOT NULL,
  `evaluation` INT NOT NULL,
  `trajet_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_evaluateur`
    FOREIGN KEY (`evaluateur_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evalue`
    FOREIGN KEY (`evalue_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trajet_id`
    FOREIGN KEY (`trajet_id`)
    REFERENCES `trajet` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `transaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `credit_user_id` INT NOT NULL,
  `debit_user_id` INT NOT NULL,
  `somme` DECIMAL(10) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_credit`
    FOREIGN KEY (`credit_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_debit`
    FOREIGN KEY (`debit_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `messagerie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `messagerie` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `expediteur_user_id` INT NOT NULL,
  `destinataire_user_id` INT NOT NULL,
  `titre` VARCHAR(45) NOT NULL,
  `date` DATETIME NOT NULL,
  `message` LONGTEXT NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_expediteur`
    FOREIGN KEY (`expediteur_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_destinataire`
    FOREIGN KEY (`destinataire_user_id`)
    REFERENCES `user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

