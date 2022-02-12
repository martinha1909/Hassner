<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../shared/include/StockTradeHelpers.php';
    include '../constants/ShareInteraction.php';

    $conn = connect();
    $connPDO = connectPDO();

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
        echo(json_encode(array(
            "status" => StatusCodes::ErrEmpty,
            "msg" => "Amount cannot be empty"
        )));
    }
    else if(!is_numeric($additional_shares))
    {
        hx_error(HX::SHARES_INJECT, "additional_shares cannot be non-numeric (artist: ".$_SESSION['username'].")");
        echo(json_encode(array(
            "status" => StatusCodes::ErrNum,
            "msg" => "Amount has to be a number"
        )));
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

        hx_debug(HX::SELL_SHARES, "autoSellNoLimitStop param: ".json_encode(array(
            "user_username" => $_SESSION['username'],
            "artist_username" => $_SESSION['username'],
            "request_quantity" => $additional_shares,
            "request_price:" => $current_pps,
            "current_market_price" => $current_pps,
            "is_from_injection" => true
        )));
        $new_quantity = autoSellNoLimitStop($_SESSION['username'], 
                                            $_SESSION['username'],
                                            $additional_shares, 
                                            $current_pps,
                                            $current_pps,
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
                "sell_limit" => -1,
                "sell_stop" => -1,
                "date_posted: " => $current_date,
                "is_from_injection" => true
            )));
            //When artist distributes more share, we add it as a sell order as well
            //Share injection sell orders don't have limit and stop, hence, these values are set to -1
            postSellOrder($connPDO, $_SESSION['username'], $_SESSION['username'], $new_quantity, $current_pps, -1, -1, $current_date, true);
        }

        echo(json_encode(array(
            "status" => StatusCodes::Success,
            "msg" => "Shares injected successfully"
        )));

        $_SESSION['dependencies'] = "FRONTEND";
    }
?>