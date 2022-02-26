<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Available Scripts</title>

  <link rel="stylesheet" href="../../frontend/css/default.css" id="theme-color">
  <link rel="stylesheet" href="../../frontend/css/menu.css" id="theme-color">
</head>

<body>
    <div class="text-center">
        <div id="hidden_after_verified">
            <input type="password" id="scripts_admin_pwd" placeholder="Password">
            <input id="admin_verify_btn" type = "submit" role="button" value = "Verify">
            <p class="error-msg" id="admin_verify_err"></p>
        </div>

        <div id="verified_content" class="div-hidden">
            <h3 class="h3-blue">Available Scripts</h3>
            <a class="py-4" href = "PopulateUserBalance.php">Reset user balance</a></br>
            <a class="py-4" href = "CreateArtist.php">Create an Artist</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="js/VerifyAdmin.js"></script>
</body>

</html>