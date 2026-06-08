<?php
require_once "app/Model/Joueur.php";
$Joueurs = [];
$erreur = '';
try {
    $Joueurs = Joueur::findAll();
} catch (PDOException $e) {
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
        <h2>Liste des joueurs</h2>
        <form action="ListeDesJoueur.php" method="GET" class="filter">

            <fieldset>
                <legend>Filtrer les participants</legend>
                <div class="filterGroup">
                    <label for="scene">Choisissez une scène</label>
                    <select name="scene" id="scene">
                        <option value="">Toutes les scènes</option>
                        <option value="valorant">Scène Valorant</option>
                        <option value="csgo">Scène Counter-Strike</option>
                        <option value="Zen">Espace détente</option>
                        <option value="Overwatch">Scène Overwatch</option>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="Heure">Choisissez une heure</label>
                    <select name="Heure" id="Heure">
                        <option value="">Toutes les heures</option>
                        <option value="8h">8h-10h</option>
                        <option value="10h">10h-12h</option>
                        <option value="12h">12h-13h</option>
                        <option value="13h">13h-15h</option>
                        <option value="15h">15h-18h</option>
                    </select>
                </div>
                <div class="filterGroup">
                    <label for="prograPresta">Afficher artiste programmé</label>
                    <input type="checkbox" id="prograPresta">
                </div>
                <button type="submit" id="bouttonFiltre">Rechercher</button>

            </fieldset>
        </form>

        <div class="ListeVignette">
            <?php
            if ($erreur) {
                echo "<span> $erreur <span>";
            } else {
                foreach ($Joueurs as $value) {
                echo "<a href='JoueurEx.php?id=" . $value->getIdJoueur() . "' class='vignette'>
                <img src='" . "assets/img/" . $value->getImage() . "' alt='portrait du joueur'>
                <h3>" . $value->getPseudo() . "</h3>
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