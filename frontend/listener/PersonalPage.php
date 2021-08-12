<?php
  include '../../APIs/control/Dependencies.php';
  include '../../APIs/shared/PersonalPageFunctions.php';
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">

  <!-- Title of the page -->
  <title><?php echo $_SESSION['username'];?> Page</title>

  <!-- Link to grab icons  -->
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

    <!-- About section of user -->
    <section class="middle-card">
      <div class="name">
        <h1><?php echo $_SESSION['username'];?></h1>
      </div>
      <h1 id="h1-sm">About</h1>
      <p>Year-long growth. Most invested artists.</p>
    </section>

    <!-- Email address of user -->
    <section class="middle-card">
      <h1 id="h1-sm">Email Address</h1>
      <p style="color: #ff9100"><?php 
        //variable to hold all information of user's account info (i.e all columns of account table in database corresponds to this user)
        $account_info = getAccount($_SESSION['username']);
        printUserImportantInfo($account_info['email']);
      ?>
      <!-- Brings to a page that allows user to edit their email address -->
      <a href="../../APIs/shared/EditEmailBackend.php" id="icon-btn"><i class="fa fa-edit"></i></a>
      <?php
      //If they click on the edit button, prompt a textfield that allows user to enter new email and save it
      if($_SESSION['edit'] == 2)
      {
        echo '
            <form action="../../APIs/shared/UpdateEmailBackend.php" method="post">
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

    <!-- Region section of personal page, for now only Canada region is available -->
    <section class="middle-card">
      <h1 id="h1-sm">Country/Region</h1>
      <p>Canada</p>
    </section>

    <!-- Displaying the username of user in personal page -->
    <section class="middle-card">
      <h1 id="h1-sm">Username</h1>
      <p><?php echo $_SESSION['username'];?></p>
    </section>

    <!-- Display password of personal page, with first and last characters the only visible characters -->
    <section class="middle-card">
      <h1 id="h1-sm">Password</h1>
      <p>
        <?php
          printUserImportantInfo($account_info['password']); 
        ?>
      </p> 
      <!-- Brings to a page that allows user to edit their password -->
      <a href="../../APIs/shared/EditPasswordBackend.php" id="icon-btn"><i class="fa fa-edit"></i></a>
      <?php
      //If they click on the edit button, prompt a textfield that allows user to enter new password and save it
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

    <!-- This section displays payment info (buy siliqas) of personal page -->

    <!-- Displaying name on card of the user -->
    <section class="middle-card">
      <h1 id="h1-sm">Payment info</h1>
      <p><i style="color: white;" class="fa fa-user"></i> Name on card: <?php
        echo $account_info['Full_name'];
      ?></p>
    </section>

    <!-- Displaying card number of personal page -->
    <section class="middle-card">
    <p><i class="far fa-credit-card"></i> Card number: <?php
      printUserImportantInfo($account_info['Card_number']);
    ?></p>
    </section>

    <!-- Displaying billing address of personal page -->
    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-map-marker-alt"></i> Billing Address: <?php
        echo $account_info['billing_address'];
      ?></p>
    </section>
    
    <!-- Displaying billing info of personal page -->

    <!-- Displaying city -->
    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-location-arrow"></i> City: <?php
        echo $account_info['City'];
      ?></p>
    </section>

    <!-- Displaying state -->
    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-archway"></i> State: <?php
        echo $account_info['State'];
      ?></p>
    </section>

    <!-- Displaying zip code -->
    <section class="middle-card">
      <p><i style="color: white;" class="fas fa-align-justify"></i> Zip: <?php
        echo $account_info['ZIP'];
      ?></p>
    </section>

    <!-- This section displays deposit info (sell siliqas) of personal page -->

    <!-- Displaying transit number -->
    <section class="middle-card">
      <h1 id="h1-sm">Deposit info</h1>
      <p><i class="fas fa-dolly-flatbed"></i> Transit No. : <?php
        echo $account_info['Transit_no'];
      ?></p>
    </section>

    <!-- Displaying institution number -->
    <section class="middle-card">
      <p><i class="fas fa-project-diagram"></i> Institution No. : <?php
        echo $account_info['Inst_no'];
      ?></p>
    </section>
    
    <!-- Displaying Account number -->
    <section class="middle-card">
      <p><i class="fas fa-wallet"></i> Account No. : <?php
        echo $account_info['Account_no'];
      ?></p>
    </section>

    <!-- Displaying swift code -->
    <section class="middle-card">
      <p><i class="fas fa-wind"></i> Swift/BIC Code : <?php
        echo $account_info['Swift'];
      ?></p>
    </section>

  </main>
<script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</body>