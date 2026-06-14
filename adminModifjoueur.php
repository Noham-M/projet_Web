<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/app/Model/utilisateur.php";
require_once __DIR__ . "/app/Model/Joueur.php";
require_once __DIR__ . "/app/Model/prestation.php";
require_once __DIR__ . "/app/Model/Scene.php";
require_once __DIR__ . "/app/Model/Heure.php";

$error = [];
$message = null;
$user = null;
$joueur = null;
$isAdmin = false;

if (!empty($_SESSION['user_email'])) {
    $user = Utilisateur::findByEmail($_SESSION['user_email']);
    if ($user) {
        $admins = Utilisateur::getAdmins();
        foreach ($admins as $admin) {
            if ($admin->getEmail() === $user->getEmail()) {
                $isAdmin = true;
                break;
            }
        }
    }
}

if (!$isAdmin) {
    header('Location: connexion.php');
    exit;
}

$joueurId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$joueurId) {
    header('Location: gererArtistes.php');
    exit;
}

$joueur = Joueur::findById($joueurId);
if (!$joueur) {
    header('Location: gererArtistes.php');
    exit;
}

$utilisateur = $joueur->getUtilisateur();
if (!$utilisateur) {
    $error['db'] = 'Utilisateur lié non trouvé.';
}

$Nom = $utilisateur ? $utilisateur->getNom() : '';
$Prenom = $utilisateur ? $utilisateur->getPrenom() : '';
$Email = $utilisateur ? $utilisateur->getEmail() : '';
$Pseudo = $joueur->getPseudo();
$Description = $joueur->getDescription();
$Photo = $joueur->getImage();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete_prestation') {
        $prestationId = filter_var($_POST['prestation_id'] ?? null, FILTER_VALIDATE_INT);
        if ($prestationId) {
            $prestation = Prestation::findById($prestationId);
            if ($prestation && $prestation->getIdJoueur() === $joueur->getIdJoueur()) {
                if (Prestation::deleteById($prestationId)) {
                    $message = 'Prestation supprimée.';
                } else {
                    $error['db'] = 'Impossible de supprimer cette prestation.';
                }
            } else {
                $error['access'] = 'Prestation introuvable ou accès refusé.';
            }
        } else {
            $error['id'] = 'Identifiant de prestation invalide.';
        }
    } else {
        $Nom = trim($_POST['user_name'] ?? $Nom);
        if (empty($Nom)) {
            $error['nom'] = 'Le nom est obligatoire.';
        }

        $Prenom = trim($_POST['user_surname'] ?? $Prenom);
        if (empty($Prenom)) {
            $error['prenom'] = 'Le prénom est obligatoire.';
        }

        $Pseudo = trim($_POST['user_pseudo'] ?? $Pseudo);
        if (empty($Pseudo)) {
            $error['pseudo'] = 'Le pseudo est obligatoire.';
        }

        $Description = trim($_POST['user_description'] ?? $Description);
        if (empty($Description)) {
            $error['description'] = 'La description est obligatoire.';
        }

        $Photo = trim($_POST['user_photo'] ?? $Photo);
        if (empty($Photo)) {
            $error['photo'] = 'Le nom du fichier photo est obligatoire.';
        }

        $Email = trim($_POST['user_email'] ?? $Email);
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            $error['Email'] = 'L’email n’est pas valide.';
        } elseif ($utilisateur && $Email !== $utilisateur->getEmail()) {
            $existing = Utilisateur::findByEmail($Email);
            if ($existing && $existing->getIdUser() !== $utilisateur->getIdUser()) {
                $error['Email'] = 'Cet email est déjà utilisé.';
            }
        }

        $Password = $_POST['user_password'] ?? '';
        $passwordConfirm = $_POST['user_password_confirm'] ?? '';
        if ($Password !== '') {
            if ($passwordConfirm === '') {
                $error['passwordconfirm'] = 'Veuillez confirmer le mot de passe.';
            } elseif ($passwordConfirm !== $Password) {
                $error['passwordconfirm'] = 'La confirmation ne correspond pas au mot de passe.';
            }
        } else {
            $Password = $utilisateur ? $utilisateur->getPassword() : '';
        }

        if (empty($error) && $utilisateur) {
            try {
                $utilisateur->setNom($Nom);
                $utilisateur->setPrenom($Prenom);
                $utilisateur->setEmail($Email);
                $utilisateur->setPassword($Password);
                $userUpdated = $utilisateur->update();

                $joueur->setPseudo($Pseudo);
                $joueur->setDescription($Description);
                $joueur->setImage($Photo);
                $joueurUpdated = $joueur->update();

                if ($userUpdated && $joueurUpdated) {
                    $message = 'Profil joueur mis à jour avec succès.';
                } else {
                    $error['db'] = 'Impossible de mettre à jour le profil.';
                }
            } catch (Exception $e) {
                $error['db'] = 'Erreur lors de la mise à jour.';
            }
        }
    }
}

