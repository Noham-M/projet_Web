
<?php
require_once "app/Model/prestation.php";
require_once "app/Model/Scene.php";
require_once "app/Model/Heure.php";

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'deprogrammer') {
    $prestationId = filter_var($_POST['prestation_id'] ?? null, FILTER_VALIDATE_INT);
    if ($prestationId) {
        if (Prestation::clearHeureById($prestationId)) {
            $message = 'Prestation déprogrammée avec succès.';
        } else {
            $message = 'Impossible de déprogrammer cette prestation.';
        }
    } else {
        $message = 'Identifiant de prestation invalide.';
    }
}

$scenes = Scene::findAll();
$heures = Heure::findAll();
$prestations = Prestation::findAll(null, null, true);

$schedule = [];
foreach ($prestations as $prestation) {
    $heureId = $prestation->getIdHeure();
    $sceneId = $prestation->getIdScene();
    if ($heureId !== null && $sceneId !== null) {
        $schedule[$heureId][$sceneId] = $prestation;
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
<?php require_once "app/view/header.php";?>
<main>
    <h2>Page de l'organisateur</h2>
    <p>Bienvenue sur la page de l'organisateur ! vous trouverez ici les informations et les outils nécessaires pour gérer le tournoi.</p>
    <a href="gererArtistes.php" class="orgaLien">Cliquez ici pour gérer les joueurs</a>

    <?php if ($message) : ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <table>
        <caption>Programme des prestations</caption>
        <thead>
            <tr>
                <th>Heure</th>
                <?php foreach ($scenes as $scene) : ?>
                    <th><?php echo htmlspecialchars($scene->getNom()); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($heures as $heure) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($heure->toString()); ?></td>
                    <?php foreach ($scenes as $scene) : ?>
                        <?php
                        $presta = $schedule[$heure->getIdHeure()][$scene->getIdScene()] ?? null;
                        if ($presta) {
                            $joueur = $presta->findJoueur($presta->getIdJoueur());
                            $artist = $joueur ? $joueur->getPseudo() : 'N/A';
                        ?>
                            <td>
                                <div class="programme-cell">
                                    <p>Artiste : <?php echo htmlspecialchars($artist); ?></p>
                                    <p>Titre : <?php echo htmlspecialchars($presta->getTitre()); ?></p>
                                    <form action="organisateur.php" method="post" onsubmit="return confirm('Déprogrammer cette prestation ?');">
                                        <input type="hidden" name="prestation_id" value="<?php echo htmlspecialchars($presta->getIdPrestation()); ?>">
                                        <button type="submit" name="action" value="deprogrammer" class="delete-btn">Déprogrammer</button>
                                    </form>
                                </div>
                            </td>
                        <?php } else { ?>
                            <td>Libre</td>
                        <?php } ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php require_once "app/view/footer.php";?>
</body>
</html>