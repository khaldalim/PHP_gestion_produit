<?php
// formulaire soumis ?
$pdo = new PDO("mysql:host=localhost;dbname=php-commerce;charst=utf8", "root", "");
if (isset($_GET['login'])) {
    if ($_GET['login'] == 'error'){
        $message = "Erreur lors de la connexion";
    }
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // récupération des valeurs

    $password = filter_input(INPUT_POST, 'password');
    $email = filter_input(INPUT_POST, 'email');


    // vérification des formats
    $errors = [];

    if (!preg_match("#^[a-zA-Z0-9\-_]+@[a-zA-Z0-9\-_]+\.[a-z]{2,}$#", $email)) {
        $errors['email'] = "Erreur email";
    }
    if (mb_strlen($password) < 0) {
        $errors['password'] = "veuillez taper un mot de passe";
    }

    if (empty($errors)) {

        $sql = "select * FROM user WHERE username = :emailParam AND password = :passwordParam LIMIT 1";
        $statement = $pdo->prepare($sql);
        $result = $statement->execute([
            ':emailParam' => $email,
            ':passwordParam' => $password
        ]);
        $user = $statement->fetchAll(PDO::FETCH_ASSOC);


        if (count($user) == 1) {
            $message = "Validé";
            session_start();
            $_SESSION['log'] = true;
            $_SESSION['user'] = $email;
            header('Location: products.php');
            exit;
        } else {
            $message = "Erreur lors de la connexion";
        }

    } else {
        $message = "Erreur";
    }
}

include 'header.php';
if (isset($message)) {
    echo "<p>$message</p>";
    if (!empty($errors)) {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo "</ul>";
    }
}
?>
<form method="POST" action="">

    <input type="email" name="email" required placeholder="Votre email"
           pattern="[a-zA-Z0-9\-_]+@[a-zA-Z0-9\-_]+\.[a-z]{2,}"/><br>
    <input name="password" required placeholder="mot de passe"/><br>

    <input type="submit" name="submit"/>
</form>
<?php
include "footer.php";
?>


