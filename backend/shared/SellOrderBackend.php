<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../constants/LoggingModes.php';
    include '../shared/MarketplaceHelpers.php';

    $_SESSION['logging_mode'] = LogModes::SELL_SHARE;

    $conn = connect();

    $current_date = getCurrentDate("America/Edmonton");
    $date_parser = dayAndTimeSplitter($current_date);

    if(empty($_POST['asked_price']))
    {
        $_SESSION['status'] = "EMPTY_ERR";
        if($_SESSION['account_type'] == AccountType::User)
        {
            header("Location: ../../frontend/listener/ArtistUserShareInfo.php");
        }
        else if($_SESSION['account_type'] == AccountType::Artist)
        {
            returnToMainPage();
        }
    }
    else
    {
        $_SESSION['logging_mode'] = LogModes::NONE;
        $quantity = $_POST['purchase_quantity'];
        $asked_price = $_POST['asked_price'];

        $result = searchSellOrderByArtistAndUser($conn, $_SESSION['username'], $_SESSION['selected_artist']);
        $existed = 0;

        if($_SESSION['account_type'] == AccountType::User)
        {
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
                $_SESSION['logging_mode'] = LogModes::NON_EXIST;

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
                $_SESSION['logging_mode'] = LogModes::EXIST;

                $res = searchSellOrderByUser($conn, $_SESSION['username']);
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

            autoSell($_SESSION['username'], $_SESSION['selected_artist'], $asked_price, $quantity);

            refreshUserArtistShareTable();
            refreshSellOrderTable();
            refreshBuyOrderTable();

            $_SESSION['display'] = "PORTFOLIO";
        }

        else if($_SESSION['account_type'] == AccountType::Artist)
        {
            //queries to see if artist has already posted an order to sell their own shares or not
            while($row = $result->fetch_assoc())
            {   
                if((strcmp($row['user_username'], $_SESSION['username']) == 0) && (strcmp($row['artist_username'], $_SESSION['username']) == 0) && ($row['selling_price'] == $asked_price))
                {
                    $existed = 1;
                    break;
                }
            }

            //If the user has not been selling the same share, post a new order to sell
            if($existed == 0)
            {
                $_SESSION['logging_mode'] = LogModes::NON_EXIST;

                $_SESSION['status'] = postSellOrder($conn, 
                                                    $_SESSION['username'], 
                                                    $_SESSION['username'], 
                                                    $quantity, 
                                                    $asked_price, 
                                                    $date_parser[0], 
                                                    $date_parser[1]);
            }

            //If the user has already been selling the same share, simply just adjust the quantity to the new requested quantity
            else
            {
                $_SESSION['logging_mode'] = LogModes::EXIST;

                $res = searchSellOrderByUser($conn, $_SESSION['username']);
                while($row = $res->fetch_assoc())
                {
                    if($row['selling_price'] == $asked_price)
                    {
                        $quantity += $row['no_of_share'];
                        $_SESSION['status'] = adjustExistedAskedPriceQuantity($conn, 
                                                                            $_SESSION['username'], 
                                                                            $_SESSION['username'], 
                                                                            $asked_price, 
                                                                            $quantity, 
                                                                            $date_parser[0], 
                                                                            $date_parser[1]);
                        break;
                    }
                }
            }

            autoSell($_SESSION['username'], $_SESSION['username'], $asked_price, $quantity);
        }
        $_SESSION['dependencies'] = "FRONTEND";
        returnToMainPage();
    }
?>