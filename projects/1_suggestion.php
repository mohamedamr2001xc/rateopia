<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Suggestion Page</title>
</head>

<body>
    <?php include 'user_navbar.php'; ?>
    <div class="container">
        <form name="sugform" action="includes/suggestion-inc.php" method="post">
            <div class="form__input-group">
                <legend>Add Suggestion:</legend>
                <input type="text" name="type" class="form__input" autofocus placeholder="Name...">
                <input type="text" name="details" class="form__input" autofocus placeholder="Details...">
                <input class="sugbtn" type="submit" value="Add Suggestion" name="submit">
            </div>
        </form>
    </div>
</body>

</html>