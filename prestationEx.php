<?php
require_once "app/Model/prestation.php";
require_once "app/Model/Scene.php";
require_once "app/Model/Joueur.php";
require_once "app/Model/Heure.php";

$prestation = null;
$Scene = null;
$Joueur = null;
$Heure = null;
$erreur = '';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $prestation = Prestation::findById($id);
        if ($prestation) {
        $Scene = $prestation->findScene($prestation->getidScene());
        $Joueur = $prestation->findJoueur($prestation->getIdJoueur());
        $Heure = $prestation->findHeure($prestation->getIdHeure());
        } else {
            $erreur = "Cette prestation n'existe pas.";
        }
    } catch (PDOException $e) {
        $erreur = "Erreur de base de données : " . $e->getMessage();
    }
} else {
    $erreur = "Aucune prestation n'a été sélectionnée.";
}
$programme = (!$Heure || !$Scene)? false :true;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journée Tournoi LAN jeux-vidéo</title>
    <link rel="stylesheet" href="css/Style.css">
</head>

<body>
    <?php
    require_once "app/view/header.php";
    ?>
    <main>

        <article id="TournoiBO3" class="exemple">
            <?php
            if (!empty($erreur)) {
            echo "<span>" . $erreur . "</span>";
            } elseif($prestation && $Joueur) {
            echo "<img src='assets/img/" . $prestation->getImage() . "' alt='image de manette'>
            <section id='descripTournoi'>
                <h2>" . $prestation->getTitre() . "</h2>";
                if ($programme) {
                    echo "<h3>heure : " . $Heure->toString() . "</h3>
                    <h3>scène : " . $Scene->getNom() . "</h3>";
                } else {
                    echo "<h3>Prestation non programmée</h3>";
                }
                echo "<h3><a href='JoueurEx.php?id=" . $Joueur->getIdJoueur() . "'>Joueur : " . $Joueur->getPseudo() . "</a></h3> 
                <p>déscription : " . $prestation->getDescription() . "</p>

            </section>";
            }
            ?>
        </article>


    </main>

    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>