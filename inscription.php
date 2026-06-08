<?php
$error = [];

$success = null;

$Utilisateur = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nom = $_POST['user_name'] ?? "";
    if (empty($Nom)) {
        $error['nom'] = "le nom est obligatoire";
    }
    $Prenom = $_POST['user_surname'] ?? "";
    if (empty($Prenom)) {
        $error['prenom'] = "le prénom est obligatoire";
    }
    $Email = $_POST['user_email'] ?? "";
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        $error['Email'] = "l'email n'est pas valide";
    }
    $Password = $_POST['user_password'] ?? "";
    if (empty($Password)) {
        $error['password'] = "Le mot de passe est obligatoire";
    }
    $passwordConfirm = $_POST['user_password_confirm'] ?? "";
     if ($passwordConfirm != $Password) {
        $error['passwordconfirm'] = "la confirmation ne correspond pas au mot de passe";
     }
    if (empty($error)) {
        $success = true;
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
                <input type="text" id="surname" name="user_surname" placeholder="Votre prénom..." required>
                 <?php if (isset($error['prenom'])) {
                        echo "<span>" . $error['prenom'] . "</span><br>";
                }
                ?>

                <label for="email">E-mail :</label>
                <input type="email" id="email" name="user_email" placeholder="Votre adresse mail" required>
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
            </fieldset>
        </form>
        <?php
        if ($success) {
            echo "<div class='pop-up'>vous êtes connecté</div>";
        }
        ?>

    </main>
    <?php
    require_once "app/view/footer.php";
    ?>
</body>

</html>