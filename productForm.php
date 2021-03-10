<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({selector:'textarea'});</script>
<?php
session_start();
if (isset($_SESSION['user']) && $_SESSION['log'] == 1) {
    include "header.php";
    $pdo = new PDO("mysql:host=localhost;dbname=php-commerce;charst=utf8", "root", "");

    $idProd = "";
    $codeProd = "";
    $nomProd = "";
    $description = "";
    $priceProd = "";
    $categoryProd = "";


    //liste des categories
    $query = "SELECT * FROM category";
    $statement = $pdo->prepare($query);
    $statement->execute();
    $categories = $statement->fetchAll(PDO::FETCH_ASSOC);


    if (isset($_GET['id'])) {
        $idProd = $_GET['id'];
        $query = "SELECT * FROM produit WHERE id = :id LIMIT 1";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':id' => $idProd
        ]);
        $dataGet = $statement->fetch(PDO::FETCH_ASSOC);
        $codeProd = $dataGet['product_code'];
        $nomProd = $dataGet['name'];
        $description = $dataGet['description'];
        $priceProd = $dataGet['price'];
        $categoryProd = $dataGet['category'];

    }


    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $codeProd = filter_input(INPUT_POST, 'codeProd');
        $nomProd = filter_input(INPUT_POST, 'nomProd');
        $description = filter_input(INPUT_POST, 'descProd');
        $priceProd = filter_input(INPUT_POST, 'priceProd');
        $categoryProd = filter_input(INPUT_POST, 'category');
        $errors = [];

        //check if code exist
        $query = "SELECT product_code FROM produit WHERE product_code = :code";
        $statement = $pdo->prepare($query);
        $statement->execute([
            ':code' => $codeProd
        ]);
        $codeProdExist = $statement->fetch(PDO::FETCH_ASSOC);

        if (!preg_match("#^[a-z]{3}[0-9]{3}$#", $codeProd)) {
            $errors['codeProd'] = "Repecter le format 3 lettres + 3 numéro exemple : 'AAA444'";
        }

        if (!isset($_GET['id'])) {
            if ($codeProdExist) {
                $errors['codeExist'] = "Ce code produit existe déja";
            }
        }


        if (strlen($nomProd) < 5) {
            $errors['email'] = "Taper une nom d'au moins 5 caractères";
        }
        if (!preg_match("#^\d{0,}\.?\d{1,2}$#", $priceProd)) {
            $errors['priceProd'] = "Erreur dans le prix";
        }

        $categoriesIds = array_column($categories, "id");
        if (!in_array($categoryProd, $categoriesIds)) {
            $errors['category'] = "Choisissez une catégorie valide";
        }


        if (empty($errors)) {
            //update
            if (isset($_GET['id'])) {
                $sql = "UPDATE produit set 
                                product_code = :codeProd,
                                name = :nomProd,
                                description = :description,
                                price = :priceProd,
                                category = :category
                            WHERE id = :idProd";
                $statement = $pdo->prepare($sql);

                $result = $statement->execute([
                    ':codeProd' => $codeProd,
                    ':nomProd' => $nomProd,
                    ':description' => $description,
                    ':priceProd' => $priceProd,
                    ':category' => $categoryProd,
                    ':idProd' => $idProd
                ]);

                if ($result) {
                    header("Location:products.php?update=$idProd");
                } else {
                    $message = "Erreur lors de la modification";
                }
            } else {
                //insert
                $sql = "INSERT INTO produit( product_code, name, description, price, category)  
                            VALUES (:codeProd, :nomProd, :description, :priceProd, :category )";
                $statement = $pdo->prepare($sql);
                $result = $statement->execute([
                    ':codeProd' => $codeProd,
                    ':nomProd' => $nomProd,
                    ':description' => $description,
                    ':priceProd' => $priceProd,
                    ':category' => $categoryProd
                ]);

                if ($result) {
                    $message = "Produit enregitré !";
                    $codeProd = "";
                    $nomProd = "";
                    $description = "";
                    $priceProd = "";
                    $categoryProd = "";
                } else {
                    $message = "Erreur lors de l'enregistrement";
                }
            }
        } else {
            $message = "<h3>Erreur</h3>";
        }
    }

    if (isset($message)) {
        echo "<p>$message</p>";
        if (!empty($errors)) {
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li style='color: red'>$error</li>";
            }
            echo "</ul>";
        }
    }
    ?>
    <form method="POST">
        <fieldset>
            <div class="form-group row">
                <label for="codeProd" class="col-sm-10 col-form-label">Code produit :</label><br>
                <input class="form-control" type="text" id="codeProd" name="codeProd" value="<?php echo $codeProd ?>"
                       minlength="6"
                       maxlength="6">
            </div>

            <div class="form-group row">
                <label for="nomProd" class="col-sm-10 col-form-label">nom du produit :</label><br>
                <input class="form-control" type="text" id="nomProd" name="nomProd" value="<?php echo $nomProd ?>"
                       minlength="5">
            </div>

            <div class="form-group row">
                <label for="descProd" class="col-sm-10 col-form-label">Description du produit :</label><br>
                <textarea class="form-control" id="descProd" name="descProd"><?php echo $description ?></textarea>
            </div>

            <div class="form-group row">
                <label for="priceProd" class="col-sm-10 col-form-label">Prix du produit :</label><br>
                <input class="form-control" id="priceProd" type="number" step="0.01" name="priceProd"
                       value="<?php echo $priceProd ?>">
            </div>

            <div class="form-group row">
                <label for="category" class="col-sm-10 col-form-label">Categorie: </label><br>
                <select class="form-control" name="category" id="category">
                    <?php


                    foreach ($categories as $category) {
                        if ($category['id'] == $categoryProd) {
                            echo "<option selected value='{$category['id']}'> {$category['nom']}</option>";
                        } else {
                            echo "<option value='{$category['id']}'> {$category['nom']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Enregister</button>
        </fieldset>
    </form>
    <?php
    include "footer.php";

} else {
    header('Location: login.php?login=error');
}
