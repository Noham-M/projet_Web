<?php
include_once "app/Model/prestation.php";
include_once "app/Model/Heure.php";
include_once "app/Model/Joueur.php";

$selectedJoueur = isset($_GET['Joueur']) && $_GET['Joueur'] !== '' ? intval($_GET['Joueur']) : null;
$selectedScene = isset($_GET['Scene']) && $_GET['Scene'] !== '' ? intval($_GET['Scene']) : null;
$programmed = isset($_GET['prograpresta']);

$exemplePresta = [];
$players = [];
$scenes = [];
$erreur = '';

try {
    $exemplePresta = Prestation::findAll($selectedJoueur, $selectedScene, $programmed);
    $players = Joueur::findAll();
    $scenes = Scene::findAll();
} catch(PDOException $e) {
    $erreur = "Erreur : " . $e->getMessage();
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
    <?php
    require_once "app/view/header.php";
    ?>

    <main>

        <h2>Liste des prestations</h2>
        <form action="#" method="GET" class="filter">
            <fieldset>
                <legend>Filtrer les activités</legend>
                <div class="filterGroup">
                    <label for="Joueur">Choisissez un joueur</label>
                    <select name="Joueur" id="Joueur">
                        <option value="">Tous les joueurs</option>
                        <?php foreach ($players as $player) { ?>
                            <option value="<?php echo $player->getIdJoueur(); ?>" <?php echo $player->getIdJoueur() === $selectedJoueur ? 'selected' : ''; ?>>
                                <?php echo $player->getPseudo(); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="Scene">Choisissez une scène</label>
                    <select name="Scene" id="Scene">
                        <option value="">Toutes les scènes</option>
                        <?php foreach ($scenes as $scene) { ?>
                            <option value="<?php echo $scene->getIdScene(); ?>" <?php echo $scene->getIdScene() === $selectedScene ? 'selected' : ''; ?>>
                                <?php echo $scene->getNom(); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="prograPresta">Afficher prestation programmé</label>
                    <input type="checkbox" id="prograPresta" name="prograpresta" value="1" <?php echo $programmed ? 'checked' : ''; ?>> 
                </div>

                <button type="submit">Rechercher</button>
            </fieldset>
        </form>

        <div class="ListeVignette">
            <?php
            if (!empty($erreur)) {
                echo "<p class='error-message'>" . $erreur . "</p>";
            } elseif (empty($exemplePresta)) {
                echo "<p class='empty-message'>Aucun artiste ne correspond à votre recherche.</p>";
            } else {
                foreach ($exemplePresta as $value) {
                    $Player = $value->findJoueur($value->getIdJoueur());
                    $Heure = $value->findHeure($value->getIdHeure());
                    $Scene = $value->findScene($value->getIdScene());
                    $playerName = $Player ? $Player->getPseudo() : 'Joueur inconnu';
                    echo "<a href='prestationEx.php?id=" . $value->getIdPrestation() . "' class='vignette'>
                    <img src='assets/img/". $value->getImage() . "'alt='photo de la prestation'>
                    <h3>" . $value->getTitre() . "</h3>
                    <p><strong>Joueur : </strong>" . $playerName . "</p>
                    <p><strong>Heure : </strong>" . ($Heure ? $Heure->toString() : 'Inconnue') . "</p>
                    <p><strong>Scène : </strong>" . ($Scene ? $Scene->getNom() : 'Inconnue') . "</p>
                    <p>" . $value->getDescription() . "</p>
                    </a>";
                }
            }
            ?>
        </div>
    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>