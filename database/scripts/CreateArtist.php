<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Create Artist Script</title>

  <link rel="stylesheet" href="../../frontend/css/default.css" id="theme-color">
  <link rel="stylesheet" href="../../frontend/css/menu.css" id="theme-color">
</head>

<body>
    <div class="text-center">
        <h5>Artist Email</h5>
        <input type="text" id="script_artist_email" placeholder="Email">
        <h5>Artist Username</h5>
        <input type="text" id="script_artist_username" placeholder="Username">
        <h5>Password</h5>
        <input type="password" id="script_artist_password" placeholder="Password">
        <h5>Market Tag</h5>
        <input type="text" id="script_artist_tag" placeholder="market tag"></br>
        <input id="script_create_artist_btn" class="btn btn-primary" type = "submit" role="button" value = "Create">
        <p id="create_artist_status"></p>

        <a class="py-4" href = "scriptsMain.php">Return to main scripts page</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="js/CreateArtist.js"></script>
</body>

</html>