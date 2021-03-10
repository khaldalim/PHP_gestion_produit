<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['log'] == 1) {
    $pdo = new PDO("mysql:host=localhost;dbname=php-commerce;charst=utf8", "root", "");
    include "header.php";

    $query = "SELECT P.id, P.created_date, P.product_code,P.name, P.description, P.price  , C.nom AS catName FROM produit P INNER JOIN category C ON P.category = C.id ";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $produits = $statement->fetchAll(PDO::FETCH_ASSOC);

    //action après suppression
    if (isset($_GET['delete'])) {
        $code = $_GET['delete'];
        switch ($code) {
            case 0:
                echo "La requête a planté";
                break;
            case -1:
                echo "Accès refusé";
                break;
            default:
                echo "<b style='color: red'>Le produit $code a bien été supprimé</b>";
        }
    } else if (isset($_GET['update'])) {
        $code = $_GET['update'];
        echo "<b style='color: green'>Le produit $code a bien été modifié</b>";
    }

    ?>

    <table class="table ">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Date création</th>
            <th scope="col">Code produit</th>
            <th scope="col">Nom</th>
            <th scope="col">Prix</th>
            <th scope="col">Catégorie</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>


        <?php
        foreach ($produits as $produit) {
            echo "
            <tr>
            <td>{$produit['id']}</td>
            <td>{$produit['created_date']}</td>
            <td>{$produit['product_code']}</td>
            <td>{$produit['name']}</td>            
            <td>{$produit['price']} €</td>
            <td>{$produit['catName']}</td>   
                 
           <td><a class='btn btn-info' href='viewProd.php?id={$produit['id']}'>Voir</a> </td>
            <td><a class='btn btn-success' href='productForm.php?id={$produit['id']}'>Modifier</a> </td>
            <td><a class='btn btn-danger' href='deleteProd.php?id={$produit['id']}' onclick=\"if (!confirm('Etes vous sur de vouloir supprimer ce produit ?')) return false;\" >Supprimer</td>
        </tr>
        ";
        } ?>
        </tbody>
    </table>

    <a class="btn btn-info" href="productForm.php">Ajouter une produit</a>


    <?php
    include "footer.php";
} else {
    header('Location: login.php?login=error');
}
