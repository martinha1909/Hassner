<?php
    include '../../APIs/control/Dependencies.php';
    $_SESSION['expmonth'] = 0;
    $_SESSION['expyear'] = 0;
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=divice-width, initial-scale=1.0">

    <title><?php echo $_SESSION['username'];?> Page</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/checkout.css" type="text/css">
    <link rel="stylesheet" href="../css/default.css" type="text/css">
    <link rel="stylesheet" href="../css/menu.css" type="text/css">
</head>

<?php
    $account_info = getAccount($_SESSION['username']);
?>
<body class="bg-dark">
    <header class="smart-scroll">
        <div class="container-xxl">
            <nav class="navbar navbar-expand-md navbar-dark bg-orange d-flex justify-content-between">
            <a id = "href-hover" style = "background: transparent;" class="navbar-brand" href="Artist.php" onclick='window.location.reload();'>
                HASSNER
            </a>
        </div>
    </header>

    <div style="padding-top:50px;" class="row">
        <div class="col-75">
            <div class="container">
                <form action="../../APIs/shared/CardVerificationBackend.php" method="post">
                    <div class="row">
                        <div class="col-50">
                            <h3>Billing Address</h3>
                            <h5 class="text-right"><a href="../../APIs/shared/UseSavedPaymentInfoBackend.php" onclick='window.location.reload();' class="btn btn-primary py-2">Use saved payment info</a></h5>
                            <label for="fname"><i class="fa fa-user"></i> Full Name</label>
                            <?php
                                if($_SESSION['saved'] == 1)
                                {
                                    echo '<input type="text" id="fname" name="firstname" value="'.$account_info['Full_name'].'">';
                                }
                                else if($_SESSION['saved'] == 0)
                                {
                                    echo '<input type="text" id="fname" name="firstname" placeholder="Full Name">';
                                }
                            ?>
                            <label for="email"><i class="fa fa-envelope"></i> Email</label>
                            <?php
                                if($_SESSION['saved'] == 1)
                                {
                                    echo '<input type="text" id="email" name="email" value='.$account_info['email'].'>';
                                }
                                else if($_SESSION['saved'] == 0)
                                {
                                    echo '<input type="text" id="email" name="email" placeholder="john@example.com">';
                                }
                            ?>
                            <label for="adr"><i class="fas fa-map-marker-alt"></i> Address</label>
                            <?php
                                if($_SESSION['saved'] == 1)
                                {
                                    echo '<input type="text" id="adr" name="address" value='.$account_info['billing_address'].'>';
                                }
                                else if($_SESSION['saved'] == 0)
                                {
                                    echo '<input type="text" id="adr" name="address" placeholder="542 W. 15th Street">';
                                }
                            ?>
                            <label for="city"><i class="fas fa-location-arrow"></i> City</label>
                            <?php
                                if($_SESSION['saved'] == 1)
                                {
                                    echo '<input type="text" id="city" name="city" value='.$account_info['City'].'>';
                                }
                                else if($_SESSION['saved'] == 0)
                                {
                                    echo '<input type="text" id="city" name="city" placeholder="New York">';
                                }
                            ?>

                            <div class="row">
                                <div class="col-50">
                                    <label for="state"><i class="fas fa-archway"></i> State</label>
                                    <?php
                                        if($_SESSION['saved'] == 1)
                                        {
                                            echo '<input type="text" id="state" name="state" value='.$account_info['State'].'>';
                                        }
                                        else if($_SESSION['saved'] == 0)
                                        {
                                            echo '<input type="text" id="state" name="state" placeholder="NY">';
                                        }
                                    ?>
                            </div>

                            <div class="col-50">
                                <label for="zip"><i class="fas fa-align-justify"></i> Zip</label>
                                <?php
                                    if($_SESSION['saved'] == 1)
                                    {
                                        echo '<input type="text" id="zip" name="zip" value='.$account_info['ZIP'].'>';
                                    }
                                    else if($_SESSION['saved'] == 0)
                                    {
                                        echo '<input type="text" id="zip" name="zip" placeholder="10001">';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-50">
                        <h3>Payment</h3>
                        <label for="fname">Accepted Cards</label>
                        <div class="icon-container">
                            <i class="fab fa-cc-visa fa-2x"></i>
                            <i class="fab fa-cc-amex fa-2x"></i>
                            <i class="fab fa-cc-mastercard fa-2x"></i>
                            <i class="fab fa-cc-paypal fa-2x"></i>
                        </div>
                        <label for="cname">Name on Card</label>
                        <?php
                            if($_SESSION['saved'] == 1)
                            {
                                echo '<input type="text" id="cname" name="cardname" value='.$account_info['Full_name'].'>';
                            }
                            else if($_SESSION['saved'] == 0)
                            {
                                echo '<input type="text" id="cname" name="cardname" placeholder="John More Doe">';
                            }
                        ?>

                        <label for="ccnum">Credit card number</label>
                        <?php
                            if($_SESSION['saved'] == 1)
                            {
                                echo '<input type="text" id="ccnum" name="cardnumber" value='.$account_info['Card_number'].'>';
                            }
                            else if($_SESSION['saved'] == 0)
                            {
                                echo '<input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444">';
                            }
                        ?>
                        <label for="expmonth">Exp Month</label>
                        <div class="select-dark">
                            <?php
                                echo '
                                    <select name="expmonth" id="dark">
                                        <option selected disabled>Month</option>
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                ';
                            ?>
                        </div>

                        <div class="row">
                            <div class="col-50">
                                <label for="expyear">Exp Year</label>
                                <div class="select-dark">
                                    <?php
                                        echo '
                                            <select name="expyear" id="dark">
                                                <option selected disabled>Year</option>
                                            ';
                                        for($i=2021; $i<2031; $i++)
                                        {
                                            echo '<option value='.$i.'>'.$i.'</option>';
                                        }
                                        echo '</select>';          
                                    ?>
                                </div>
                            </div>

                            <div class="col-4">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="111">
                            </div>
                        </div>
                    </div>

                </div>
                <label>
                    <?php
                        if($_SESSION['saved'] == 0 || ((empty($account_info['Full_name']) || $account_info['Full_name'] == 0) && empty($account_info['email']) && empty($account_info['billing_address']) && empty($account_info['City']) && empty($account_info['State']) && empty($account_info['ZIP']) && empty($account_info['Card_number']) && empty($account_info['Expiry_month']) && empty($account_info['Expiry_year'])))
                        {
                            echo '<input type="checkbox" name="save_info" value="Yes" checked> Save information for later payments';
                        }
                        else
                        {
                            echo '<input type="checkbox" name="save_info" value="Yes" checked> Update billing information';
                        }
                    ?>
                </label>
                <input type="submit" value="Continue to checkout" class="btn btn-primary">
            </form>
        </div>
    </div>
    <div class="col-25">
        <div class="container">
            <h4 style="color: #ff9100;">Deposit <span class="price" style="color:white;">
                <?php 
                    if($_SESSION['currency'] == "USD" || $_SESSION['currency'] == "CAD") 
                    {
                        echo "$";
                    }
                    else if($_SESSION['currency'] == "EURO")
                    {
                        echo "€";
                    }
                    echo $_SESSION['deposit']; 
                ?>
            </span></h4>
            <p>Your lower-bound(q̶): <span class="price"><?php echo round($_SESSION['lower_bound'], 2); ?></span></p>
            </div>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
</body>