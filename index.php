<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire PHP</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Allan:wght@400;700&family=Funnel+Sans:ital,wght@0,300..800;1,300..800&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Noto+Sans+Symbols:wght@100..900&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Space+Mono:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/validator@13.6.0/validator.min.js"></script>

</head>
<body>
    <form id="myForm" method="post" action="process_form.php" onsubmit="return validateForm()" class="form">
        <p class="title">Form </p>
        <p class="message">Complete this form please</p>
        <div class="flex">
            <label for="name">
                <input type="text" id="name" name="name" class="input" placeholder="" required>
                <span>Name</span>
            </label>
            
            <label for="firstName">
                <input type="text" id="firstName" name="firstName" class="input" placeholder="" required>
                <span>Firstname</span>
            </label>
        </div>
        
        <label for="mail">
            <input type="mail" id="mail" name="mail" class="input" placeholder="" required>
            <span>Email</span>
        </label>
        
        <label for="url">
            <input type="url" id="url" name="url" placeholder="" class="input" required>
            <span>Enter the image URL</span>
        </label>
        
        <label for="description">
            <input type="text" id="description" name="description" class="input" placeholder="" required>
            <span>Description</span>
        </label>
        
        <button class="submit" type="submit">Submit</button>
    </form>
    <script src="js/script.js"></script> 
</body>
</html>
