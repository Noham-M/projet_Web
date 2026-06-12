<?php
require_once "app/Model/Joueur.php";
$programmed = isset($_GET['prograArtiste']);
$Joueurs = [];
$erreur = '';
try {
    $Joueurs = Joueur::findAll($programmed);
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
                <div class="filterGroup">
                    <label for="prograPresta">Afficher artiste programmé</label>
                    <input type="checkbox" id="prograPresta" name="prograArtiste" value="1" <?php echo $programmed ? 'checked' : ''; ?>>
                </div>
                <button type="submit" id="bouttonFiltre">Rechercher</button>

            </fieldset>
        </form>

        <div class="ListeVignette">
            <?php
            if ($erreur) {
                echo "<span> $erreur <span>";
            } elseif(empty($Joueurs)) {
                echo "<p>Aucun joueur ne correspond à votre recherche.</p>";
            } else {
                foreach ($Joueurs as $value) {
                echo "<a href='JoueurEx.php?id=" . $value->getIdJoueur() . "' class='vignette'>
                <img src='" . "assets/img/" . $value->getImage() . "' alt='portrait du joueur'>
                <h3>" . $value->getPseudo() . "</h3>";
                if (!$value->getPrestations()) {
                    echo "<p>Aucune prestation programmée</p>";
                }
                foreach ($value->getPrestations() as $presta) {
                    $scene = $presta->findScene($presta->getIdScene());
                    $heure = $presta->findHeure($presta->getIdHeure());
                    if ($scene && $heure) {
                        echo "<p>" . $scene->getNom() . " à " . $heure->toString() . "</p>";
                    } else {
                        echo "<p>Prestation non programmée</p>";
                    }
                }
                echo "<p>" . $value->getDescription() . "</p>
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