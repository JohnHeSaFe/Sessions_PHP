<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
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

        <h2>Inventory:</h2>
    </form>
</body>

<?php
    if (!isset($_SESSION)) {
        session_start();
    }
    
    if (!isset($_SESSION['name'])) {
        $_SESSION['name'] = "[No name]";
    }
    
    if (!isset($_SESSION['product'])) {
        $_SESSION['product'] = [];
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['reset'])) {
            session_unset();
            session_destroy();
            exit;
        }

        if (!empty($_POST['username'])) {
            $_SESSION['name'] = htmlspecialchars($_POST['username']);
        } 
        
        if (!empty($_POST['product']) && !empty($_POST['quantity'])) {
            $product = htmlspecialchars($_POST['product']);
            $quantity = (int) $_POST['quantity'];

            if (isset($_POST['add'])) {
                if (isset($_SESSION['product'][$product])) {
                    $_SESSION['product'][$product] += $quantity;
                } else {
                    $_SESSION['product'][$product] = $quantity;
                }
            }

            if (isset($_POST['remove']) && isset($_SESSION['product'][$product])) {
                $_SESSION['product'][$product] -= $quantity;

                if ($_SESSION['product'][$product] <= 0) {
                    unset($_SESSION['product'][$product]);
                }
            } 
        }
    }

    if (!empty($_SESSION['product'])) {
        echo "worker: " . $_SESSION['name'] . "<br><br>";
        foreach ($_SESSION['product'] as $product => $quantity) {
            echo "units " . $product . ": " . $quantity . "<br><br>";
        }
    }  
?>
