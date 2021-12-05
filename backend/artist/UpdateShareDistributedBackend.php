<?php
    $_SESSION['dependencies'] = "BACKEND";
    include '../control/Dependencies.php';
    include '../shared/include/MarketplaceHelpers.php';
    include '../constants/ShareInteraction.php';

    $conn = connect();

    $_SESSION['logging_mode'] = LogModes::SHARE_DIST;

    $additional_shares = $_POST['share_distributing'];
    $comment = $_POST['inject_comment'];
    date_default_timezone_set(Timezone::MST);
    $current_date = date('Y-m-d H:i:s');

    if(empty($additional_shares))
    {
        $_SESSION['status'] = StatusCodes::ErrEmpty;
        returnToMainPage();
    }
    else if(!is_numeric($additional_shares))
    {
        $_SESSION['status'] = StatusCodes::ErrNum;
        returnToMainPage();
    }
    else
    {
        if(empty($comment))
        {
            //empty check and makes sure that it is properly instanitiated 
            $comment = "";
        }

        $res = searchNumberOfShareDistributed($conn, $_SESSION['username']);
        $share_distributed = $res->fetch_assoc();

        $new_shares_distributed = $share_distributed['Share_Distributed'] + $additional_shares;

        $res_2 = searchArtistCurrentPricePerShare($conn, $_SESSION['username']);
        $current_pps = $res_2->fetch_assoc();

        updateShareDistributed($conn, 
                            $_SESSION['username'], 
                            $new_shares_distributed, 
                            $additional_shares, 
                            $comment, 
                            $current_date);
        
        $current_pps = getArtistPricePerShare($_SESSION['username']);
        $new_quantity = autoSell($_SESSION['username'], 
                                 $_SESSION['username'], 
                                 $current_pps, 
                                 $additional_shares,
                                 $current_date,
                                 ShareInteraction::BUY_BACK_SHARE);

        refreshSellOrderTable();
        refreshBuyOrderTable();

        if($new_quantity > 0)
        {
            //When artist distributes more share, we add it as a sell order as well
            postSellOrder($conn, $_SESSION['username'], $_SESSION['username'], $new_quantity, $current_pps, $current_date);
        }

        $_SESSION['share_distribute'] = 0;
        $_SESSION['dependencies'] = "FRONTEND";
        
        returnToMainPage();
    }
?>