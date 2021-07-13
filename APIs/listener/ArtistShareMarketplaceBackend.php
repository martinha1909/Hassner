<?php
    function fetchMarketplace($artist_username)
    {
        $selling_prices = array();
        $conn = connect();
        $result = getUsersSellingPrices($conn, $artist_username);
        while($row = $result->fetch_assoc())
        {
            array_push($selling_prices, $row['selling_price']);
        }
        return $selling_prices;
    }
?>