$prestations = $joueur->getPrestations();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Modifier joueur</title>
    <link rel="stylesheet" href="assets/css/Style.css">
</head>
<body>
    <?php require_once "app/view/header.php"; ?>
    <main>
        <section class="admin-modif-section">
            <form class="admin-modif-form" action="adminModifjoueur.php?id=<?php echo htmlspecialchars($joueurId); ?>" method="post">
                <fieldset>
                    <h2>Modifier le joueur <?php echo htmlspecialchars($joueur->getPseudo()); ?></h2>

                    <label for="name">Nom :</label><br>
                    <input type="text" id="name" name="user_name" value="<?php echo htmlspecialchars($Nom); ?>" required><br>
                    <?php if (isset($error['nom'])) { echo '<span>' . htmlspecialchars($error['nom']) . '</span><br>'; } ?>

                    <label for="surname">Prénom :</label><br>
                    <input type="text" id="surname" name="user_surname" value="<?php echo htmlspecialchars($Prenom); ?>" required><br>
                    <?php if (isset($error['prenom'])) { echo '<span>' . htmlspecialchars($error['prenom']) . '</span><br>'; } ?>

                    <label for="pseudo">Pseudo :</label><br>
                    <input type="text" id="pseudo" name="user_pseudo" value="<?php echo htmlspecialchars($Pseudo); ?>" required><br>
                    <?php if (isset($error['pseudo'])) { echo '<span>' . htmlspecialchars($error['pseudo']) . '</span><br>'; } ?>

                    <label for="description">Description :</label><br>
                    <textarea id="message" name="user_description" required><?php echo htmlspecialchars($Description); ?></textarea><br>
                    <?php if (isset($error['description'])) { echo '<span>' . htmlspecialchars($error['description']) . '</span><br>'; } ?>

                    <label for="photo">Photo :</label><br>
                    <input type="text" id="photo" name="user_photo" value="<?php echo htmlspecialchars($Photo); ?>" required><br>
                    <?php if (isset($error['photo'])) { echo '<span>' . htmlspecialchars($error['photo']) . '</span><br>'; } ?>

                    <label for="email">Email :</label><br>
                    <input type="email" id="email" name="user_email" value="<?php echo htmlspecialchars($Email); ?>" required><br>
                    <?php if (isset($error['Email'])) { echo '<span>' . htmlspecialchars($error['Email']) . '</span><br>'; } ?>

                    <label for="password">Mot de passe (laisser vide pour conserver) :</label><br>
                    <input type="password" id="password" name="user_password"><br>
                    <?php if (isset($error['password'])) { echo '<span>' . htmlspecialchars($error['password']) . '</span><br>'; } ?>

                    <label for="passwordconfirm">Confirmation du mot de passe :</label><br>
                    <input type="password" id="passwordconfirm" name="user_password_confirm"><br>
                    <?php if (isset($error['passwordconfirm'])) { echo '<span>' . htmlspecialchars($error['passwordconfirm']) . '</span><br>'; } ?>

                    <button type="submit" name="action" value="update_joueur">Enregistrer</button>
                    <?php if ($message) { echo '<p>' . htmlspecialchars($message) . '</p>'; } ?>
                    <?php if (isset($error['db'])) { echo '<p>' . htmlspecialchars($error['db']) . '</p>'; } ?>
                </fieldset>
            </form>
        </section>

        <div class="ListeVignette">
            <?php foreach ($prestations as $prestation) : ?>
                <?php $heure = $prestation->findHeure($prestation->getIdHeure());
                      $scene = $prestation->findScene($prestation->getIdScene()); ?>
                <div class="ModifPrestaVignette">
                    <img src="assets/img/<?php echo htmlspecialchars($prestation->getImage()); ?>" alt="<?php echo htmlspecialchars($prestation->getTitre()); ?>">
                    <h3><?php echo htmlspecialchars($prestation->getTitre()); ?></h3>
                    <p><?php echo htmlspecialchars($prestation->getDescription()); ?></p>
                    <p>Scène : <?php echo $scene ? htmlspecialchars($scene->getNom()) : 'N/A'; ?></p>
                    <p>Heure : <?php echo $heure ? htmlspecialchars($heure->toString()) : 'N/A'; ?></p>
                    <div class="prestationActions">
                        <a href="updatePrestation.php?id=<?php echo htmlspecialchars($prestation->getIdPrestation()); ?>" class="Deconnexion">Modifier</a>
                        <form action="adminModifjoueur.php?id=<?php echo htmlspecialchars($joueurId); ?>" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer cette prestation ?');">
                            <input type="hidden" name="prestation_id" value="<?php echo htmlspecialchars($prestation->getIdPrestation()); ?>">
                            <button type="submit" name="action" value="delete_prestation" class="delete-btn">Supprimer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>
</html>
