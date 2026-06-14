<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/../Model/utilisateur.php";
$pageActuelle = basename($_SERVER['PHP_SELF']);

$actualUser = null;
$isAdmin = false;
if (!empty($_SESSION['user_email'])) {
    $actualUser = Utilisateur::findByEmail($_SESSION['user_email']);
    if ($actualUser) {
        $admins = Utilisateur::getAdmins();
        foreach ($admins as $admin) {
            if ($admin->getEmail() === $actualUser->getEmail()) {
                $isAdmin = true;
                break;
            }
        }
    }
}
?>
<header>
        <section id="Titre">
            <img src="assets/img/ec8ea05d379666617c19b9cb79865d70.jpg" alt="Logo de l'événement">
            <h1>Journée tournoi LAN jeux-vidéo</h1>
            <div class="headerconnect">
                <?php if (!empty($_SESSION['user_email']) && $actualUser) : ?>
                    <?php if ($isAdmin) : ?>
                        <a class="SeConnecter" href="organisateur.php"><?php echo $actualUser->getNom() . " " . $actualUser->getPrenom(); ?></a>
                    <?php else: ?>
                        <a class="SeConnecter" href="tableauDeBord.php">Tableau de bord</a>
                    <?php endif; ?>
                    <a class="Deconnexion" href="deconnexion.php">Déconnexion</a>
                <?php else: ?>
                    <a class="SeConnecter" href="connexion.php">Se connecter</a>
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