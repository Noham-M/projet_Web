<?php
require_once "app/Model/utilisateur.php";
require_once "app/Model/prestation.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
try {
    if (!empty($_SESSION['user_email'])) {
        $utilisateur = Utilisateur::findByEmail($_SESSION['user_email']);
        if ($utilisateur) {
            $Joueur = $utilisateur->getJoueur();
            if ($Joueur) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete_prestation_id'])) {
                    $deleteId = filter_var($_POST['delete_prestation_id'], FILTER_VALIDATE_INT);
                    if ($deleteId && $deleteId > 0) {
                        $prestationToDelete = Prestation::findById($deleteId);
                        if ($prestationToDelete && $prestationToDelete->getIdJoueur() === $Joueur->getIdJoueur()) {
                            if (Prestation::deleteById($deleteId)) {
                                header('Location: modifierPrestation.php');
                                exit;
                            }
                            $error = 'Impossible de supprimer la prestation.';
                        } else {
                            $error = 'Prestation introuvable ou accès refusé.';
                        }
                    } else {
                        $error = 'Identifiant de prestation invalide.';
                    }
                }
                $prestations = $Joueur->getPrestations();
            }
        }
    }
} catch (Exception $e) {
    $error = "Erreur de base de données.";
    $utilisateur = null;
    $Joueur = null;
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
        <h2>Modifier une prestation</h2>
        <?php if (isset($error)) : ?>
            <span><?php echo htmlspecialchars($error); ?></span>
        <?php elseif (isset($prestations) && !empty($prestations)) : ?>
            <div class="ListeVignette">
            <?php foreach ($prestations as $prestation) : ?>
            <?php $heure = $prestation->findHeure($prestation->getIdHeure());
                  $scene = $prestation->findScene($prestation->getidScene());?>
            <div class="ModifPrestaVignette">
                <img src='assets/img/<?php echo htmlspecialchars($prestation->getImage()); ?>' alt='image de manette'>
                <h3><?php echo htmlspecialchars($prestation->getTitre()); ?></h3>
                <p><?php echo "heure : " . htmlspecialchars($heure->toString()) . " scene: " . htmlspecialchars($scene->getNom()); ?></p>
                <div class="prestationActions">
                    <a href="updatePrestation.php?id=<?php echo htmlspecialchars($prestation->getIdPrestation()); ?>" class="Deconnexion">Modifier</a>
                    <form action="modifierPrestation.php" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer cette prestation ?');">
                        <input type="hidden" name="delete_prestation_id" value="<?php echo htmlspecialchars($prestation->getIdPrestation()); ?>">
                        <button type="submit" class="delete-btn">Supprimer</button>
                    </form>
                </div>
            </div>   
            <?php endforeach; ?>
            </div>
            <?php endif; ?> 
            <a href="ajouterPrestation.php" class="Deconnexion" >Ajouter une prestation</a> 
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>