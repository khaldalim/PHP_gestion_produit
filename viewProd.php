<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['log'] == 1) {
    if (isset($_GET['id'])) {


        $pdo = new PDO("mysql:host=localhost;dbname=php-commerce;charst=utf8", "root", "");
        $query = "SELECT P.id as id , P.product_code,P.name, P.description, P.price  , C.nom AS catName FROM produit P INNER JOIN category C ON P.category = C.id WHERE  P.id = :id";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':id' => $_GET['id']
        ]);
        $produit = $statement->fetch(PDO::FETCH_ASSOC);

        include 'header.php';


        echo "<h1>{$produit['name']}</h1>
<p> Code : {$produit['product_code']}</p>
            <p>Description : {$produit['description']}</p>
            <p>Prix :{$produit['price']} €</p>
            <p>Categorie : {$produit['catName']}</p>
            ";


        include 'footer.php';

    } else {
        header('Location: products.php');
    }
} else {
    header('Location: login.php?login=error');
}
