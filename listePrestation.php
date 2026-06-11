<?php
include_once "app/Model/prestation.php";
include_once "app/Model/Heure.php";

$selectedScene = isset($_GET['Joueur']) && $_GET['Joueur'] !== '' ? intval($_GET['Joueur']) : null;
$selectedHeure = isset($_GET['Creneau']) && $_GET['Creneau'] !== '' ? intval($_GET['Creneau']) : null;
$programmed = isset($_GET['prograpresta']);

$exemplePresta = [];
$players = [];
$heures = [];
$erreur = '';

try {
    $exemplePresta = Prestation::findAll($selectedScene, $selectedHeure, $programmed);
    $players = Joueur::findAll();
    $heures = Heure::findAll();
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
                            <option value="<?php echo $player->getIdJoueur(); ?>" <?php echo $player->getIdJoueur() === $selectedScene ? 'selected' : ''; ?>>
                                <?php echo $player->getPseudo(); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="Creneau">Choisissez une heure</label>
                    <select name="Creneau" id="Creneau">
                        <option value="">Toutes les heures</option>
                        <?php foreach ($heures as $heure) { ?>
                            <option value="<?php echo $heure->getIdHeure(); ?>" <?php echo $heure->getIdHeure() === $selectedHeure ? 'selected' : ''; ?>>
                                <?php echo $heure->toString(); ?>
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
            foreach ($exemplePresta as $value) {
                $Player = $value->findJoueur($value->getIdJoueur());
                $playerName = $Player ? $Player->getPseudo() : 'Joueur inconnu';
                echo "<a href='prestationEx.php?id=" . $value->getIdPrestation() . "' class='vignette'>
                <img src='assets/img/". $value->getImage() . "'alt='photo de la prestation'>
                <h3>" . $value->getTitre() . "</h3>
                <p><strong>Joueur : </strong>" . $playerName . "</p>
                <p>" . $value->getDescription() . "</p>
                </a>";
            } ?>
        </div>
    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>