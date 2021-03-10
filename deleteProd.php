<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['log'] == 1) {
// supprimer l'utilisateur dont l'id a été passé dans l'url
    $id = filter_input(INPUT_GET, 'id');

    if ($id != null) {
        $id = intval($id);
        // vérifier si le joueur existe en bdd, vérifier que l'id est un entier et que != 0
        $pdo = new PDO("mysql:host=localhost;dbname=php-commerce;charst=utf8", "root", "");
        $sql = "DELETE FROM produit WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        //$stmt->bindValue(1, $id);
        $result = $stmt->execute([':id' => $id]);

        if (!$result) {
            // pour debugger
            $stmt->debugDumpParams();
            $code = 0;
        } else {
            $code = $id;
        }
    } else {
        $code = -1;
    }

    header("Location:products.php?delete=$code");

} else {
    header('Location: login.php?login=error');
}
