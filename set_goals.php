<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signup.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Goals - 254Fit</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Set Your Goals</h1>
    <form action="save_goals.php" method="post">
        <label for="calorie_goal">Calorie Goal (kcal):</label>
        <input type="number" id="calorie_goal" name="calorie_goal" required>
        <label for="protein_goal">Protein Goal (g):</label>
        <input type="number" id="protein_goal" name="protein_goal" required>
        <label for="carbs_goal">Carbs Goal (g):</label>
        <input type="number" id="carbs_goal" name="carbs_goal" required>
        <label for="fats_goal">Fats Goal (g):</label>
        <input type="number" id="fats_goal" name="fats_goal" required>
        <label for="weight_goal">Weight Goal (kg):</label>
        <input type="number" id="weight_goal" name="weight_goal" required>
        <button type="submit">Save Goals</button>
    </form>
</body>
</html>
