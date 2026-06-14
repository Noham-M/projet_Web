<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/app/Model/utilisateur.php";
require_once __DIR__ . "/app/Model/Joueur.php";

$error = [];
$message = null;
$isAdmin = false;
$admins = Utilisateur::getAdmins();
$sessionUser = null;
if (!empty($_SESSION['user_email'])) {
    $sessionUser = Utilisateur::findByEmail($_SESSION['user_email']);
    foreach ($admins as $admin) {
        if ($sessionUser && $admin->getEmail() === $sessionUser->getEmail()) {
            $isAdmin = true;
            break;
        }
    }
}

if (!$isAdmin) {
    header('Location: connexion.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete_joueur') {
        $joueurId = filter_var($_POST['joueur_id'] ?? null, FILTER_VALIDATE_INT);
        if ($joueurId) {
            if (Joueur::deleteWithPrestations($joueurId)) {
                $message = 'Joueur et ses prestations supprimés.';
            } else {
                $error['db'] = 'Impossible de supprimer ce joueur.';
            }
        } else {
            $error['id'] = 'Identifiant de joueur invalide.';
        }
    }
}

$joueurs = Joueur::findAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les artistes</title>
    <link rel="stylesheet" href="assets/css/Style.css">
</head>
<body>
    <?php require_once "app/view/header.php"; ?>
    <main>
        <h2>Gérer les artistes</h2>
        <?php if ($message) : ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error)) : ?>
            <p><?php echo htmlspecialchars(implode(' ', $error)); ?></p>
        <?php endif; ?>
        <div class="ListeVignette">
            <?php foreach ($joueurs as $joueur) : ?>
                <div class="ModifPrestaVignette">
                    <img src="assets/img/<?php echo htmlspecialchars($joueur->getImage()); ?>" alt="Photo de <?php echo htmlspecialchars($joueur->getPseudo()); ?>">
                    <h3><?php echo htmlspecialchars($joueur->getPseudo()); ?></h3>
                    <p><?php echo htmlspecialchars($joueur->getDescription()); ?></p>
                    <div class="prestationActions">
                        <a href="adminModifJoueur.php?id=<?php echo htmlspecialchars($joueur->getIdJoueur()); ?>" class="Deconnexion">Gerer</a>
                        <form action="gererArtistes.php" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce joueur et toutes ses prestations ?');">
                            <input type="hidden" name="joueur_id" value="<?php echo htmlspecialchars($joueur->getIdJoueur()); ?>">
                            <button type="submit" name="action" value="delete_joueur" class="delete-btn">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>
