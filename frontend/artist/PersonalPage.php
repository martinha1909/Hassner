<?php
    include '../../backend/control/Dependencies.php';
    include '../../backend/shared/PersonalPageFunctions.php';
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">
  <title><?php echo $_SESSION['username'];?> Page</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/app.css" type="text/css">
  <link rel="stylesheet" href="../css/default.css" type="text/css">
  <link rel="stylesheet" href="../css/menu.css" id="theme-color">
</head>
<body class="bg-dark">
    <header class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-orange d-flex justify-content-between">
                <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="Artist.php" onclick='window.location.reload();'>
                    HASSNER
                </a>
            </nav>
        </div>
    </header>
  <main>
    <?php
      $account_info = getAccount($_SESSION['username']);
      if($account_info['Share_Distributed'] == 0)
      {
          echo '
                <form action="../../backend/artist/DistributeShareBackend.php" method="post">

                  <div class="form-group">
                    <h5>How much siliqas are you raising</h5>
                    <input name = "siliqas_raising" type="text" style="border-color: white;" class="form-control" id="exampleInputPassword1" placeholder="Enter amount">
                  </div>

                  <div class="form-group">
                    <h5>How many shares are you distributing?</h5>
                    <input name = "distribute_share" type="text" style="border-color: white;" class="form-control" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter amount of share">
                  </div>';
          
          if($_SESSION['logging_mode'] == "SHARE_DIST")
          {
            if($_SESSION['status'] == "NUM_ERR")
            {
              $_SESSION['status'] = "ERROR";
              getStatusMessage("Please enter in number format", "");
            }
            else if($_SESSION['status'] == "EMPTY_ERR")
            {
              $_SESSION['status'] = "ERROR";
              getStatusMessage("Please fill out all fields", "");
            }
          }

          echo '

                  <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                    <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Continue">
                  </div>

                </form>
              ';
      }
      else
      {
        echo '
              <section class="middle-card">
                <div class="name">
                    <h1>'.$_SESSION['username'].'</h1>
                </div>
              </section>
            ';
        echo '
              <section class="middle-card">
                <h1 id="h1-sm">Email Address</h1>
                <p style="color: #ff9100">
          ';
        printUserImportantInfo($account_info['email']); 
        echo '<a href="../../backend/shared/EditEmailBackend.php" id="icon-btn"><i class="fa fa-edit"></i></a>';
        if($_SESSION['edit'] == 2)
        {
          echo '
              <form action="../../backend/shared/UpdateEmailBackend.php" method="post">
                <div class="form-group">
                  <input type="text" name = "email_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new email address">
                </div>
                <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                  <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
                </div>
              </form>
            ';
        }
        echo '
              </section>
              <section class="middle-card">
              <h1 id="h1-sm">Country/Region</h1>
              <p>Canada</p>
              </section>
              <section class="middle-card">
              <h1 id="h1-sm">Username</h1>
              <p>'.$_SESSION['username'].'</p>
              </section>
              <section class="middle-card">
              <h1 id="h1-sm">Password</h1>
              <p>
          ';
        printUserImportantInfo($account_info['password']); 
        echo '<a href="../../backend/shared/EditPasswordBackend.php" id="icon-btn"><i class="fa fa-edit"></i></a>';
        if($_SESSION['edit'] == 1)
        {
          echo '
            <form action="../../backend/shared/UpdatePasswordBackend.php" method="post">
              <div class="form-group">
                <input type="password" name = "pwd_edit" class="form-control form-control-sm" style="border-color: white;" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new password">
              </div>
              <div class="col-md-8 col-12 mx-auto pt-5 text-center">
                <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
              </div>
            </form>
          ';
        }
        echo '</section>';
    ?>
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

    <section class="middle-card">
      <h1 id="h1-sm">Market info</h1>
      <p><i style="color: white;" class="fa fa-user"></i> Share Distributed: <?php
        echo $account_info['Share_Distributed'];
      ?></p>
      <?php
        echo '</section>';
      ?>
    </section>
    <?php
      }
    ?>

  </main>
<script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</body>