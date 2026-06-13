<?php
session_start();
require_once __DIR__ . "/../Model/utilisateur.php";
$pageActuelle = basename($_SERVER['PHP_SELF']);

$actualUser = null;
if (!empty($_SESSION['user_email'])) {
    $actualUser = Utilisateur::findByEmail($_SESSION['user_email']);
}
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
            <div class="headerconnect">
                <a class="SeConnecter" href="connexion.php"><?php echo isset($_SESSION['user_email']) && $actualUser ? $actualUser->getNom() . ' ' . $actualUser->getPrenom() : 'Se connecter'; ?></a>
                <?php if (!empty($_SESSION['user_email']) && $actualUser) : ?>
                    <a class="Deconnexion" href="deconnexion.php">Déconnexion</a>
                <?php endif; ?>
            </div>
        </section>

       <nav>
        <ul>
            <li>
                <a class="<?php echo ($pageActuelle == 'index.php') ? 'current' : '' ?>" href="index.php">Accueil</a>
            </li>
            <li>
                <a class="<?php echo ($pageActuelle == 'ListeDesJoueur.php') ? 'current' : '' ?>" href="ListeDesJoueur.php">Liste des joueurs</a>
            </li>
            <li>
                <a class="<?php echo ($pageActuelle == 'listePrestation.php') ? 'current' : '' ?>" href="listePrestation.php">Prestations</a>
            </li>
            <li>
                <a class="<?php echo ($pageActuelle == 'contact.php') ? 'current' : '' ?>" href="contact.php">Contact</a>
            </li>
        </ul>
    </nav>
</header>