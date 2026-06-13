<?php
require_once "app/Model/prestation.php";
require_once "app/Model/Scene.php";
require_once "app/Model/Heure.php";

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
    <?php require_once "app/view/header.php"; ?>
    <main>
        <article id="presentationEvenement">
            <div id="h2EtPresentation">
                <h2>Brève description de l'événement</h2>
                <p>Chaque année, un nombre incalculable de jeunes talents cherchent à se faire connaître sur la scène
                    e-sport.
                    Ce tournoi leur sert donc de tremplin pour leur future carrière.
                    N'hésitez pas à venir soutenir les joueurs présents sur place.
                </p>
            </div>
            <img src="assets/img/OIP.png" alt="Aperçu du tournoi e-sport">
        </article>
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
                            $presta = $schedule[$heure->getIdHeure()][$scene->getidScene()] ?? null;
                            if ($presta) {
                                $joueur = $presta->findJoueur($presta->getIdJoueur());
                                $artist = $joueur ? $joueur->getPseudo() : 'N/A';
                                echo "<td><a href='prestationEx.php?id=" . $presta->getIdPrestation() . "'>Artiste : " . htmlspecialchars($artist) . " <br>Titre prestation : " . htmlspecialchars($presta->getTitre()) . "</a></td>";
                            } else {
                                echo "<td>Artiste : N/A <br>Titre prestation : N/A</td>";
                            }
                            ?>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>

</html>
