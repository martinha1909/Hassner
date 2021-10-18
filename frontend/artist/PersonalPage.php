<?php
include '../../backend/control/Dependencies.php';
include '../../backend/shared/PersonalPageHelpers.php';
include '../../backend/constants/StatusCodes.php';
include '../../backend/constants/LoggingModes.php';
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">

  <title><?php echo $_SESSION['username']; ?> Page</title>

  <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/app.css" type="text/css">
  <link rel="stylesheet" href="../css/default.css" id="theme-color">
  <link rel="stylesheet" href="../css/menu.css" id="theme-color">
</head>

<body class="bg-dark">
  <header class="smart-scroll">
    <div class="container-xxl">
      <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan">
        <a class="navbar-brand heading-black" href="Artist.php" onclick='window.location.reload();'>
          HASSNER
        </a>
      </nav>
    </div>
  </header>
  <main>

    <?php
    $account_info = getAccount($_SESSION['username']);
    echo '
            <section class="middle-card text-center">
              <div class="name">
                  <h1 class="h1-blue">' . $_SESSION['username'] . '</h1>
              </div>
            </section>
          ';
    echo '
            <section class="middle-card">
              <h1 id="h1-sm">Email Address</h1>
              <p>
        ';
    printUserImportantInfo($account_info['email']);
    echo '<a href="../../backend/shared/EditEmailBackend.php" id="icon-btn">&nbsp<i class="fa fa-pen"></i></a>';
    if ($_SESSION['edit'] == 2) {
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
    echo '
            </section>
            <section class="middle-card">
            <h1 id="h1-sm">Country/Region</h1>
            <p>Canada</p>
            </section>
            <section class="middle-card">
            <h1 id="h1-sm">Username</h1>
            <p>' . $_SESSION['username'] . '</p>
            </section>
            <section class="middle-card">
            <h1 id="h1-sm">Password</h1>
            <p>
        ';
    printUserImportantInfo($account_info['password']);
    echo '<a href="../../backend/shared/EditPasswordBackend.php" id="icon-btn">&nbsp<i class="fa fa-pen"></i></a>';
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
    echo '</section>';
    ?>
    <section class="middle-card">
      <h1 id="h1-sm">Payment info</h1>
      <p><i class="fa fa-user"></i> Name on card: <?php
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
      <p><i class="fas fa-map-marker-alt"></i> Billing Address: <?php
                                                                echo $account_info['billing_address'];
                                                                ?></p>
    </section>

    <!-- Displaying billing info of personal page -->

    <!-- Displaying city -->
    <section class="middle-card">
      <p><i class="fas fa-location-arrow"></i> City: <?php
                                                      echo $account_info['City'];
                                                      ?></p>
    </section>

    <!-- Displaying state -->
    <section class="middle-card">
      <p><i class="fas fa-archway"></i> State: <?php
                                                echo $account_info['State'];
                                                ?></p>
    </section>

    <!-- Displaying zip code -->
    <section class="middle-card">
      <p><i class="fas fa-align-justify"></i> Zip: <?php
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
    <section class="middle-card py-2"></section>
  </main>
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
</body>