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
    } else if (empty($Email)) {
        $error['Email'] = "L'email est obligatoire";
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


                <label for="surname">Prénom :</label>
                <input type="text" id="surname" name="user_surname" placeholder="Votre prénom..." required>


                <label for="email">E-mail :</label>
                <input type="email" id="email" name="user_email" placeholder="Votre adresse mail" required>
               

                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="user_password" placeholder="Votre mot de passe">


                <label for="passwordconfirm">Confirmation du mot de passe :</label>
                <input type="password" id="passwordconfirm" name="user_password_confirm" placeholder="Confirmation">
                 <?php if (isset($error['Email'])) {
                        echo "<span>" . $error['passwordconfirm'] . "</span>";
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