<?php
include '../../backend/control/Dependencies.php';
include '../../backend/shared/include/PersonalPageHelpers.php';
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">

  <!-- Title of the page -->
  <title><?php echo $_SESSION['username']; ?> Page</title>

  <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

  <!-- Link to grab icons  -->
  <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/app.css" type="text/css">
  <link rel="stylesheet" href="../css/default.css" id="theme-color">
  <link rel="stylesheet" href="../css/menu.css" id="theme-color">

</head>

<body class="bg-dark">
  <header class="smart-scroll">
    <div class="container-xxl">
      <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
        <a class="navbar-brand heading-black" href="Listener.php" onclick='window.location.reload();'>
            ❖ HX
        </a>
    </div>
  </header>
  <main>

    <!-- About section of user -->
    <section class="middle-card text-center">
      <div class="name">
        <h1 class="h1-blue"><?php echo $_SESSION['username']; ?></h1>
      </div>
    </section>

    <!-- Email address of user -->
    <section class="middle-card">
      <h1 id="h1-sm">Email Address</h1>
      <p><?php
          //variable to hold all information of user's account info (i.e all columns of account table in database corresponds to this user)
          $account_info = getAccount($_SESSION['username']);
          echo printUserImportantInfo($account_info['email']);
          ?>
        <?php
          if(isTestingPhase(null, null))
          {
            //Brings to a page that allows user to edit their email address
            echo '
                  <a href="../../backend/shared/EditEmailBackend.php" id="icon-btn">&nbsp<i class="fa fa-pen"></i></a>
            ';
            //If they click on the edit button, prompt a textfield that allows user to enter new email and save it
            if ($_SESSION['edit'] == 2) 
            {
              echo '
                <form action="../../backend/shared/UpdateEmailBackend.php" method="post">
                  <div class="form-group py-2">
                    <input type="text" name = "email_edit" class="form-control form-control-sm" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new email address">
                  </div>
                  <div class="text-center">
                    <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
                  </div>
                </form>
              ';
            }
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
      <p><?php echo $_SESSION['username']; ?></p>
    </section>

    <!-- Display password of personal page, with first and last characters the only visible characters -->
    <section class="middle-card">
      <h1 id="h1-sm">Password</h1>
      <p>
        <?php
         echo printUserImportantInfo($account_info['password']);
        ?>
      </p>
      <!-- Brings to a page that allows user to edit their password -->
      <a href="../../backend/shared/EditPasswordBackend.php" id="icon-btn">&nbsp<i class="fa fa-pen"></i></a>
      <?php
      //If they click on the edit button, prompt a textfield that allows user to enter new password and save it
      if ($_SESSION['edit'] == 1) {
        echo '
              <form action="../../backend/shared/UpdatePasswordBackend.php" method="post">
                <div class="form-group py-2">
                  <input type="password" name = "pwd_edit" class="form-control form-control-sm" id="signupUsername" aria-describedby="signupUsernameHelp" placeholder="Enter new password">
                </div>
                <div class="text-center">
                  <input type = "submit" class="my_btn edit-btn" role="button" aria-pressed="true" name = "button" value = "Save">  
                </div>
              </form>
          ';
      }
      ?>
    </section>

    <?php
      if(!$_SESSION['testing_phase'])
      {
        echo '
            <section class="middle-card">
              <h1 id="h1-sm">Payment info</h1>
              <p><i class="fa fa-user"></i> Name on card: '.$account_info['Full_name'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-credit-card"></i> Card number: '.printUserImportantInfo($account_info['Card_number']).'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-map-marker-alt"></i> Billing Address: '.$account_info['billing_address'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-location-arrow"></i> City: '.$account_info['City'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-archway"></i> State: '.$account_info['State'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-align-justify"></i> Zip: '.$account_info['ZIP'].'</p>
            </section>

            <section class="middle-card">
              <h1 id="h1-sm">Deposit info</h1>
              <p><i class="fas fa-dolly-flatbed"></i> Transit No. : '.$account_info['Transit_no'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-project-diagram"></i> Institution No. : '.$account_info['Inst_no'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-wallet"></i> Account No. : '.$account_info['Account_no'].'</p>
            </section>

            <section class="middle-card">
              <p><i class="fas fa-wind"></i> Swift/BIC Code : '.$account_info['Swift'].'</p>
            </section>
        ';
      }
    ?>
    
    <section class="middle-card py-2"></section>
  </main>
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</body>