<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';

    $_SESSION['logging_mode'] = "SELL_SHARE";

    $conn = connect();

    $current_date = getCurrentDate("America/Edmonton");
    $date_parser = dayAndTimeSplitter($current_date);

    if(empty($_POST['asked_price']))
    {
        $_SESSION['status'] = "EMPTY_ERR";
        header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
    }
    else
    {
        $_SESSION['logging_mode'] = 0;
        $quantity = $_POST['purchase_quantity'];
        $asked_price = $_POST['asked_price'];

        $result = getSpecificAskedPrice($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        $existed = 0;

        //queries to see if the user has already been selling this share of the same artist or not
        while($row = $result->fetch_assoc())
        {   
            if((strcmp($row['user_username'], $_SESSION['username']) == 0) && (strcmp($row['artist_username'], $_SESSION['selected_artist']) == 0) && ($row['selling_price'] == $asked_price))
            {
                $existed = 1;
                break;
            }
        }

        //If the user has not been selling the same share, post a new order to sell
        if($existed == 0)
        {
            $_SESSION['logging_mode'] = "NON_EXIST";

            $_SESSION['status'] = postSellOrder($conn, 
                                                $_SESSION['username'], 
                                                $_SESSION['selected_artist'], 
                                                $quantity, 
                                                $asked_price, 
                                                $date_parser[0], 
                                                $date_parser[1]);
        }

        //If the user has already been selling the same share, simply just adjust the quantity to the new requested quantity
        else
        {
            $_SESSION['logging_mode'] = "EXIST";

            $res = searchUserSellOrders($conn, $_SESSION['username']);
            while($row = $res->fetch_assoc())
            {
                if($row['selling_price'] == $asked_price)
                {
                    $quantity += $row['no_of_share'];
                    $_SESSION['status'] = adjustExistedAskedPriceQuantity($conn, 
                                                                          $_SESSION['username'], 
                                                                          $_SESSION['selected_artist'], 
                                                                          $asked_price, 
                                                                          $quantity, 
                                                                          $date_parser[0], 
                                                                          $date_parser[1]);
                    break;
                }
            }
        }

        $_SESSION['display'] = "PORTFOLIO";
        $_SESSION['dependencies'] = "FRONTEND";
        returnToMainPage();
    }
?>