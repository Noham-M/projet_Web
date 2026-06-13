<?php
require_once "app/Model/utilisateur.php";
$ConnectEmail = '';
$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['user_email']) && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL) && isset($_POST['user_password']) && !empty($_POST['user_password'])) {
        $ConnectEmail = $_POST['user_email'] ?? "";
        $utilisateur = Utilisateur::findByEmail($ConnectEmail);
        if ($utilisateur && $_POST['user_password'] === $utilisateur->getPassword()) {
            session_start();
            $_SESSION['user_id'] = $utilisateur->getIdUser();
            $_SESSION['user_email'] = $utilisateur->getEmail();

            $admins = Utilisateur::getAdmins();
            $isAdmin = false;
            foreach ($admins as $admin) {
                if ($admin->getEmail() === $utilisateur->getEmail()) {
                    $isAdmin = true;
                    break;
                }
            }

            if ($isAdmin) {
                header("Location: organisateur.php");
            } else {
                header("Location: tableauDeBord.php");
            }
            exit();
        }

        $erreur = "Email ou mot de passe incorrect.";
    } else {
        $erreur = "Veuillez remplir tous les champs correctement.";
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
    <?php
    require_once "app/view/header.php";
    ?>
    <main>
        <form action="connexion.php" method="post">
            <fieldset>
                <h2>Accès à votre compte</h2>

                <label for="email">E-mail :</label>
                <input type="email" id="email" name="user_email" placeholder="Votre E-mail" required><br><br>

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="user_password" placeholder="Votre mot de passe"
                    required><br><br>
                <?php if (!empty($erreur)) : ?>
                    <span class="error"><?php echo htmlspecialchars($erreur); ?></span><br>
                <?php endif; ?>
                <button type="submit">Envoyer</button>
                <div>
                    <a id="inscription" href="inscription.php">Vous n'avez pas de compte ?</a>
                </div>
            </fieldset>
        </form>
    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>