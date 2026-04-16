<?php
$name = "";
$message = "";
$age = 0;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["my_name"];
    $age = $_POST["my_age"];
    if($name == "Jáchym") {
        $message = "Ahoj Jáchyme";
        $age = $_POST["my_age"];
    } else {
        $message = "Neznám tě";
    }
}  
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test PHP</title>
</head>
<body>
    <h1>Test formuláře</h1>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores esse explicabo eaque? Debitis nihil beatae temporibus enim nesciunt dolorum iusto velit accusantium, officia sit suscipit similique fugit maxime blanditiis autem!</p>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nulla dolore eum possimus perferendis fuga animi! Tenetur corrupti eveniet similique saepe vitae dolorum maiores pariatur, natus nisi illum a, esse dolore.</p>
    <form method="post">
        <input type="text" name="my_name" placeholder="Zadejte jméno">
        <input type="number" name="my_age" placeholder="Zadejte váš věk">
        <button type="submit">Odeslat</button>
    </form>

    <p>
        <?php echo $message; ?>
    </p>

    <p>
        <?php 
        if($age > 0){
            echo "Tvj věk: $age";
        }
        ?>
    </p>

</body>
</html>