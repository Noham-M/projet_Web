<?php
require_once "app/Model/Joueur.php";

$joueurSelectionne = null;
$erreur = "";


if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id']; 
    try {
        
        $joueurSelectionne = Joueur::findById($id);
        
        if (!$joueurSelectionne) {
            $erreur = "Ce joueur n'existe pas.";
        }
    } catch (PDOException $e) {
        $erreur = "Erreur de base de données : " . $e->getMessage();
    }
} else {
    $erreur = "Aucun joueur n'a été sélectionné.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Fiche de Joueur</title>
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
        <p>" . $joueurSelectionne->getDescription() . "</p>
        </div>
        </article>";
    }?>
        
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>