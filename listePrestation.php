<?php
include_once "app/Model/prestation.php";
$exemplePresta = [];
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $actual = $_GET['Lieu'] ?? "";
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
                    <label for="Lieu">Choisissez une scène</label>
                    <select name="Lieu" id="Lieu">
                        <option value="">Toutes les scènes</option>
                        <option value="valorant">Scène Valorant</option>
                        <option value="csgo">Scène Counter-Strike</option>
                        <option value="Zen">Espace détente</option>
                        <option value="Overwatch">Scène Overwatch</option>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="Creneau">Choisissez une heure</label>
                    <select name="Creneau" id="Creneau">
                        <option value="">Toutes les heures</option>
                        <option value="8h">8h-10h</option>
                        <option value="10h">10h-12h</option>
                        <option value="12h">12h-13h</option>
                        <option value="13h">13h-15h</option>
                        <option value="15h">15h-18h</option>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="prograPresta">Afficher prestation programmé</label>
                    <input type="checkbox" id="prograPresta"> 
                </div>

                <button type="submit">Rechercher</button>
            </fieldset>
        </form>

        <div class="ListeVignette">
            <?php
            foreach ($exemplePresta as $value) {
                echo "<a href='prestationEx.php' class='vignette'>
                <img src='". $value->getImage() . "'alt='photo de la prestation'>
                <h3>" . $value->getTitre() . "</h3>
                <p><strong>Scène : </strong>" . $value->getScene() . "</p>
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