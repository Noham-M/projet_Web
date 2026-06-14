<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . "/app/Model/utilisateur.php";
require_once __DIR__ . "/app/Model/Joueur.php";
$error = [];

$Nom = '';
$Prenom = '';
$Email = '';
$Pseudo = '';
$Description = '';
$Photo = '';
$Password = '';
$passwordConfirm = '';

$utilisateur = null;
$Joueur = null;
$success = '';

if (!empty($_SESSION['user_email'])) {
    $utilisateur = Utilisateur::findByEmail($_SESSION['user_email']);
    if ($utilisateur) {
        $Nom = $utilisateur->getNom();
        $Prenom = $utilisateur->getPrenom();
        $Email = $utilisateur->getEmail();

        $Joueur = $utilisateur->getJoueur();
        if ($Joueur) {
            $Pseudo = $Joueur->getPseudo();
            $Description = $Joueur->getDescription();
            $Photo = $Joueur->getImage();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nom = trim($_POST['user_name'] ?? $Nom);
    if (empty($Nom)) {
        $error['nom'] = "Le nom est obligatoire.";
    }

    $Prenom = trim($_POST['user_surname'] ?? $Prenom);
    if (empty($Prenom)) {
        $error['prenom'] = "Le prénom est obligatoire.";
    }

    $Pseudo = trim($_POST['user_pseudo'] ?? $Pseudo);
    if (empty($Pseudo)) {
        $error['pseudo'] = "Le pseudo est obligatoire.";
    }

    $Email = trim($_POST['user_email'] ?? $Email);
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $error['Email'] = "L'email n'est pas valide.";
    } elseif ($utilisateur && $Email !== $utilisateur->getEmail() && Utilisateur::findByEmail($Email)) {
        $error['Email'] = "Cet email est déjà utilisé.";
    }

    $Description = trim($_POST['user_description'] ?? $Description);
    if (empty($Description)) {
        $error['description'] = "La description est obligatoire.";
    }

    if (isset($_POST['user_photo']) && trim($_POST['user_photo']) === '' && $Joueur) {
        $Photo = $Joueur->getImage();
    } else {
        $Photo = trim($_POST['user_photo'] ?? $Photo);
    }

    if (empty($Photo)) {
        $error['photo'] = "Le nom du fichier photo est obligatoire.";
    }

    $Password = $_POST['user_password'] ?? "";
    $passwordConfirm = $_POST['user_password_confirm'] ?? "";
    if ($Password !== '') {
        if ($passwordConfirm === '') {
            $error['passwordconfirm'] = "Veuillez confirmer le mot de passe.";
        } elseif ($passwordConfirm !== $Password) {
            $error['passwordconfirm'] = "La confirmation ne correspond pas au mot de passe.";
        }
    } else {
        if ($utilisateur) {
            $Password = $utilisateur->getPassword();
        }
    }

    if (empty($error)) {
        if ($utilisateur) {
            $utilisateur->setNom($Nom);
            $utilisateur->setPrenom($Prenom);
            $utilisateur->setEmail($Email);
            $utilisateur->setPassword($Password);
            $userUpdated = $utilisateur->update();
            if ($userUpdated) {
                $_SESSION['user_email'] = $Email;
            }
        }

        $joueurUpdated = true;
        if ($Joueur) {
            $Joueur->setPseudo($Pseudo);
            $Joueur->setDescription($Description);
            $Joueur->setImage($Photo);
            $joueurUpdated = $Joueur->update();
        }

        if (!empty($userUpdated) && $joueurUpdated) {
            $success = "Profil mis à jour avec succès.";
        } elseif (!empty($userUpdated) && empty($Joueur)) {
            $success = "Profil utilisateur mis à jour avec succès.";
        } else {
            $error['db'] = "Impossible de mettre à jour le profil. Veuillez réessayer.";
        }
    }
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
        <form action="modifierJoueur.php" method="post">
            <fieldset>
                <h2>Modifier profil de <?php echo htmlspecialchars($Pseudo); ?></h2>

                <label for="name">Nom :</label>
                <input type="text" id="name" name="user_name" placeholder="Votre nom..." value="<?php echo htmlspecialchars($Nom); ?>" required>
                <?php if (isset($error['nom'])) {
                    echo "<span>" . $error['nom'] . "</span><br>";
                }
                ?>

                <label for="surname">Prénom :</label>
                <input type="text" id="surname" name="user_surname" placeholder="Votre prénom..." value="<?php echo htmlspecialchars($Prenom); ?>" required>
                <?php if (isset($error['prenom'])) {
                    echo "<span>" . $error['prenom'] . "</span><br>";
                }
                ?>

                <label for="pseudo">Pseudo :</label>
                <input type="text" id="pseudo" name="user_pseudo" placeholder="Votre pseudo..." value="<?php echo htmlspecialchars($Pseudo); ?>" required>
                <?php if (isset($error['pseudo'])) {
                    echo "<span>" . $error['pseudo'] . "</span><br>";
                }
                ?>

                <label for="message">Description :</label>
                <textarea id="message" name="user_description" placeholder="Parlez un peu de vous..." required><?php echo htmlspecialchars($Description); ?></textarea>
                <?php if (isset($error['description'])) {
                    echo "<span>" . $error['description'] . "</span><br>";
                }
                ?>

                <label for="photo">Nom du fichier photo :</label>
                <input type="text" id="photo" name="user_photo" placeholder="Nom du fichier photo" value="<?php echo htmlspecialchars($Photo); ?>">
                <?php if (isset($error['photo'])) {
                    echo "<span>" . $error['photo'] . "</span><br>";
                }
                ?>

                <label for="email">E-mail :</label>
                <input type="email" id="email" name="user_email" placeholder="Votre adresse mail" value="<?php echo htmlspecialchars($Email); ?>" required>
                <?php if (isset($error['Email'])) {
                    echo "<span>" . $error['Email'] . "</span><br>";
                }
                ?>

                <label for="password">Mot de passe (laisser vide pour conserver l'actuel) :</label>
                <input type="password" id="password" name="user_password" placeholder="Votre mot de passe">
                <?php if (isset($error['password'])) {
                    echo "<span>" . $error['password'] . "</span><br>";
                }
                ?>

                <label for="passwordconfirm">Confirmation du mot de passe :</label>
                <input type="password" id="passwordconfirm" name="user_password_confirm" placeholder="Confirmation">
                <?php if (isset($error['passwordconfirm'])) {
                    echo "<span>" . $error['passwordconfirm'] . "</span><br>";
                }
                ?>

                <button type="submit">Envoyer</button>
                <?php if (!empty($success)) : ?>
                    <p class="success"><?php echo htmlspecialchars($success); ?></p>
                <?php endif; ?>
                <?php if (isset($error['db'])) {
                    echo "<p>" . htmlspecialchars($error['db']) . "</p>";
                } ?>
            </fieldset>
        </form>

    </main>
    <?php require_once "app/view/footer.php"; ?>
</body>

</html>