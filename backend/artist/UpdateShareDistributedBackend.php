<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/ShareInteraction.php';

    $conn = connect();

    $_SESSION['logging_mode'] = LogModes::SHARE_DIST;

    $additional_shares = $_POST['share_distributing'];
    $comment = $_POST['inject_comment'];

    hx_debug(HX::SHARES_INJECT, "Fetching form post variables: ".json_encode(array(
        "additional_shares" => $additional_shares,
        "comment" => $comment
    )));

    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($additional_shares))
    {
        hx_error(HX::SHARES_INJECT, "additional_shares cannot be empty (artist: ".$_SESSION['username'].")");
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        returnToMainPage();
    }
    else if(!is_numeric($additional_shares))
    {
        hx_error(HX::SHARES_INJECT, "additional_shares cannot be non-numeric (artist: ".$_SESSION['username'].")");
        $_SESSION['status'] = StatusCodes::ErrNum;
        returnToMainPage();
    }
    else
    {
        if(empty($comment))
        {
            hx_info(HX::SHARES_INJECT, "Injecting shares with empty comments for artist ".$_SESSION['username']);
            //empty check and makes sure that it is properly instanitiated 
            $comment = "";
        }

        $res = searchNumberOfShareDistributed($conn, $_SESSION['username']);
        hx_debug(HX::QUERY, "searchNumberOfShareDistributed returned ".$res->num_rows." entries");
        $share_distributed = $res->fetch_assoc();
        hx_debug(HX::QUERY, "share_distributed: ".json_encode($share_distributed));

        $new_shares_distributed = $share_distributed['Share_Distributed'] + $additional_shares;

        $res_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['username']);
        hx_debug(HX::QUERY, "searchArtistCurrentPricePerShare returned ".$res_2->num_rows." entries");
        $current_pps = $res_2->fetch_assoc();
        hx_debug(HX::QUERY, "current_pps: ".json_encode($current_pps));

        hx_debug(HX::SHARES_INJECT, "updateShareDistributed params: ".json_encode(array(
            "artist_username" => $_SESSION['username'], 
            "new_share_distributed" => $new_shares_distributed, 
            "added_shares" => $additional_shares, 
            "comment" => $comment, 
            "date" => $current_date
        )));
        updateShareDistributed($conn, 
                               $_SESSION['username'], 
                               $new_shares_distributed, 
                               $additional_shares, 
                               $comment, 
                               $current_date);
        
        $current_pps = getArtistPricePerShare($_SESSION['username']);
        hx_debug(HX::HELPER, "current_pps is ".$current_pps);

        hx_debug(HX::SELL_SHARES, "autoSell param: ".json_encode(array(
            "user_username" => $_SESSION['username'],
            "artist_username" => $_SESSION['username'],
            "asked_price" => $current_pps,
            "quantity:" => $additional_shares,
            "current_date: " => $current_date,
            "is_from_injection" => true
        )));
        $new_quantity = autoSell($_SESSION['username'], 
                                 $_SESSION['username'], 
                                 $current_pps, 
                                 $additional_shares,
                                 $current_date,
                                 true);

        refreshSellOrderTable();
        refreshBuyOrderTable();

        if($new_quantity > 0)
        {
            hx_debug(HX::SELL_SHARES, "postSellOrder param: ".json_encode(array(
                "user_username" => $_SESSION['username'],
                "artist_username" => $_SESSION['username'],
                "quantity" => $new_quantity,
                "asked_price:" => $current_pps,
                "date_posted: " => $current_date,
                "is_from_injection" => true
            )));
            //When artist distributes more share, we add it as a sell order as well
            postSellOrder($conn, $_SESSION['username'], $_SESSION['username'], $new_quantity, $current_pps, $current_date, true);
        }

        $_SESSION['share_distribute'] = 0;
        $_SESSION['dependencies'] = "FRONTEND";
        
        returnToMainPage();
    }
?>