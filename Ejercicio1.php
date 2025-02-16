<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<?php
    session_start();

    if (!isset($_SESSION['name'])) {
        $_SESSION['name'] = "[No name]";
    }
    if (!isset($_SESSION['product'])) {
        $_SESSION['product'] = [];
    }

    $message = "";
    $error = "";
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['reset'])) {
            session_unset();
            $_SESSION['name'] = "[No name]";
            $_SESSION['product'] = [];
            $message = "Inventory restarted";
        }

        if (!empty($_POST['username'])) {
            $_SESSION['name'] = htmlspecialchars($_POST['username']);
            $message = "Updated username to ". $_SESSION['name'] . "<br><br>";
        } 
        
        if (!empty($_POST['product']) && !empty($_POST['quantity'])) {
            $product = htmlspecialchars($_POST['product']);
            $quantity = (int) $_POST['quantity'];

            if (isset($_POST['add'])) {
                if (isset($_SESSION['product'][$product])) {
                    $_SESSION['product'][$product] += $quantity;
                    $message .= "Added $quantity units to $product";
                } else {
                    $_SESSION['product'][$product] = $quantity;
                    $message .= "Added new product $product with $quantity units";
                }
            }

            if (isset($_POST['remove'])) {
                if (isset($_SESSION['product'][$product])) {
                    $_SESSION['product'][$product] -= $quantity;
                    $message = "Removed $quantity units to $product";

                    if ($_SESSION['product'][$product] <= 0) {
                        if ($_SESSION['product'][$product] < 0) {
                            $error = "Quantity trying to remove to " . $product . " is greater than existent. Removing product instead";
                        } else {
                            $message = "Removed product $product";
                        }
                        unset($_SESSION['product'][$product]);
                    }
                } else {
                    $error = $product . " is not on inventory, can't remove quantity";
                }
            }
        }
    }
?>

<body>
    <form method="post" action="">
        <h1>Supermarket management</h1>
        <label for="">Worker name:</label>
        <input type="text" name="username">
        <br>

        <h2>Choose product:</h2>
        <select name="product" id="product">
            <option value="" disabled selected></option>
            <option value="Scissors">Scissors</option>
            <option value="Pen">Pen</option>
            <option value="Eraser">Eraser</option>
        </select>
        <br>

        <h2>Product quantity:</h2>
        <input type="number" min="0" name="quantity">
        <br><br>
        <input type="submit" value="add" name="add">
        <input type="submit" value="remove" name="remove">
        <input type="reset" value="clear">
        <input type="submit" value="reset inventory" name="reset"> 
        <br>

        <p style="color: green"><?php echo $message ?></p>
        <p style="color: red"><?php echo $error ?></p>

        <h2>Inventory:</h2>
        <p>worker: <?php echo $_SESSION["name"] ?></p>

        <?php 
        if (!empty($_SESSION['product'])) {
            foreach ($_SESSION['product'] as $product => $quantity) {
                echo "units " . $product . ": " . $quantity . "<br><br>";
            }
        } 
        ?>
    </form>
</body>

<?php
 
?>
