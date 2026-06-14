<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/app/Model/utilisateur.php";
require_once __DIR__ . "/app/Model/prestation.php";
require_once __DIR__ . "/app/Model/Scene.php";
require_once __DIR__ . "/app/Model/Heure.php";

$error = [];
$title = '';
$description = '';
$image = '';
$idScene = null;
$idHeure = null;

$utilisateur = null;
$Joueur = null;
$success = '';

try {
    if (!empty($_SESSION['user_email'])) {
        $utilisateur = Utilisateur::findByEmail($_SESSION['user_email']);
        if ($utilisateur) {
            $Joueur = $utilisateur->getJoueur();
        }
    }
} catch (Exception $e) {
    $error['db'] = "Erreur de base de données.";
}

$scenes = Scene::findAll();
$heures = Heure::findAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    if (empty($title)) {
        $error['title'] = "Le titre est obligatoire.";
    }

    $description = trim($_POST['description'] ?? '');
    if (empty($description)) {
        $error['description'] = "La description est obligatoire.";
    }

    $image = trim($_POST['image'] ?? '');
    if (empty($image)) {
        $error['image'] = "Le nom du fichier image est obligatoire.";
    }

    $idScene = filter_var($_POST['scene_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$idScene) {
        $error['scene_id'] = "La scène est obligatoire.";
    }

    $idHeure = filter_var($_POST['heure_id'] ?? null, FILTER_VALIDATE_INT);
    if (!$idHeure) {
        $error['heure_id'] = "L'heure est obligatoire.";
    }

    if ($utilisateur && $Joueur && empty($error)) {
        try {
            $prestation = new Prestation(null, $idScene, $Joueur->getIdJoueur(), $idHeure, $title, $description, $image);
            if ($prestation->create()) {
                header('Location: modifierPrestation.php');
                exit;
            }
            $error['db'] = "Impossible de créer la prestation.";
        } catch (Exception $e) {
            $error['db'] = "Erreur lors de l'enregistrement de la prestation.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une prestation</title>
    <link rel="stylesheet" href="assets/css/Style.css">
</head>
<body>
    <?php require_once "app/view/header.php"; ?>
    <main>
        <form action="AjouterPrestation.php" method="post">
            <fieldset>
                <h2>Ajouter une prestation</h2>

                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" placeholder="Titre de la prestation" value="<?php echo htmlspecialchars($title); ?>" required>
                <?php if (isset($error['title'])) { echo "<span>" . htmlspecialchars($error['title']) . "</span><br>"; } ?>

                <label for="description">Description :</label>
                <textarea id="message" name="description" placeholder="Description de la prestation" required><?php echo htmlspecialchars($description); ?></textarea>
                <?php if (isset($error['description'])) { echo "<span>" . htmlspecialchars($error['description']) . "</span><br>"; } ?>

                <label for="image">Image  :</label>
                <input type="text" id="image" name="image" placeholder="assets/img/monimage.jpg" value="<?php echo htmlspecialchars($image); ?>" required>
                <?php if (isset($error['image'])) { echo "<span>" . htmlspecialchars($error['image']) . "</span><br>"; } ?>

                <label for="scene_id">Scène :</label><br>
                <select id="scene_id" name="scene_id" required>
                    <option value="">Sélectionnez une scène</option>
                    <?php foreach ($scenes as $scene) : ?>
                        <option value="<?php echo htmlspecialchars($scene->getIdScene()); ?>" <?php echo $scene->getIdScene() === $idScene ? 'selected' : ''; ?>><?php echo htmlspecialchars($scene->getNom()); ?></option>
                    <?php endforeach; ?>
                </select><br>
                <?php if (isset($error['scene_id'])) { echo "<span>" . htmlspecialchars($error['scene_id']) . "</span><br>"; } ?>

                <label for="heure_id">Heure :</label><br>
                <select id="heure_id" name="heure_id" required>
                    <option value="">Sélectionnez une heure</option>
                    <?php foreach ($heures as $heureOption) : ?>
                        <option value="<?php echo htmlspecialchars($heureOption->getIdHeure()); ?>" <?php echo $heureOption->getIdHeure() === $idHeure ? 'selected' : ''; ?>><?php echo htmlspecialchars($heureOption->toString()); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($error['heure_id'])) { echo "<span>" . htmlspecialchars($error['heure_id']) . "</span><br>"; } ?>

                <button type="submit">Ajouter</button>
                <?php if (isset($error['db'])) { echo "<p>" . htmlspecialchars($error['db']) . "</p>"; } ?>
            </fieldset>
        </form>
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>
