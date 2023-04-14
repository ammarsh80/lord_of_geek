<?php

include 'App/modele/M_Session.php';
include 'App/modele/M_Commande.php';

/**
 * Controleur pour les commandes
 * @author Loic LOG
 */
switch ($action) {
    case 'passerCommande':
        $n = nbJeuxDuPanier();
        if ($n > 0) {
            $nom = '';
            $prenom = '';
            $rue = '';
            $ville = '';
            $cp = '';
            $email = '';
            $InfoUtilisateurPourCommander=(M_Commande::infoUtilisateurPourCommander($_SESSION['id']));

        } else {
            afficheMessage("Panier vide !!");
            $uc = '';
        }
        break;
    case 'confirmerCommande':
        $nom = filter_input(INPUT_POST, 'nom');
        $prenom = filter_input(INPUT_POST, 'prenom');
        $rue = filter_input(INPUT_POST, 'rue');
        $ville = filter_input(INPUT_POST, 'ville');
        $cp = filter_input(INPUT_POST, 'cp');
        $mail = filter_input(INPUT_POST, 'email');
        $errors = M_Commande::estValide($nom, $prenom, $rue, $ville, $cp, $mail);
        if (count($errors) > 0) {
            // Si une erreur, on recommence
            afficheErreurs($errors);
        } else {
            
            $id_ville = M_Commande::trouveOuCreerVille($ville, $cp);
            
            $idclient = M_Commande::trouveOuCreerClient($nom, $prenom, $rue, $mail, $id_ville);
            $listJeux = getLesIdJeuxDuPanier();
            M_Commande::creerCommande($idclient, $id_ville, $listJeux);
     
            supprimerPanier();
            afficheMessage("Commande enregistrée");
            $uc = '';
        }
        break;
}

