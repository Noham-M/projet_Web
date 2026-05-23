<?php
$nom = '';
$prenom = '';
$Email = '';
$object = '';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'Post') {
    $nom = trim($_POST['user_name'] ?? '');
    $prenom = trim($_POST['user_surname'] ?? '');
    $Email = trim($_POST['user_email'] ?? '');
    $object = trim($_POST['message_object'] ?? '');
    $message = trim($_POST['user_message'] ?? '');
}

if (empty($nom)) {
    
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
    require_once 'app/view/header.php'
        ?>
    <main>
        <form action="connexion" method="post">
            <fieldset>
                <h2>Contactez-nous</h2>

                <label for="name">Nom :</label>
                <input type="text" id="name" name="user_name" placeholder="Votre nom..." required>


                <label for="surname">Prénom :</label>
                <input type="text" id="surname" name="user_surname" placeholder="Votre prénom..." required>


                <label for="email">E-mail :</label>
                <input type="email" id="email" name="user_email" placeholder="Votre E-mail" required>


                <label for="Object">Objet :</label>
                <input type="text" id="Object" name="message_object" placeholder="Sujet du message...">


                <label for="message">Message :</label>
                <textarea id="message" name="user_message" rows="5" placeholder="Votre message..." required></textarea>

                <button type="submit">Envoyer</button>
            </fieldset>
        </form>
    </main>
    <?php
    require_once 'app/view/footer.php';
    ?>
</body>

</html>