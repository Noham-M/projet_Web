<?php
require_once "app/Model/Joueur.php";
$allPlayer = [
    new Joueur("jean", "valorant","'assets/img/racing-6249392_1280.jpg'", 1,"permatete","jean permatete","jeanPermatete@gmail.com","ggggg", "Petit joueur de Valorant venant de Liège, c'est l'un des meilleurs supports du pays."),
    new Joueur("Eva mChercherUneBierre", "csgo","'assets/img/racing-6249392_1280.jpg'", 2,"permatete","jean permatete","jeanPermatete@gmail.com","ggggg", "joueuse competitive elle est la pour révolutionner l'art de l'awp sur csgo"),
    new Joueur("JuJuCactus", "Overwatch","'assets/img/racing-6249392_1280.jpg'", 4,"permatete","jean permatete","jeanPermatete@gmail.com","ggggg", "Joueuse Dps sur Overwatch, elle est venue parce qu'il manquait des joueurs"),
    new Joueur("XxGalaxyDestroyerxX", "Overwatch","'assets/img/racing-6249392_1280.jpg'", 4,"permatete","jean permatete","jeanPermatete@gmail.com","ggggg", "Joueur support sur overwatch il est la pour concurencer tout les support de ce tournoi")
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
            foreach ($allPlayer as $value) {
                echo "<a href='Joueur.php' class='vignette'>

                <img src=" . $value->getImage() . "alt='portrait du joueur'>
                <h3>" . $value->getPseudo() . "</h3>

                <p><strong>Présence :</strong>" . $value->getheure()->toString() ."(" . $value->getScene() . ") .</p>
                <p>" . $value->getDescription() . "</p>
            </a>";
            }
            ?>

        </div>
    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>