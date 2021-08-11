<?php
    session_start();
    include '../logic.php';
    include '../connection.php';

    $conn = connect();
    $amount_bought = $_POST['purchase_quantity'];
    //not enough siliqas
    if($_SESSION['user_balance'] < ($amount_bought * $_SESSION['purchase_price']))
    {
        //disabling both options, forbids user from buying anything unless they purchase more siliqas
        $_SESSION['buy_market_price'] = 0;
        $_SESSION['buy_asked_price'] = 0;
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else
    {
        //if the user chooses a seller from market price section
        if($_SESSION['buy_market_price'] == 1)
        {
            $result = searchAccount($conn, $_SESSION['selected_artist']);
            $artist_balance = $result->fetch_assoc();

            //if the user buys from the market price, the siliqas will go to the artist since the artist is the seller
            $artist_new_balance = $artist_balance['balance'] + ($amount_bought * $_SESSION['purchase_price']);

            //subtracts the siliqas from the user
            $buyer_new_balance = $_SESSION['user_balance'] - ($amount_bought * $_SESSION['purchase_price']);

            //the user now owns more share of the artist
            $buyer_new_share_amount = $_SESSION['shares_owned'] + $amount_bought;

            //since there is a buying mecahnism, demand has gone up so price per share is increased
            $new_pps = $_SESSION['current_pps']['price_per_share'];
            for($i = 0; $i<$amount_bought; $i++)
            {
                //for now each time a share is bought its value is increased by 5%
                $new_pps*=1.05;
            }
            purchaseMarketPriceShare($conn, $_SESSION['username'], $_SESSION['selected_artist'], $buyer_new_balance, $artist_new_balance, $_SESSION['current_pps']['price_per_share'], $new_pps, $buyer_new_share_amount, $_SESSION['shares_owned'], $amount_bought);
            $_SESSION['buy_market_price'] = 0;
            $_SESSION['buy_asked_price'] = 0;
            header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
        }
        //if the user chooses a seller from bid price section
        else if($_SESSION['buy_asked_price'] == 1)
        {
            $result = searchAccount($conn, $_SESSION['seller_toggle']);
            $seller_initial_balance = $result->fetch_assoc();
            
            //if the user buys from the bid price, the siliqas will go to the other user since they are the seller
            $seller_new_balance = $seller_initial_balance['balance'] + ($amount_bought * $_SESSION['purchase_price']); 

            //subtracts siliqas from the user
            $buyer_new_balance = $_SESSION['user_balance'] - ($amount_bought * $_SESSION['purchase_price']);
            $result = searchSpecificInvestment($conn, $_SESSION['seller_toggle'], $_SESSION['selected_artist']);
            
            //the owned share of the seller is now transfered to the buyer
            $seller_initial_share_amount = $result->fetch_assoc();
            $seller_new_share_amount = $seller_initial_share_amount['no_of_share_bought'] - $amount_bought;
            $buyer_new_share_amount = $_SESSION['shares_owned'] + $amount_bought;

            //since there is a buying mecahnism, demand has gone up so price per share is increased
            $new_pps = $_SESSION['current_pps']['price_per_share'];
            for($i = 0; $i<$amount_bought; $i++)
            {
                //for now each time a share is bought its value is increased by 5%
                $new_pps*=1.05;
            }
            purchaseAskedPriceShare($conn, $_SESSION['username'], $_SESSION['seller_toggle'], $_SESSION['selected_artist'], $buyer_new_balance, $seller_new_balance, $_SESSION['current_pps']['price_per_share'], $new_pps, $buyer_new_share_amount, $seller_new_share_amount, $_SESSION['shares_owned'], $amount_bought);
            $_SESSION['buy_market_price'] = 0;
            $_SESSION['buy_asked_price'] = 0;
            header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
        }
    }
?>