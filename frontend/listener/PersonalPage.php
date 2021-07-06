<?php
  session_start();
  include '../../APIs/control/Dependencies.php';
  include '../../APIs/listener/PersonalPageFunctions.php';
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
                <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="listener.php" onclick='window.location.reload();'>
                    HASSNER
                </a>
        </div>
    </header>
  <main>

    <section class="middle-card">
      <div class="name">
        <h1><?php echo $_SESSION['username'];?></h1>
      </div>
      <h1 id="h1-sm">About</h1>
      <p>Year-long growth. Most invested artists.</p>
    </section>
    <section class="middle-card">
      <h1 id="h1-sm">Email Address</h1>
      <p style="color: #ff9100"><?php 
        $account_info = getAccount($_SESSION['username']);
        printUserImportantInfo($account_info['email']);
      ?>
      <a href="../../APIs/listener/EditEmailBackend.php" id="icon-btn"><i class="fa fa-edit"></i></a>
      <?php
      if($_SESSION['edit'] == 2)
      {
        echo '
            <form action="../../APIs/listener/UpdateEmailBackend.php" method="post">
              <div class="form-group">
                <input type="text" name = "email_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new email address">
              </div>
              <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
              </div>
            </form>
        ';
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
      <p>
        <?php
          printUserImportantInfo($account_info['password']); 
        ?>
      </p> 
      <a href="../../APIs/listener/EditPasswordBackend.php" id="icon-btn"><i class="fa fa-edit"></i></a>
      <?php
        if($_SESSION['edit'] == 1)
        {
          echo '
              <form action="../../APIs/listener/UpdatePasswordBackend.php" method="post">
                <div class="form-group">
                  <input type="password" name = "pwd_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new password">
                </div>
                <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                  <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
                </div>
              </form>
          ';
        }
      ?>
    </section>

    <section class="middle-card">
      <h1 id="h1-sm">Payment info</h1>
      <p><i style="color: white;" class="fa fa-user"></i> Name on card: <?php
        echo $account_info['Full_name'];
      ?></p>
    </section>

    <section class="middle-card">
    <p><i class="far fa-credit-card"></i> Card number: <?php
      printUserImportantInfo($account_info['Card_number']);
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