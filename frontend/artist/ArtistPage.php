<?php
  session_start();
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">
  <title><?php echo $_SESSION['username'];?> Page</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/app.css" type="text/css">
  <link rel="stylesheet" href="../css/default.css" type="text/css">
</head>
<body class="bg-dark">
    <header class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-orange d-flex justify-content-between">
                <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="../artist.php" onclick='window.location.reload();'>
                    HASSNER
                </a>
        </div>
    </header>
  <main>
    <!-- <section class="top-card">
      <img src="Images/account.png" alt="user picture">
      <div class="menu-icon">
        <div class="menu item1"></div>
        <div class="menu item2"></div>
      </div>
    </section> -->
    <?php
      if($_SESSION['notify'] == 2)
        echo "<script>alert('Please enter input fields with numbers');</script>";
      $_SESSION['notify'] = 0;
      include '../../APIs/logic.php';
      include '../../APIs/connection.php';
      $conn = connect();
      $query = searchAccount($conn, $_SESSION['username']);
      $account_info = $query->fetch_assoc();
      if($account_info['Share_Distributed'] == 0)
      {
        echo '<form action="../../APIs/artist/ArtistDistributeShare.php" method="post">

                  <!-- username field -->
                  <div class="form-group">
                    <h5>How many shares are you distributing?</h5>
                    <input name = "distribute_share" type="text" style="border-color: white;" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter amount of share">
                  </div>

                  <!-- password field -->
                  <div class="form-group">
                    <h5>Price per share</h5>
                    <input name = "price_per_share" type="text" style="border-color: white;" class="form-control" id="exampleInputPassword1" placeholder="Enter amount">
                  </div>


                  <!-- login button -->
                  <!-- TODO: login button functionality-->
                  <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                    <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Continue">
                  </div>
              </form>';
      }
      else
      {
        echo '<section class="middle-card">
        <div class="name">
            <h1>'.$_SESSION['username'].'</h1>
          </div>
        </section>';
        echo '<section class="middle-card">
        <h1 id="h1-sm">Email Address</h1>
        <p style="color: #ff9100">';
        $chars = str_split($account_info['email']);
        echo '<p>';
        $i = 0;
        foreach($chars as $char)
        {
          if($i == 0 || $i == sizeof($chars)-1)
            echo $char;
          else
           echo '*';
          $i++;
        }
        echo '</p>';
        echo '<a href="../../APIs/artist/EditEmailArtist.php" id="icon-btn"><i class="fa fa-edit"></i></a>';
        if($_SESSION['edit'] == 2)
        {
          echo '<form action="../../APIs/artist/EditEmailArtistConnection.php" method="post">';
          echo '<div class="form-group">
            <input type="text" name = "email_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new email address">
          </div>';
          echo '<div class="col-md-8 col-12 mx-auto pt-5 text-center">
          <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
          </div>';
          echo '</form>';
        }
        echo '</section>';
        echo '<section class="middle-card">
                <h1 id="h1-sm">Country/Region</h1>
                <p>Canada</p>
              </section>';
        echo '<section class="middle-card">
                <h1 id="h1-sm">Username</h1>
                  <p>'.$_SESSION['username'].'</p>
              </section>';
        echo '<section class="middle-card">
                <h1 id="h1-sm">Password</h1>
                  <p>';
        $chars = str_split($account_info['password']);
        echo '<p>';
        $i = 0;
        foreach($chars as $char)
        {
          if($i == 0 || $i == sizeof($chars)-1)
            echo $char;
          else
           echo '*';
          $i++;
        }
        echo '</p>';
        echo '<a href="../APIs/EditPassword.php" id="icon-btn"><i class="fa fa-edit"></i></a>';
        if($_SESSION['edit'] == 1)
        {
          echo '<form action="../APIs/EditPasswordConnection.php" method="post">';
          echo '<div class="form-group">
            <input type="password" name = "pwd_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new password">
          </div>';
          echo '<div class="col-md-8 col-12 mx-auto pt-5 text-center">
                  <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
              </div>';
          echo '</form>';
        }
        echo '</section>';
        echo '<section class="middle-card">
                <h1 id="h1-sm">Payment info</h1>
                <p><i style="color: white;" class="fa fa-user"></i> Name on card: <?php
                  '.$account_info['Full_name'].'
                ?></p>
              </section>';
        echo '<section class="middle-card">
                <p><i class="far fa-credit-card"></i> Card number:';
        $chars = str_split($account_info['Card_number']);
        echo '<p>';
        $i = 0;
        foreach($chars as $char)
        {
          if($char=="-" || $i == sizeof($chars)-1 || $i == sizeof($chars)-2 || $i == sizeof($chars) -3 || $i == sizeof($chars) -4)
            echo $char;
          else
            echo "*";
          $i++;
        }
        echo '</p>';
        echo '</section>';
        echo '

              <section class="middle-card">
                <p><i style="color: white;" class="fas fa-map-marker-alt"></i> Billing Address: </p>
              </section>
          
              <section class="middle-card">
                <p><i style="color: white;" class="fa fa-envelope"></i> Email: </p>
              </section>
          
              <section class="middle-card">
                <p><i style="color: white;" class="fas fa-location-arrow"></i> City: </p>
              </section>
          
              <section class="middle-card">
                <p><i style="color: white;" class="fas fa-archway"></i> State: </p>
              </section>
          
              <section class="middle-card">
                <p><i style="color: white;" class="fas fa-align-justify"></i> Zip: </p>
              </section>
          
              <section class="middle-card">
                <h1 id="h1-sm">Deposit info</h1>
                <p><i class="fas fa-dolly-flatbed"></i> Transit No. : </p>
              </section>
          
              <section class="middle-card">
                <p><i class="fas fa-project-diagram"></i> Institution No. : </p>
              </section>
              
              <section class="middle-card">
                <p><i class="fas fa-wallet"></i> Account No. : </p>
              </section>
          
              <section class="middle-card">
                <p><i class="fas fa-wind"></i> Swift/BIC Code :</p>
              </section>';
      }
    ?>

  </main>
<script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</body>