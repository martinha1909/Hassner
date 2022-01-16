<?php
include '../../backend/control/Dependencies.php';
include '../../backend/constants/Currency.php';

$account_info = getAccount($_SESSION['username']);
$_SESSION['expmonth'] = 0;
$_SESSION['expyear'] = 0;
?>

<link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">
  <title>Checkout</title>
  <link rel="icon" href="../../frontend/Images/hx_tmp_2.ico" type="image/ico">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/checkout.css" type="text/css">
  <link rel="stylesheet" href="../css/default.css" type="text/css">
  <link rel="stylesheet" href="../css/menu.css" type="text/css">
</head>

<body class="bg-dark">
  <header class="smart-scroll">
    <div class="container-xxl">
      <nav class="navbar navbar-expand-md navbar-dark bg-darkcyan d-flex">
        <?php
        if ($_SESSION['account_type'] == "user") 
        {
          echo '
                              <a class="navbar-brand" href="../listener/Listener.php">
                                HASSNER
                              </a>
                        ';
        } 
        else if ($_SESSION['account_type'] == "artist") 
        {
          echo '
                              <a class="navbar-brand" href="../artist/Artist.php">
                                HASSNER
                              </a>
                      ';
        }
        ?>
    </div>
  </header>
  <div class="row py-4">
    <div class="col-75">
      <div class="container">
        <form action="../../backend/shared/DepositVerificationBackend.php" method="post">

          <div class="row">

          </div>
          <div class="col-50">
            <h3 class="h3-blue">Sellout</h3>
            <h5 class="text-right"><a href="../../backend/shared/UseSavedAccountInfoBackend.php" onclick='window.location.reload();' class="btn btn-primary py-2">Use saved account info</a></h5>
            <label for="cname">Transit No. : </label>
            <?php
            if ($_SESSION['saved'] == 1) // change this to transit num 
              echo '<input type="text" id="cname" name="transit_no" value=' . $account_info['Transit_no'] . '>';
            else if ($_SESSION['saved'] == 0)
              echo '<input type="text" id="cname" name="transit_no" placeholder="12345">';
            ?>

            <label for="ccnum">Institution No. : </label>
            <?php
            if ($_SESSION['saved'] == 1) // change this to inst num
              echo '<input type="text" id="ccnum" name="inst_no" value=' . $account_info['Inst_no'] . '>';
            else if ($_SESSION['saved'] == 0)
              echo '<input type="text" id="ccnum" name="inst_no" placeholder="123">';
            ?>
            <label for="expmonth">Account No. : </label>
            <?php
            if ($_SESSION['saved'] == 1) // change this to acct num
              echo '<input type="text" id="ccnum" name="account_no" value=' . $account_info['Account_no'] . '>';
            else if ($_SESSION['saved'] == 0)
              echo '<input type="text" id="ccnum" name="account_no" placeholder="12345678">';
            ?>
            <div class="row">
              <div class="col-50">
                <label for="expyear">Swift/BIC Code: </label>
                <?php
                if ($_SESSION['saved'] == 1) // change this to swift/bic num
                  echo '<input type="text" id="ccnum" name="swift" value=' . $account_info['Swift'] . '>';
                else if ($_SESSION['saved'] == 0)
                  echo '<input type="text" id="ccnum" name="swift" placeholder="AAAABBCCDDDD">';
                ?>

              </div>


            </div>
          </div>
          <label>
            <?php
            // change everything to sellout cuz rn its on buyouts
            if ($_SESSION['saved'] == 0 || (empty($account_info['Transit_no']) || empty($account_info['Account_no']) || empty($account_info['Swift'])))
              echo '<input type="checkbox" name="save_info" value="Yes" checked> Save information for later sellouts';
            else
              echo '<input type="checkbox" name="save_info" value="Yes" checked> Update billing information';
            ?>
          </label>
      </div>
      <div class="col-3 mx-auto">
        <input type="submit" value="Continue" class="btn btn-primary">
      </div>
      </form>
    </div>
  </div>
  <div class="col-25">
    <div class="container">
      <h4 class="h3-blue">Payout (<?php echo $_SESSION['currency'] ?>)<span class="price">
          <?php
          if ($_SESSION['currency'] == Currency::USD || $_SESSION['currency'] == Currency::CAD)
            echo "$";
          else if ($_SESSION['currency'] == Currency::EUR)
            echo "€";
          echo $_SESSION['fiat'];
          ?>
          </b></span></h4>
      <p><a>
          <?php echo "USD ";
          ?>
        </a> <span class="price">
          <?php

          echo $_SESSION['usd'];
          ?>
        </span></p>
      <hr>
      <p>Total (USD) <span class="price"><b>
            <?php

            echo $_SESSION['usd'];
            ?>
          </b></span></p>
    </div>
  </div>
  </div>
  <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
</body>