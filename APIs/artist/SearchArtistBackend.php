<?php
function printSearch($search_result)
 {   $conn = connect();
//     $result = searchAccount($conn, $artist_search);
//     $search_result= $result->fetch_assoc();
    
   
    
        $artist_name = $search_result['username'];
        $price_per_share = $search_result['price_per_share'];
        $Monthly_shareholders = $search_result['Monthly_shareholder'];
        $market_cap = $search_result['Market_cap'];
        $lower_bound = $search_result['lower_bound'];
        $id = 1;
        //$price_per_share = getArtistPricePerShare($users[$i]);
        //$rate = getArtistCurrentRate($users[$i]);
        echo '<tr><th scope="row">'.$id.'</th>
                    <td style="color: white">'.$artist_name.'</td>
                    <td style="color: white">'.$price_per_share.'</td>
                    <td style="color: white">'.$market_cap.'</td>
                    <td style="color: white">'.$lower_bound.'</td>
                    <td style="color: white">'.$Monthly_shareholders.'</td>';    
           
}
?>