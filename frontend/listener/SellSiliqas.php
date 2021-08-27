<?php
  include '../../backend/control/Dependencies.php';
  $_SESSION['expmonth'] = 0;
  $_SESSION['expyear'] = 0;
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=divice-width, initial-scale=1.0">
  <title><?php echo $_SESSION['username'];?> Page</title>
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
  <link rel="stylesheet" href="css/checkout.css" type="text/css">
  <link rel="stylesheet" href="css/default.css" type="text/css">
  <link rel="stylesheet" href="css/menu.css" type="text/css">
</head>

<?php
  $conn = connect();
  $result = searchAccount($conn, $_SESSION['username']);
  $account_info = $result->fetch_assoc();
  if($_SESSION['status'] == "SUCCESS")
    echo "<script>alert('Siliqas Sold Successfully');</script>";
  if($_SESSION['status'] == 2)
    echo "<script>alert('Please fill out all forms');</script>";
  $_SESSION['status'] = 0;
?>
<body class="bg-dark">
    <header class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-orange d-flex justify-content-between">
                <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="listener.php" onclick='window.location.reload();'>
                    HASSNER
                </a>
        </div>
    </header>
<div style="padding-top:50px;" class="row">
  <div class="col-75">
    <div class="container">
      <form action="../backend/DepositConnection.php" method="post">
      
        <div class="row">
          <div class="col-50">
            <h3>Deposit Information</h3>
            <h5 class="text-right"><a href="../backend/UseSavedDepositInfoConnnection.php" onclick='window.location.reload();' class="btn btn-primary py-2">Use saved deposit info</a></h5>
            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <?php
            if($_SESSION['saved'] == 1)
              echo '<input type="text" id="fname" name="firstname" value="'.$account_info['Full_name'].'">';
            else if($_SESSION['saved'] == 0)
              echo '<input type="text" id="fname" name="firstname" placeholder="Full Name">';
            ?>
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <?php
            if($_SESSION['saved'] == 1)
              echo '<input type="text" id="email" name="email" value='.$account_info['email'].'>';
            else if($_SESSION['saved'] == 0)
              echo '<input type="text" id="email" name="email" placeholder="john@example.com">';
            ?>
            <label for="adr"><i class="fas fa-map-marker-alt"></i> Address</label>
            <?php
            if($_SESSION['saved'] == 1)
              echo '<input type="text" id="adr" name="address" value='.$account_info['billing_address'].'>';
            else if($_SESSION['saved'] == 0)
              echo '<input type="text" id="adr" name="address" placeholder="542 W. 15th Street">';
            ?>
            <label for="city"><i class="fas fa-location-arrow"></i> City</label>
            <?php
            if($_SESSION['saved'] == 1)
              echo '<input type="text" id="city" name="city" value='.$account_info['City'].'>';
            else if($_SESSION['saved'] == 0)
              echo '<input type="text" id="city" name="city" placeholder="New York">';
            ?>
            
            <div class="row">
              <div class="col-50">
                <label for="state"><i class="fas fa-archway"></i> State</label>
                <?php
                if($_SESSION['saved'] == 1)
                  echo '<input type="text" id="state" name="state" value='.$account_info['State'].'>';
                else if($_SESSION['saved'] == 0)
                  echo '<input type="text" id="state" name="state" placeholder="NY">';
                ?>
                
              </div>
              <div class="col-50">
                <label for="zip"><i class="fas fa-align-justify"></i> Zip</label>
                <?php
                if($_SESSION['saved'] == 1)
                  echo '<input type="text" id="zip" name="zip" value='.$account_info['ZIP'].'>';
                else if($_SESSION['saved'] == 0)
                  echo '<input type="text" id="zip" name="zip" placeholder="10001">';
                ?>
                
              </div>
            </div>
          </div>
          <div class="col-50">
            <label for="fname">Accepted Methods</label>
            <div class="icon-container">
              <i class="fab fa-cc-paypal fa-2x"></i>
            </div>
            <label for="cname">Transit No.</label>
            <?php
                if($_SESSION['saved'] == 1)
                  echo '<input type="text" id="cname" name="transit_no" value='.$account_info['Transit_no'].'>';
                else if($_SESSION['saved'] == 0)
                  echo '<input type="text" id="cname" name="transit_no" placeholder="12345">';
            ?>
            
            <label for="ccnum">Institution No.</label>
            <?php
                if($_SESSION['saved'] == 1)
                  echo '<input type="text" id="ccnum" name="inst_no" value='.$account_info['Inst_no'].'>';
                else if($_SESSION['saved'] == 0)
                  echo '<input type="text" id="ccnum" name="inst_no" placeholder="001">';
            ?>
            <label for="expmonth">Account No.</label>
            <?php
                if($_SESSION['saved'] == 1)
                    echo '<input type="text" id="ccnum" name="account_no" value='.$account_info['Account_no'].'>';
                else if($_SESSION['saved'] == 0)
                    echo '<input type="text" id="ccnum" name="account_no" placeholder="1234567">';
            ?>

            <label for="expmonth">Swift/BIC Code</label>
            <?php
                if($_SESSION['saved'] == 1)
                    echo '<input type="text" id="ccnum" name="swift" value='.$account_info['Swift'].'>';
                else if($_SESSION['saved'] == 0)
                    echo '<input type="text" id="ccnum" name="swift" placeholder="AAAABBCCDDD">';
            ?>
            <div class="row">
              <div class="col-50">
                <label for="expyear">Account</label>
                <?php
                  echo '<div class="select-dark">
                          <select name="account_type" id="dark">
                              <option selected disabled>Type</option>
                              <option value="chequing">Chequing</option>
                              <option value="saving">Saving</option>
                          </select>
                          </div>';        
            ?>
                
              </div>
            </div>
          </div>
          
        </div>
        <label>
        <?php
            // if($_SESSION['saved'] == 0)
          if($_SESSION['saved'] == 0 || ((empty($account_info['Full_name']) || $account_info['Full_name'] == 0) && empty($account_info['email']) && empty($account_info['billing_address']) && empty($account_info['City']) && empty($account_info['State']) && empty($account_info['ZIP']) && empty($account_info['Transit_no']) && empty($account_info['Inst_no']) && empty($account_info['Account_no'])))
              echo '<input type="checkbox" name="save_info" value="Yes" checked> Save information for later deposits';
          else
            echo '<input type="checkbox" name="save_info" value="Yes" checked> Update billing information';
        ?>
        </label>
        <input type="submit" value="Continue to deposit" class="btn btn-primary">
      </form>
    </div>
  </div>
  <div class="col-25">
    <div class="container">
      <h4 style="color: #ff9100;">Deposit Amount <span class="price" style="color:white;"><?php 
      if($_SESSION['currency'] == "USD" || $_SESSION['currency'] == "CAD")
        echo "$: ";
    //   echo "(q̶): -";
      else if($_SESSION['currency'] == "EURO")
        echo "€: ";
      echo round($_SESSION['cad'], 2); ?></b></span></h4>
      <p><a><?php echo $_SESSION['currency']; ?></a> <span class="price"><?php
      if($_SESSION['currency'] == "USD" || $_SESSION['currency'] == "CAD") 
        echo "$";
      else if($_SESSION['currency'] == "EURO")
        echo "€";
      echo round($_SESSION['cad'], 2);
      ?></span></p>
      <p><a>Fees (2%)</a> <span class="price"><?php 
      if($_SESSION['currency'] == "USD" || $_SESSION['currency'] == "CAD") 
        echo "$";
      else if($_SESSION['currency'] == "EURO")
        echo "€";
        $fees = $_SESSION['cad']*0.02;
        echo "-";
        echo round($fees, 2);
      ?></span></p>
      <hr>
      <p>Total <span class="price" style="color: white;"><b><?php 
        if($_SESSION['currency'] == "USD" || $_SESSION['currency'] == "CAD") 
            echo "$";
        else if($_SESSION['currency'] == "EURO")
            echo "€";
        $total = $_SESSION['cad'] - $fees;
        echo round($total, 2);
      ?></b></span></p>
    </div>
  </div>
</div>
<?php
  $_SESSION['saved'] = 0;
?>
<script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
</body>