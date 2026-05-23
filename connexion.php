<?php
$ConnectEmail = '';
$Email = '';
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $ConnectEmail = $_POST['user_mail'] ?? "";
    $ConnectPassWord = $_POST['user_password'] ?? "";
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

                <button type="submit">Envoyer</button>
                <div>
                <a id="inscription" href="inscription.php">Vous n'avez pas de compte ?</a>
                <a id="OubliMdp"href="#">mot de passe oublié ?</a>
                </div>
            </fieldset>
        </form>
    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>