<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post">
        <h1>Modify array saved in session</h1>

        <label for="position">Position to modify: </label>
        <select name="position" id="position">
            <option value="0">0</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
        <br><br>

        <label for="value">New value: </label>
        <input type="number" name="value" id="value" value="0">
        <br><br>

        <input type="submit" name="modify" value="Modify">
        <input type="submit" name="average" value="Average">
        <input type="reset" value="Clear">
        <input type="submit" name="reset" value="Reset array">
        <br><br>
    </form>
</body>
</html>

<?php
    session_start();

    if (!isset($_SESSION['array'])) {
        $_SESSION['array'] = array(10, 20, 30);
    } 

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['reset'])) {
            $_SESSION['array'] = array(10, 20, 30);
        }

        if (isset($_POST['modify']) && isset($_POST['value'])) {
            $position = (int) $_POST['position'];
            $value = (int) $_POST['value'];
            $_SESSION['array'][$position] = $value;
        }
    }

    echo "Current array:" . implode(", ", $_SESSION['array']);

    if (isset($_POST['average'])) {
        $sum = array_sum($_SESSION['array']);
        $count = count($_SESSION['array']);
        echo "<br><br> Average: " . (double) ($sum / $count);
    }    
?>