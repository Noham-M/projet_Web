<?php
session_start();
require_once "app/Model/utilisateur.php";
$Joueur = null;
$utilisateur = null;
if (!empty($_SESSION['user_email'])) {
    $utilisateur = Utilisateur::findByEmail($_SESSION['user_email']);
    if ($utilisateur) {
        $Joueur = $utilisateur->getJoueur();
    }
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
    <?php require_once "app/view/header.php"; ?>
    <main>
        <h2>Tableau de bord</h2>
        <p>Bienvenue sur votre tableau de bord ! vous trouverez ici les informations et les outils nécessaires pour gérer votre compte.</p>

        <article id='presentation' class='exemple'>
            <img src='assets/img/question-svgrepo-com.svg' alt='Photo'>
            <div id='biographie'>
                <h3>Salut <?php echo $Joueur->getPseudo(); ?> !</h3>
                <a href='modifierJoueur.php' class='modifProfil'>Modifier mon profil</a>
            </div>
        </article>

    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>

</html>