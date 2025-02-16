
<!DOCTYPE html>
<html>

<head>
    <title>Shopping list</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 5px;
        }

        input[type=submit] {
            margin-top: 10px;
        }
    </style>
</head>

<?php
    if (!isset($_SESSION)) {
        session_start();
    }

    $error = "";    
    $message = "";
    $name = "";
    $quantity = "";
    $price = "";
    $totalValue = 0;
    
    if (!isset($_SESSION['list'])) {
        $_SESSION['list'] = [];
        
    }

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['reset'])) {
            session_unset();
            session_destroy();
            $totalValue = 0;
        } 

        if (isset($_POST['add'])) {
            if (empty($_POST['name'])) {
                $error = "Name not specified";
            }elseif (empty($_POST['quantity'])) {
                $error = "Quantity not specified";
            }elseif (empty($_POST['price'])) {
                $error = "Price not specified";
            }

            $name = ucfirst(strtolower($_POST['name']));
            
            foreach($_SESSION['list'] as $index => $item) {
                if ($item['name'] == $name) {
                    $error = "Item already on list";
                    break;
                }
            }

            if (empty($error)) {
                $item = [];
                $item['name'] = $name;
                $item['quantity'] = (int) $_POST['quantity'];
                $item['price'] = (float) $_POST['price'];
                $_SESSION['list'][] = $item;

                $message = "Item added succesfully";
            }
        }
        
        if (isset($_POST['update'])) {
            if (empty($_POST['name'])) {
                $error = "Name not specified";
            }elseif (empty($_POST['quantity'])) {
                $error = "Quantity not specified";
            }elseif (empty($_POST['price'])) {
                $error = "Price not specified";
            }

            if (empty($error)) {
                foreach ($_SESSION['list'] as $index => $item) {
                    if ($item['name'] == ucfirst(strtolower($_POST['name']))) {
                        $_SESSION['list'][$index]['quantity'] = (int) $_POST['quantity'];
                        $_SESSION['list'][$index]['price'] = (float) $_POST['price'];
    
                        $message = "Item updated properly";
                        break;
                    }
                }
            }

            if (empty($message) && empty($error)) {
                $error = "Item not on list";
            }
        }

        if (isset($_POST['total'])) {
            foreach($_SESSION['list'] as $item) {
                $totalValue += $item['quantity'] * $item['price'];

                $message = "Total cost calculated";
            }
        }

        if (isset($_POST['edit'])) {
            $name = $_POST['name'];
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];

            $message = "Item selected properly";
        }

        if (isset($_POST['delete'])) {
            foreach ($_SESSION['list'] as $index => $item) {
                if ($item['name'] == $_POST['name']) {
                    unset($_SESSION['list'][$index]);
                }
            }

            $message = "Item deleted properly";
        }
    }
?>

<body>
    <h1>Shopping list</h1>
    <form method="post">
        <label for="name">name:</label>
        <input type="text" name="name" id="name" value="<?php echo $name; ?>">
        <br>
        <label for="quantity">quantity:</label>
        <input type="number" name="quantity" id="quantity" min="0" value="<?php echo $quantity; ?>">
        <br>
        <label for="price">price:</label>
        <input type="number" name="price" id="price" min="0" value="<?php echo $price; ?>">
        <br>
        <input type="hidden" name="index">
        <input type="submit" name="add" value="Add">
        <input type="submit" name="update" value="Update">
        <input type="reset" value="Clear">
        <input type="submit" name="reset" value="Reset shop list">
    </form>
    <p style="color:red;"><?php echo $error; ?></p>
    <p style="color:green;"><?php echo $message; ?></p>
    <table>
        <thead>
            <tr>
                <th>name</th>
                <th>quantity</th>
                <th>price</th>
                <th>cost</th>
                <th>actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($_SESSION['list'])) { foreach($_SESSION['list'] as $index => $item) { ?>
                <tr>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td><?php echo $item['quantity'] * $item['price']; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="name" value="<?php echo $item['name']; ?>">
                            <input type="hidden" name="quantity" value="<?php echo $item['quantity']; ?>">
                            <input type="hidden" name="price" value="<?php echo $item['price']; ?>">
                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                            <input type="submit" name="edit" value="Edit">
                            <input type="submit" name="delete" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php }} ?>
            <tr>
                <td colspan="3" align="right"><strong>Total:</strong></td>
                <td><?php echo $totalValue; ?></td>
                <td>
                    <form method="post">
                        <input type="submit" name="total" value="Calculate total">
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
</body>




