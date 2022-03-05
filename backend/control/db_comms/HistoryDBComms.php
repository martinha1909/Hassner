<?php
    function searchUserBuyHistory($conn, $user_username)
    {
        $sql = "SELECT * FROM buy_history WHERE user_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchArtistBuyBackShares($conn, $artist_username)
    {
        $sql = "SELECT no_of_share_bought, price_per_share_when_bought, date_purchased FROM buy_history WHERE user_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $artist_username, $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSharesBoughtFromArtist($conn, $artist_username)
    {
        $sql = "SELECT no_of_share_bought, price_per_share_when_bought, date_purchased FROM buy_history WHERE artist_username = ? ORDER BY date_purchased DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchBuyHistoryByUserAndArtist($conn, $user_username, $invested_artist)
    {
        $sql = "SELECT * FROM buy_history WHERE user_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $user_username, $invested_artist);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSellHistoryByUserAndArtist($conn, $seller_username, $artist_username)
    {
        $sql = "SELECT id, seller_username, buyer_username, artist_username, amount_sold, price_sold, date_sold FROM sell_history WHERE seller_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $seller_username, $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchInitialPriceWhenBought($conn, $user_username, $invested_artist)
    {
        $sql = "SELECT price_per_share_when_bought FROM buy_history WHERE user_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $user_username, $invested_artist);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function getInjectionHistory($conn, $artist_username)
    {
        $sql = "SELECT amount, comment, date_injected FROM inject_history WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function addToSellHistory($seller_username, $buyer_username, $artist_username, $amount_sold, $price_sold, $date_sold)
    {
        $connPDO = 0;
        if($_SESSION['dependencies'] == "TEST")
        {
            $connPDO = connectPDOTest();
        }
        else
        {
            $connPDO = connectPDO();
        }
        try
        {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("INSERT INTO sell_history (seller_username, buyer_username, artist_username, amount_sold, price_sold, date_sold)
                                        VALUES(?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $seller_username);
            $stmt->bindValue(2, $buyer_username);
            $stmt->bindValue(3, $artist_username);
            $stmt->bindValue(4, $amount_sold);
            $stmt->bindValue(5, $price_sold);
            $stmt->bindValue(6, $date_sold);
            $stmt->execute(array($seller_username, $buyer_username, $artist_username, $amount_sold, $price_sold, $date_sold));

            $connPDO->commit();
        }
        catch (PDOException $e) 
        {
            $connPDO->rollBack();
            hx_error(HX::DB, "Failed: " . $e->getMessage());
            echo "Failed: " . $e->getMessage()."\n";
        }
    }

    function addToInjectionHistory($conn, $artist_username, $share_distributing, $comment, $date)
    {
        $status = 0;
        $injection_id = 0;

        $sql = "INSERT INTO inject_history (artist_username, amount, comment, date_injected)
                VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('siss', $artist_username, $share_distributing, $comment, $date);
        if($stmt->execute() == TRUE)
        {
            $status = StatusCodes::Success;
        }
        else
        {
            $status = StatusCodes::ErrGeneric;
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }
        return $status;
    }
?>