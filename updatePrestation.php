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
$prestation = null;
$isAdmin = false;

try {
    if (!empty($_SESSION['user_email'])) {
        $utilisateur = Utilisateur::findByEmail($_SESSION['user_email']);
        if ($utilisateur) {
            $admins = Utilisateur::getAdmins();
            foreach ($admins as $admin) {
                if ($admin->getEmail() === $utilisateur->getEmail()) {
                    $isAdmin = true;
                    break;
                }
            }
            if (!$isAdmin) {
                $Joueur = $utilisateur->getJoueur();
            }
        }
    }
    if (!$utilisateur || (!$isAdmin && !$Joueur)) {
        $error['access'] = "Accès refusé. Veuillez vous connecter en tant que joueur ou administrateur.";
    }
    $scenes = Scene::findAll();
    $heures = Heure::findAll();
} catch (Exception $e) {
    $error['db'] = "Erreur de base de données.";
    $scenes = [];
    $heures = [];
}

$prestationId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if ($prestationId) {
    try {
        $prestation = Prestation::findById($prestationId);
        if ($prestation && ($isAdmin || ($Joueur && $prestation->getIdJoueur() === $Joueur->getIdJoueur()))) {
            $title = $prestation->getTitre();
            $description = $prestation->getDescription();
            $image = $prestation->getImage();
            $idScene = $prestation->getIdScene();
            $idHeure = $prestation->getIdHeure();
        } else {
            $error['access'] = "Prestation introuvable ou accès refusé.";
            $prestation = null;
        }
    } catch (Exception $e) {
        $error['db'] = "Erreur de base de données.";
    }
} else {
    if (empty($error['access'])) {
        $error['access'] = "Identifiant de prestation invalide.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $prestation && empty($error['access'])) {
    $title = trim($_POST['title'] ?? $title);
    if (empty($title)) {
        $error['title'] = "Le titre est obligatoire.";
    }

    $description = trim($_POST['description'] ?? $description);
    if (empty($description)) {
        $error['description'] = "La description est obligatoire.";
    }

    $image = trim($_POST['image'] ?? $image);
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

    if (empty($error)) {
        try {
            $prestation->setTitre($title);
            $prestation->setDescription($description);
            $prestation->setImage($image);
            $prestation->setIdScene($idScene);
            $prestation->setIdHeure($idHeure);
            if ($prestation->update()) {
                if ($isAdmin) {
                    header('Location: gererArtistes.php');
                    exit;
                } else {
                    header('Location: modifierPrestation.php');
                    exit;
                }
                
            }
            $error['db'] = "Impossible de mettre à jour la prestation.";
        } catch (Exception $e) {
            $error['db'] = "Erreur lors de la mise à jour de la prestation.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une prestation</title>
    <link rel="stylesheet" href="assets/css/Style.css">
</head>
<body>
    <?php require_once "app/view/header.php"; ?>
    <main>
        <?php if (isset($error['access'])) : ?>
            <p><?php echo htmlspecialchars($error['access']); ?></p>
        <?php else : ?>
            <form action="updatePrestation.php?id=<?php echo htmlspecialchars($prestationId); ?>" method="post">
                <fieldset>
                    <h2>Modifier une prestation</h2>

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
                        <?php foreach ($scenes as $sceneOption) : ?>
                            <option value="<?php echo htmlspecialchars($sceneOption->getIdScene()); ?>" <?php echo $sceneOption->getIdScene() === $idScene ? 'selected' : ''; ?>><?php echo htmlspecialchars($sceneOption->getNom()); ?></option>
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

                    <button type="submit">Mettre à jour</button>
                    <?php if (isset($error['db'])) { echo "<p>" . htmlspecialchars($error['db']) . "</p>"; } ?>
                </fieldset>
            </form>
        <?php endif; ?>
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>
