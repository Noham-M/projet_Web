<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "app/Model/utilisateur.php";
$Joueur = null;
$utilisateur = null;
$error = '';
try {
    if (!empty($_SESSION['user_email'])) {
        $utilisateur = Utilisateur::findByEmail($_SESSION['user_email']);
        if ($utilisateur) {
            $Joueur = $utilisateur->getJoueur();
            if($Joueur) {
                $prestations = $Joueur->getPrestations();
            }
        }
    }
} catch (Exception $e) {
    $error = "Erreur de base de données.";
    $utilisateur = null;
    $Joueur = null;
}
$prestations = $Joueur ? $Joueur->getPrestations() : [];
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
                <?php if ($Joueur) : ?>
                    <h3>Salut <?php echo htmlspecialchars($Joueur->getPseudo()); ?> !</h3>
                <?php else : ?>
                    <h3>Profil indisponible</h3>
                <?php endif; ?>
                <?php if ($error) : ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
                <h4>Vos prestations :</h4>
                <?php if (!empty($prestations)) : ?>
                    <ul>
                        <?php foreach ($prestations as $prestation) : ?>
                            <?php $heure = $prestation->findHeure($prestation->getIdHeure());
                            $scene = $prestation->findScene($prestation->getidScene());?>
                            <li><?php echo htmlspecialchars($prestation->getTitre()) . " (" . htmlspecialchars($heure->toString()) . ") " . "scene: " . htmlspecialchars($scene->getNom()) . ")"; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>Aucune prestation trouvée.</p>
                <?php endif; ?>
                <a href='modifierPrestation.php' class='modifProfil'>Modifier mes prestations</a>
                <h4>Votre profil :</h4>
                <a href='modifierJoueur.php' class='modifProfil'>Modifier mon profil</a>
            </div>
        </article>



    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>

</html>