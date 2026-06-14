<?php
require_once "app/Model/Joueur.php";
require_once "app/Model/prestation.php";

$joueurSelectionne = null;
$prestations = [];
$utilisateur = null;
$erreur = "";
$cpt = 1;


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id']; 
    try {
        
        $joueurSelectionne = Joueur::findById($id);
        $utilisateur = $joueurSelectionne ? $joueurSelectionne->getUtilisateur() : null;
        
        if (!$joueurSelectionne || !$utilisateur) {
            $erreur = "Ce joueur n'existe pas.";
        }
    } catch (PDOException $e) {
        $erreur = "Erreur de base de données : " . $e->getMessage();
    }
} else {
    $erreur = "Aucun joueur n'a été sélectionné.";
}

try {
    $prestations = $joueurSelectionne->getPrestations();
} catch (PDOException $e) {
    $erreur = "Erreur de base de données : " . $e->getMessage();
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
        
    <?php if ($erreur) {
       echo "<span>$erreur</span>";
    }elseif ($joueurSelectionne) {
         
        echo "<h2>Profil de ". $joueurSelectionne->getPseudo() . "</h2>
        <article id='presentation' class='exemple'>
        <img src='assets/img/" . $joueurSelectionne->getImage() . "' alt='Photo'>
        <div id='biographie'>
        <h3>Nom : " . $utilisateur->getNom() . " " . $utilisateur->getPrenom() . "</h3>
        <p>" . $joueurSelectionne->getDescription() . "</p>";
        echo "<div><h3>Prestations :</h3>";
        foreach ($prestations as $presta) {
            echo " <a href='prestationEx.php?id=" . $presta->getIdPrestation() . "'>prestation " . $cpt . " : " . $presta->getTitre() . "</a><br>";
            $cpt++;
        }
        echo "</div></div></article>";
    } else {    
        echo "</div>
        </article>";
    }?>
        
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>