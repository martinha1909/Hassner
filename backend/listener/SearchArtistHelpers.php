<?php
        function printSearch($search_result)
        {   
                $conn = connect();

                $artist_name = $search_result['username'];
                $price_per_share = $search_result['price_per_share'];
                $Monthly_shareholders = $search_result['Monthly_shareholder'];
                $market_cap = $search_result['Market_cap'];
                $id = 1;
                echo '
                        <tr><th scope="row">'.$id.'</th>
                                <form action="../../backend/artist/ArtistShareInfoBackend.php" method="post">
                                        <td><input name = "artist_name" type = "submit" id="abc" style="border:1px transparent; background-color: transparent;" role="button" aria-pressed="true" value = "'.$artist_name.'"></td></td>
                                </form>
                                <td style="color: white">'.$price_per_share.'</td>
                                <td style="color: white">'.$market_cap.'</td>
                                <td style="color: white">'.$Monthly_shareholders.'
                        </td>
                ';    
                 
        }
?>