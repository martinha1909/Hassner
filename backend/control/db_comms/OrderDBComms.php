<?php
    function searchAllSellOrdersZeroQuantity($conn)
    {
        $sql = "SELECT * FROM sell_order WHERE no_of_share <= 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSellOrderByUser($conn, $user_username)
    {
        $sql = "SELECT * FROM sell_order WHERE user_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchArtistHighestPrice($conn, $artist_username)
    {
        $sql = "SELECT MAX(selling_price) AS maximum FROM sell_order WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchArtistLowestPrice($conn, $artist_username)
    {
        $sql = "SELECT MIN(selling_price) AS minimum FROM sell_order WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSellOrderByID($conn, $id)
    {
        $result = 0;

        $sql = "SELECT * FROM sell_order WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        if($stmt->execute() == TRUE)
        {
            $result = $stmt->get_result();
        }
        else
        {
            $msg = "db error occured: ".$conn->mysqli_error($conn);
            hx_error(HX::DB, $msg);
        }

        return $result;
    }

    function searchSharesSelling($conn, $user_username, $artist_username)
    {
        $sql = "SELECT no_of_share FROM sell_order WHERE user_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $user_username, $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSellOrderByArtist($conn, $artist_username)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted FROM sell_order WHERE artist_username = ? ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchNumOfSharesNoLimitStopSellOrders($conn, $user_username, $artist_username, $market_price)
    {
        $result = 0;

        $sql = "SELECT id, no_of_share
                FROM sell_order 
                WHERE artist_username = ? AND user_username != ? AND selling_price = ? AND sell_limit = -1 AND sell_stop = -1
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssd', $artist_username, $user_username, $market_price);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchNumOfSharesLimitSellOrders($conn, $user_username, $artist_username, $limit)
    {
        $result = 0;

        $sql = "SELECT id, no_of_share
                FROM sell_order 
                WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND (sell_limit <= ?) AND sell_limit != -1)
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchNumOfSharesStopSellOrders($conn, $user_username, $artist_username, $stop)
    {
        $result = 0;

        $sql = "SELECT id, no_of_share
                FROM sell_order 
                WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND sell_stop >= ?)
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchOlderSellOrders($conn, $user_username, $artist_username, $current_exe_date)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                FROM sell_order 
                WHERE artist_username = ? AND user_username != ? AND date_posted <= ?
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $artist_username, $user_username, $current_exe_date);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchMarketExeLimitStopSellOrders($conn, $artist_username, $market_price)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                FROM sell_order 
                WHERE artist_username = ? AND (selling_price = ? OR (selling_price = -1 AND (sell_stop >= ? OR (sell_limit <= ? AND sell_limit != -1))))
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sddd', $artist_username, $market_price, $market_price, $market_price);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchMatchingSellOrderNoLimitStop($conn, $user_username, $artist_username, $market_price)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                FROM sell_order 
                WHERE artist_username = ? AND user_username != ? AND (sell_stop >= ? OR (sell_limit <= ? AND sell_limit > 0) OR selling_price = ?)
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssddd', $artist_username, $user_username, $market_price, $market_price, $market_price);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchMatchingSellOrderLimitStop($conn, $user_username, $artist_username, $buy_limit, $buy_stop, $current_market_price, $include_market_orders)
    {
        $result = 0;
        $sql = "";

        if($include_market_orders)
        {
            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND ((selling_price = ? AND sell_limit = -1 AND sell_stop = -1) OR (sell_limit <= ? AND sell_limit != -1) OR (sell_stop >= ?))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssddd', $artist_username, $user_username, $current_market_price, $buy_limit, $buy_stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }
        else
        {
            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE (artist_username = ? AND user_username != ?) AND ((selling_price = -1 AND (sell_limit < ? OR sell_limit = ?) AND sell_limit != -1) OR (selling_price = -1 AND (sell_stop > ? OR sell_stop = ?)))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdddd', $artist_username, $user_username, $buy_limit, $buy_limit, $buy_stop, $buy_stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }

        return $result;
    }

    function searchMatchingSellOrderLimit($conn, $user_username, $artist_username, $limit, $market_price, $include_market_orders)
    {
        $result = 0;

        if($include_market_orders)
        {
            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND sell_limit <= ? AND sell_limit != -1) OR (selling_price = ? AND sell_limit = -1 AND sell_stop = -1)
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdd', $artist_username, $user_username, $limit, $market_price);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }
        else
        {
            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND selling_price = -1 AND sell_limit <= ? AND sell_limit != -1
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }

        return $result;
    }

    function searchMatchingSellOrderStop($conn, $user_username, $artist_username, $stop, $market_price, $include_market_orders)
    {
        $result = 0;

        if($include_market_orders)
        {
            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND (selling_price = -1 AND sell_stop >= ?) OR (selling_price = ? AND sell_limit = -1 AND sell_stop = -1)
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdd', $artist_username, $user_username, $stop, $market_price);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }
        else
        {
            $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted 
                    FROM sell_order 
                    WHERE artist_username = ? AND user_username != ? AND selling_price = -1 AND sell_stop >= ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }

        return $result;
    }

    function searchAllSellOrdersNoLimitStop($conn, $artist_username)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted
                FROM sell_order
                WHERE artist_username = ? AND selling_price != -1 AND sell_limit = -1 AND sell_stop = -1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchSellOrderFromRepurchase($conn, $artist_username)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted
                FROM sell_order
                WHERE user_username = ? AND artist_username = ? AND is_from_injection = 0 AND sell_limit = -1 AND sell_stop = -1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $artist_username, $artist_username);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchSellOrdersIDFromInjection($conn, $artist_username)
    {
        $result = 0;

        $sql = "SELECT id FROM sell_order WHERE artist_username = ? AND is_from_injection = 1 ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchSellOrderByArtistAndUser($conn, $user_username, $artist_username)
    {
        $sql = "SELECT * FROM sell_order WHERE artist_username = ? AND user_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $artist_username, $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchMaxIDSellOrdersNotFromUser($conn, $user_username, $artist_username)
    {
        $result = 0;

        $sql = "SELECT MAX(id) AS max_sell_order_id FROM sell_order WHERE artist_username = ? AND user_username != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $artist_username, $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function updateSellOrderPPS($new_pps, $sell_order_id)
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
        $status = StatusCodes::NONE;

        try {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("UPDATE sell_order SET selling_price = ? WHERE id = ?");
            $stmt->bindValue(1, $new_pps);
            $stmt->bindValue(2, $sell_order_id);
            $stmt->execute(array($new_pps, $sell_order_id));

            $connPDO->commit();
            $status = StatusCodes::Success;
            echo "sell order id ".$sell_order_id." updated selling price to ".$new_pps;
            hx_info(HX::SELL_ORDER, "sell order id ".$sell_order_id." updated selling price to ".$new_pps);
        } catch (PDOException $e) {
            $connPDO->rollBack();
            echo "Failed: " . $e->getMessage();
            hx_error(HX::DB, "Failed: " . $e->getMessage());

            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }

    function updateSellOrderNoOfShare($connPDO, $sell_order_id, $new_no_of_share)
    {
        try
        {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("UPDATE sell_order SET no_of_share = ? WHERE id = ?");
            $stmt->bindValue(1, $new_no_of_share);
            $stmt->bindValue(2, $sell_order_id);
            $stmt->execute(array($new_no_of_share, $sell_order_id));

            $connPDO->commit();
            hx_info(HX::SELL_ORDER, "sell order (id: ".$sell_order_id.") has updated no_of_share to ".$new_no_of_share."\n".
                                    "--------------------------------");
        }
        catch (PDOException $e)
        {
            $connPDO->rollBack();
            hx_error(HX::DB, "DB error occured: ".$e->getMessage());
            hx_error(HX::SELL_ORDER, "Failed to update sell order (id:".$sell_order_id.") to new quantity ".$new_no_of_share);
        }
    }

    function postSellOrder($connPDO, $user_username, $artist_username, $quantity, $asked_price, $sell_limit, $sell_stop, $date_posted, $is_from_injection)
    {
        if($is_from_injection)
        {
            $is_from_injection = 1;
        }
        else
        {
            $is_from_injection = 0;
        }
        $status = StatusCodes::NONE;

        try {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("INSERT INTO sell_order (user_username, artist_username, selling_price, no_of_share, sell_limit, sell_stop, is_from_injection, date_posted)
                                        VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $user_username);
            $stmt->bindValue(2, $artist_username);
            $stmt->bindValue(3, $asked_price);
            $stmt->bindValue(4, $quantity);
            $stmt->bindValue(5, $sell_limit);
            $stmt->bindValue(6, $sell_stop);
            $stmt->bindValue(7, $is_from_injection);
            $stmt->bindValue(8, $date_posted);
            $stmt->execute(array($user_username, $artist_username, $asked_price, $quantity, $sell_limit, $sell_stop, $is_from_injection, $date_posted));
            
            $connPDO->commit();
            $status = StatusCodes::Success;
            hx_info(HX::BUY_SHARES, "Sell order posted by user ".$user_username);
        } catch (PDOException $e) {
            $connPDO->rollBack();
            hx_error(HX::DB, "Failed: " . $e->getMessage());

            $status = StatusCodes::ErrGeneric;
        }
        return $status;
    }

    function removeSellOrder($conn, $order_id)
    {
        $sql = "DELETE FROM sell_order WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $order_id);
        $stmt->execute();

        hx_info(HX::SELL_ORDER, "sell order (id: ".$order_id.") has been removed\n".
                                "--------------------------------");
    }

    function searchAllBuyOrdersNoLimitStop($conn, $artist_username)
    {
        $result = 0;

        $sql = "SELECT id, user_username, siliqas_requested, quantity, buy_limit, buy_stop
                FROM buy_order
                WHERE artist_username = ? AND siliqas_requested != -1 AND buy_limit = -1 AND buy_stop = -1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchMatchingBuyOrderStop($conn, $user_username, $artist_username, $stop, $market_price, $include_market_orders)
    {
        $result = 0;

        if($include_market_orders)
        {
            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1) OR (siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdd', $artist_username, $user_username, $market_price, $stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }
        else
        {
            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }

        return $result;
    }

    function searchMatchingBuyOrderNoLimitStop($conn, $user_username, $artist_username, $market_price)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                FROM buy_order 
                WHERE artist_username = ? AND user_username != ? AND ((buy_stop <= ? AND buy_stop > 0) OR buy_limit >= ? OR siliqas_requested = ?)
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssddd', $artist_username, $user_username, $market_price, $market_price, $market_price);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchMatchingBuyOrderLimit($conn, $user_username, $artist_username, $limit, $market_price, $include_market_orders)
    {
        $result = 0;

        if($include_market_orders)
        {
            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1) OR (siliqas_requested = -1 AND buy_limit >= ?))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdd', $artist_username, $user_username, $market_price, $limit);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }
        else
        {
            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND siliqas_requested = -1 AND buy_limit >= ?
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssd', $artist_username, $user_username, $limit);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }

        return $result;
    }

    function searchMatchingBuyOrderLimitStop($conn, $seller_username, $artist_username, $sell_limit, $sell_stop, $current_market_price, $include_market_orders)
    {
        $result = 0;

        if($include_market_orders)
        {
            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = ? AND buy_limit = -1 AND buy_stop = -1) OR (siliqas_requested = -1 AND buy_limit >= ?) OR (siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssddd', $artist_username, $seller_username, $current_market_price, $sell_limit, $sell_stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }
        else
        {
            $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                    FROM buy_order 
                    WHERE artist_username = ? AND user_username != ? AND ((siliqas_requested = -1 AND buy_limit >= ?) OR (siliqas_requested = -1 AND buy_stop <= ? AND buy_stop != -1))
                    ORDER BY date_posted ASC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssdd', $artist_username, $seller_username, $sell_limit, $sell_stop);
            if($stmt->execute() == true)
            {
                $result = $stmt->get_result();
            }
            else
            {
                hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            }
        }

        return $result;
    }

    function searchMarketExeLimitStopBuyOrders($conn, $artist_username, $market_price)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                FROM buy_order 
                WHERE artist_username = ? AND (siliqas_requested = ? OR (siliqas_requested = -1 AND ((buy_stop <= ? AND buy_stop != -1) OR buy_limit >= ?)))
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sddd', $artist_username, $market_price, $market_price, $market_price);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchOlderBuyOrders($conn, $user_username, $artist_username, $current_exe_date)
    {
        $result = 0;

        $sql = "SELECT id, user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted 
                FROM buy_order 
                WHERE artist_username = ? AND user_username != ? AND date_posted <= ?
                ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $artist_username, $user_username, $current_exe_date);
        if($stmt->execute() == true)
        {
            $result = $stmt->get_result();
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
        }

        return $result;
    }

    function searchAllBuyOrdersZeroQuantity($conn)
    {
        $sql = "SELECT * FROM buy_order WHERE quantity <= 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchUserBuyOrders($conn, $user_username)
    {
        $sql = "SELECT * FROM buy_order WHERE user_username = ? ORDER BY date_posted ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchBuyOrdersByArtist($conn, $artist_username)
    {
        $sql = "SELECT * FROM buy_order WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchSharesRequested($conn, $user_username, $artist_username)
    {
        $sql = "SELECT quantity FROM buy_order WHERE user_username = ? AND artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $user_username, $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function updateBuyOrderPPS($new_pps, $buy_order_id)
    {
        $connPDO = connectPDO();
        $status = StatusCodes::NONE;

        try {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("UPDATE buy_order SET siliqas_requested = ? WHERE id = ?");
            $stmt->bindValue(1, $new_pps);
            $stmt->bindValue(2, $buy_order_id);
            $stmt->execute(array($new_pps, $buy_order_id));

            $connPDO->commit();
            $status = StatusCodes::Success;
            hx_info(HX::BUY_ORDER, "buy order id ".$buy_order_id." updated requesting price to ".$new_pps);
        } catch (PDOException $e) {
            $connPDO->rollBack();
            hx_error(HX::DB, "Failed: " . $e->getMessage());

            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }

    function updateBuyOrderQuantity($conn, $buy_order_id, $new_quantity)
    {
        $status = StatusCodes::NONE;

        $sql = "UPDATE buy_order SET quantity = '$new_quantity' WHERE id = '$buy_order_id'";
        if($conn->query($sql) == true)
        {
            hx_info(HX::BUY_ORDER, "Updated quantity to ".$new_quantity." for buy order id ".$buy_order_id);
            $status = StatusCodes::Success;
        }
        else
        {
            hx_error(HX::DB, "db error occured: ".$conn->mysqli_error($conn));
            $status = StatusCodes::ErrServer;
        }

        return $status;
    }
    
    function postBuyOrder($connPDO, $user_username, $artist_username, $quantity, $request_price, $buy_limit, $buy_stop, $date_posted)
    {
        $status = StatusCodes::NONE;

        try {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("INSERT INTO buy_order (user_username, artist_username, quantity, siliqas_requested, buy_limit, buy_stop, date_posted)
                                    VALUES(?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindValue(1, $user_username);
            $stmt->bindValue(2, $artist_username);
            $stmt->bindValue(3, $quantity);
            $stmt->bindValue(4, $request_price);
            $stmt->bindValue(5, $buy_limit);
            $stmt->bindValue(6, $buy_stop);
            $stmt->bindValue(7, $date_posted);
            $stmt->execute(array($user_username, $artist_username, $quantity, $request_price, $buy_limit, $buy_stop, $date_posted));
            
            $connPDO->commit();
            $status = StatusCodes::Success;
            hx_info(HX::BUY_SHARES, "Buy order posted by user ".$user_username);
        } catch (PDOException $e) {
            $connPDO->rollBack();
            hx_error(HX::DB, "Failed: " . $e->getMessage());

            $status = StatusCodes::ErrGeneric;
        }
        return $status;
    }

    function removeBuyOrder($conn, $buy_order_id)
    {
        $sql = "DELETE FROM buy_order WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $buy_order_id);
        if($stmt->execute() == true)
        {
            hx_info(HX::BUY_ORDER, "Buy order (id: ".$buy_order_id.") has been removed");
        }
        else
        {
            hx_info(HX::BUY_ORDER, "Failed to remove buy order (id: ".$buy_order_id.")");
        }
    }
?>