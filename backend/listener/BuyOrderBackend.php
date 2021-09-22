<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/MarketplaceHelpers.php';

    $_SESSION['logging_mode'] = "BUY_ORDER";

    $conn = connect();

    $request_quantity = $_POST['request_quantity'];
    $request_price = $_POST['request_price'];

    $res = searchAccount($conn, $_SESSION['username']);
    $account_balance = $res->fetch_assoc();
    
    if($account_balance['balance'] < ($request_quantity * $request_price))
    {
        $_SESSION['status'] = "NOT_ENOUGH_SILIQAS";
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else
    {
        $current_date = getCurrentDate("America/Edmonton");
        $date_parser = dayAndTimeSplitter($current_date);

        //Before posting the order, we check if there is any matching sell orders to be executed immediately
        $new_quantity = autoPurchase($conn, 
                                     $_SESSION['username'], 
                                     $_SESSION['selected_artist'], 
                                     $request_quantity, 
                                     $request_price);

        refreshUserArtistShareTable();
        refreshSellOrderTable();

        if($new_quantity > 0)
        {
            postBuyOrder($conn, 
                         $_SESSION['username'], 
                         $_SESSION['selected_artist'], 
                         $new_quantity, 
                         $request_price, 
                         $date_parser[0], 
                         $date_parser[1]);
        }
        
        refreshBuyOrderTable();

        $_SESSION['display'] = "PORTFOLIO";
        $_SESSION['dependencies'] = "FRONTEND";
        returnToMainPage();
    }
?>