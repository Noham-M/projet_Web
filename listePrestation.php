<?php
include_once "app/Model/prestation.php";
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $actual = $_GET['Lieu'] ?? "";
}
$exemplePresta = [
    new Prestation("6v6 Overwatch Qp", "Overwatch", "2 ou 3 match rapide en 6v6 sur overwatch","assets/img/video-games-1557358_1280.jpg"),
    new Prestation("Mini tournoi de 4 équipe", "valorant", "4 équipe de 5 s'affronte en B03 sur valorant","assets/img/video-games-1557358_1280.jpg"),
    new Prestation("Mini tournoi de 4 équipe", "csgo", "4 équipe de 5 s'affronte en B03 sur CS2","assets/img/video-games-1557358_1280.jpg"),
    new Prestation("presentation jeu de l'année", "Zen", "présentation des jeu de l'année 2025","assets/img/video-games-1557358_1280.jpg"),
    new Prestation("Mini Tournoi 5v5", "Overwatch", "Mini tournoi en 5v5 sur Overwatch","assets/img/video-games-1557358_1280.jpg")
];

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