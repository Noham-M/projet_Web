<?php
$pageActuelle = basename($_SERVER['PHP_SELF']); 
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journée Tournoi LAN jeux-vidéo</title>
    <link rel="stylesheet" href="assets/css/Style.css">
</head>

<body>
    <header>
        <section id="Titre">
            <img src="assets/img/question-svgrepo-com.svg" alt="Logo de l'événement">
            <h1>Journée tournoi LAN jeux-vidéo</h1>
            <a class="SeConnecter" href="connexion.php">Se connecter ?</a>
        </section>

       <nav>
        <ul>
            <li>
                <a class="<?= ($pageActuelle == 'index.php') ? 'current' : '' ?>" href="index.php">Accueil</a>
            </li>
            <li>
                <a class="<?= ($pageActuelle == 'ListeDesJoueur.php') ? 'current' : '' ?>" href="ListeDesJoueur.php">Liste des joueurs</a>
            </li>
            <li>
                <a class="<?= ($pageActuelle == 'listePrestation.php') ? 'current' : '' ?>" href="listePrestation.php">Prestations</a>
            </li>
            <li>
                <a class="<?= ($pageActuelle == 'contact.php') ? 'current' : '' ?>" href="contact.php">Contact</a>
            </li>
        </ul>
    </nav>
</header>