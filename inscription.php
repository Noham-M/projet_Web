<?php
require_once __DIR__ . "/app/Model/utilisateur.php";
require_once __DIR__ . "/app/Model/Joueur.php";
$error = [];

$Nom = '';
$Prenom = '';
$Email = '';
$Pseudo = '';
$Description = '';
$Photo = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nom = trim($_POST['user_name'] ?? "");
    if (empty($Nom)) {
        $error['nom'] = "Le nom est obligatoire.";
    }

    $Prenom = trim($_POST['user_surname'] ?? "");
    if (empty($Prenom)) {
        $error['prenom'] = "Le prénom est obligatoire.";
    }

    $Pseudo = trim($_POST['user_pseudo'] ?? "");
    if (empty($Pseudo)) {
        $error['pseudo'] = "Le pseudo est obligatoire.";
    }

    $Email = trim($_POST['user_email'] ?? "");
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $error['Email'] = "L'email n'est pas valide.";
    } elseif (Utilisateur::findByEmail($Email)) {
        $error['Email'] = "Cet email est déjà utilisé.";
    }

    $Description = trim($_POST['user_description'] ?? "");
    if (empty($Description)) {
        $error['description'] = "La description est obligatoire.";
    }

    $Photo = trim($_POST['user_photo'] ?? "");
    if (empty($Photo)) {
        $error['photo'] = "Le nom du fichier photo est obligatoire.";
    }

    $Password = $_POST['user_password'] ?? "";
    if (empty($Password)) {
        $error['password'] = "Le mot de passe est obligatoire.";
    }

    $passwordConfirm = $_POST['user_password_confirm'] ?? "";
    if ($passwordConfirm !== $Password) {
        $error['passwordconfirm'] = "La confirmation ne correspond pas au mot de passe.";
    }

    if (empty($error)) {
        $utilisateur = new Utilisateur(null, $Nom, $Prenom, $Email, $Password);
        if ($utilisateur->create()) {
            $joueur = new Joueur(null, $Photo, $Pseudo, $Description, $utilisateur->getIdUser());
            if ($joueur->create()) {
                session_start();
                $_SESSION['user_id'] = $utilisateur->getIdUser();
                $_SESSION['user_email'] = $utilisateur->getEmail();
                header("Location: tableauDeBord.php");
                exit();
            }
            $error['db'] = "Impossible de créer le profil joueur.";
        } else {
            $error['db'] = "Impossible de créer l'utilisateur.";
        }
    }
}


?>

    <?php
    require_once "app/view/header.php";
    ?>
    <main>
        <form action="inscription.php" method="post">
            <fieldset>
                <h2>inscription</h2>

                <label for="name">Nom :</label>
                <input type="text" id="name" name="user_name" placeholder="Votre nom..." required>
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

                <label for="photo">Photo :</label>
                <input type="file" id="photo" name="user_photo"  accept="image/png, image/jpg" required>
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

                <label for="password">Mot de passe :</label>
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
                <?php if (isset($error['db'])) {
                    echo "<p>" . htmlspecialchars($error['db']) . "</p>";
                } ?>
            </fieldset>
        </form>

    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>