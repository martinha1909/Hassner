<?php
  session_start();
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">
  <title><?php echo $_SESSION['username'];?> Page</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="css/app.css" type="text/css">
  <link rel="stylesheet" href="css/default.css" type="text/css">
</head>
<body class="bg-dark">
    <header class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-orange d-flex justify-content-between">
                <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="listener.php" onclick='window.location.reload();'>
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

    <section class="middle-card">
    <div class="name">
        <h1><?php echo $_SESSION['username'];?></h1>
      </div>
      <h1 id="h1-sm">About</h1>
      <p>Write Something. Year-long growth. Most invested artists.</p>
    </section>
    <section class="middle-card">
      <h1 id="h1-sm">Email Address</h1>
      <p style="color: #ff9100"><?php 
        include '../APIs/logic.php';
        include '../APIs/connection.php';
        $conn = connect();
        $result = searchAccount($conn, $_SESSION['username']);
        $account_info = $result->fetch_assoc();
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
      ?><a href="../APIs/EditEmail.php" id="icon-btn"><i class="fa fa-edit"></i></a>
      <?php
      if($_SESSION['edit'] == 2)
      {
        echo '<form action="../APIs/EditEmailConnection.php" method="post">';
        echo '<div class="form-group">
          <input type="text" name = "email_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new email address">
        </div>';
        echo '<div class="col-md-8 col-12 mx-auto pt-5 text-center">
        <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
    </div>';
        echo '</form>';
      }
      ?>
    </section>
    <section class="middle-card">
      <h1 id="h1-sm">Country/Region</h1>
      <p>Canada</p>
    </section>
    <section class="middle-card">
      <h1 id="h1-sm">Username</h1>
      <p><?php echo $_SESSION['username'];?></p>
    </section>
    <section class="middle-card">
      <h1 id="h1-sm">Password</h1>
      <p><?php 
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
      ?> <a href="../APIs/EditPassword.php" id="icon-btn"><i class="fa fa-edit"></i></a>
      <?php
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
      
      ?>
    </section>

    <section class="middle-card">
      <h1 id="h1-sm">Payment info</h1>
      <p><i style="color: white;" class="fa fa-user"></i> Name on card: <?php
        $result = searchAccount($conn, $_SESSION['username']);
        $account_info = $result->fetch_assoc();
        echo $account_info['Full_name'];
      ?></p>
    </section>

    <section class="middle-card">
    <p><i class="far fa-credit-card"></i> Card number: <?php
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
      ?></p>
    </section>

    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-map-marker-alt"></i> Billing Address: <?php
        echo $account_info['billing_address'];
      ?></p>
    </section>

    <section class="middle-card">
      <p><i style="color: white;" class="fa fa-envelope"></i> Email: <?php
        echo $account_info['email'];
      ?></p>
    </section>

    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-location-arrow"></i> City: <?php
        echo $account_info['City'];
      ?></p>
    </section>

    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-archway"></i> State: <?php
        echo $account_info['State'];
      ?></p>
    </section>

    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-align-justify"></i> Zip: <?php
        echo $account_info['ZIP'];
      ?></p>
    </section>

    <section class="middle-card">
      <h1 id="h1-sm">Deposit info</h1>
      <p><i class="fas fa-dolly-flatbed"></i> Transit No. : <?php
        echo $account_info['Transit_no'];
      ?></p>
    </section>

    <section class="middle-card">
      <p><i class="fas fa-project-diagram"></i> Institution No. : <?php
        echo $account_info['Inst_no'];
      ?></p>
    </section>
    
    <section class="middle-card">
      <p><i class="fas fa-wallet"></i> Account No. : <?php
        echo $account_info['Account_no'];
      ?></p>
    </section>

    <section class="middle-card">
      <p><i class="fas fa-wind"></i> Swift/BIC Code : <?php
        echo $account_info['Swift'];
      ?></p>
    </section>

  </main>
<script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</body>