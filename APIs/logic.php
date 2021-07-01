<?php
        //logs in with provided user info and password, then use SQL query to query database 
        //after qurerying return the result
        function login($conn, $username, $pwd) // done2
        {
            $sql = "SELECT * FROM account WHERE username = ? AND password = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $pwd);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }
        //searches an account based on a given username
        //SQL queries the account table and returns all tuples that have matching username with the given $username
        function searchAccount($conn, $username) // done2
        {
            $sql = "SELECT * FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchAccountType($conn, $account_type)
        {
            $sql = "SELECT * FROM account WHERE account_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $account_type);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function getArtistShares($conn, $artist_username)
        {
            $sql = "SELECT Share_Distributed FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function getUserBalance($conn, $username)
        {
            $sql = "SELECT balance FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function signup($conn, $username, $password, $type, $email) //done2
        {
            $original_share= "";
            $transit_no = "";
            $inst_no = "";
            $account_no = "";
            $swift = "";
            $billing_address = "";
            $full_name = "";
            $city = "";
            $state= "";
            $zip = "";
            $card_number="";
            $balance = 0;
            $rate = 0;
            $num_of_shares = 0;
            $notify = 0;
            $share_distributed = 0;
            $result = getMaxID($conn);
            $row = $result->fetch_assoc(); 
            $id = $row["max_id"] + 1;
            // $sql = "INSERT INTO account (username, password, account_type, id)
            //         VALUES('$username', '$password', '$type', '$id')";
            $sql = "INSERT INTO account (username, password, account_type, id, Shares, balance, rate, Share_Distributed, email, billing_address, Full_name, City, State, ZIP, Card_number, Transit_no, Inst_no, Account_no, Swift, Original_Share)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssiiddisssssssssssi', $username, $password, $type, $id, $num_of_shares, $balance, $rate, $share_distributed, $email, $billing_address, $full_name, $city, $state, $zip, $card_number, $transit_no, $inst_no, $account_no, $swift, $original_share);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }
            if($type == 'artist')
            {
                $price_per_share = 1;
                $sql = "INSERT INTO artist_per_share (artist_username, price_per_share)
                    VALUES(?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sd', $username, $price_per_share);
                $stmt->execute();
            }
            return $notify;
        }

        function getMaxID($conn){
            $sql = "SELECT MAX(id) AS max_id FROM account";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }

        function getMaxSongID($conn){
            $sql = "SELECT MAX(id) AS max_id FROM song";
            $result = mysqli_query($conn,$sql);
            
            return $result;
        }
        //queries in song table and searches for all tuples that matches the given songId
        //return the tuple of the song table if there is a matching tuple
        function searchSong($conn, $songId) // done2
        {
            // echo $songId;
            // echo "<br>";
            // $sql = "SELECT * FROM spotify.song WHERE id = $songId";
            // $result = $conn->query($sql);
            $sql = "SELECT * FROM spotify.song WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $songId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }

        function searchArtistRate($conn, $artist_username)
        {
            $account_type = 'artist';
            $sql = "SELECT rate FROM account WHERE username = ? AND account_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $account_type);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }

        function verifyArtist($conn, $artist_username)
        {
            $account_type = 'artist';
            $sql = "SELECT * FROM account WHERE username = ? AND account_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $artist_username, $account_type);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }

        function searchArtistUserShares($conn, $user_username, $artist_username)
        {
            $sql = "SELECT * FROM user_artist_share WHERE user_username = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchArtistByShare($conn, $share, $account_type)
        {
            $sql = "SELECT * FROM account WHERE Shares = ? AND account_type = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $share, $account_type);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchArtistPricePerShare($conn, $artist_username)
        {
            $sql = "SELECT * FROM artist_per_share WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $artist_username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }


        function searchArtistSong($conn, $username, $song_id)
        {
            // $sql = "SELECT * FROM artist_song WHERE song_id = '$song_id' AND artist_username = '$username'";
            // $result = $conn->query($sql);
            
            // return $result;

            $sql = "SELECT * FROM artist_song WHERE song_id = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $song_id, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchArtistSingles($conn, $artist_username)
        {
            $singles = array();
            $query = searchSongByArtist($conn, $artist_username);
            while($song = $query->fetch_assoc())
            {
                $result = searchSong($conn, $song['song_id']);
                while($song_info = $result->fetch_assoc())
                {
                    if($song_info['album_name'] == NULL && $song_info['Published'] == 1)
                        array_push($singles, $song_info);
                }
            }
            return $singles;
        }

        // function searchSongByArt

        //searches for all albums inside album table
        //returns any tuples that have name = $album_name
        function searchAlbum($conn, $album_name) //done2
        {
            $sql = "SELECT * FROM album WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $album_name);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        //queries for all the songs in an album in the song table using a given $album_name
        //result will contain all the songs that are in this specified album
        function searchSongsInAlbum($conn, $album_name) //done2
        {
            $sql = "SELECT * FROM album_song WHERE album_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $album_name);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchAlbumBySong($conn, $song_id)
        {
            $sql = "SELECT album_name FROM album_song WHERE song_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $song_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        //queries for all the songs in a playlist in the song table using a given $playlist_name
        //result will contain all the songs that are in this specified playlist
        function searchSongsInPlaylist($conn, $playlist_name) //done2
        {
            // $sql = "SELECT song_id FROM playlist_song WHERE playlist_name = '$playlist_name'";
            // $result = $conn->query($sql);
            
            // return $result;
            $sql = "SELECT song_id FROM playlist_song WHERE playlist_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $playlist_name);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        //queries for all the songs in a playlist in the song table using a given $username of an artist
        //result will contain all the songs that are made by this artist
        function searchSongByArtist($conn, $username) //done2
        {
            $sql = "SELECT * FROM artist_song WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
            // $sql = "SELECT * FROM artist_song WHERE artist_username = '$username'";
            // $result = $conn->query($sql);
            // return $result;
        }

        //queries for all the songs in a playlist in the song table using a given $username of a producer
        //result will contain all the songs that are made by this producer
        function searchSongByProducer($conn, $username) //done2
        {
            $sql = "SELECT * FROM producer_song WHERE producer_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchSongByName($conn, $song_name) //done2
        {
            $sql = "SELECT * FROM song AS songs, artist_song AS artists WHERE songs.id = artists.song_id AND songs.name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $song_name);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
            // $sql = "SELECT * FROM song AS songs, artist_song AS artists WHERE songs.id = artists.song_id AND songs.name = '$song_name'";
            // $result = mysqli_query($conn, $sql);
            
            // return $result;
        }

        function searchArtist($conn, $username)
        {
            // $sql = "SELECT * FROM artist_song WHERE song_id = $songID";
            // $result = mysqli_query($conn, $sql);
            // return $result;
            $sql = "SELECT * FROM artist_song WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchArtistShares($conn, $username)
        {
            $sql = "SELECT Shares FROM account WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        function searchUsersInvestment($conn, $user_username)
        {
            $sql = "SELECT * FROM user_artist_share WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $user_username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }

        //queries all playlist that matches the given $playlist_name in the playlist table
        //result contains all the information of all matching playlist
        function searchPlaylist($conn, $playlist_name) //done2
        {
            $sql = "SELECT * FROM playlist WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $playlist_name);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        function searchPlaylistsByUser($conn, $username){
            $sql = "SELECT * FROM playlist WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            return $result;
        }
        //queries all albums that are made by a specific artist by using the given $artistusername
        //result contains all the album name taken from the matching tuples
        function searchArtistAlbum($conn, $username) // done2
        {
            // $sql = "SELECT * FROM artist_album WHERE artist_username = '$username'";
            // $result = mysqli_query($conn, $sql);
            $sql = "SELECT * FROM artist_album WHERE artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }

        function searchAlbumArtist($conn, $username, $album_name)
        {
            // $sql = "SELECT * FROM artist_album WHERE artist_username = '$username' AND album_name = '$album_name'";
            $sql = "SELECT * FROM artist_album WHERE artist_username = ? AND album_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $album_name);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }

        // Searches for all Ratings made under a specific song
        // result contains all ratings under a song
        function searchRatings($conn, $songID) //done2
        {
            $sql = "SELECT * FROM rating WHERE song_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $songID);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }

        // Searches for a rating under a specific song made by a specific user
        // result contains all ratings made by specific user under specific song
        function searchSpecificRatings($conn, $songID, $username) //done2
        {
            // $sql = "SELECT * FROM rating WHERE song_id = $songID AND user_username = '$username'";
            // $result = mysqli_query($conn, $sql);
            
            // return $result;
            $sql = "SELECT * FROM rating WHERE song_id = ? AND user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $songID, $username);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result;
        }

        // Searches for all Ratings made by a specific user
        // result contains all rating made by specific user
        function searchUsersRating($conn,$username){ //done2
            $sql = "SELECT * FROM rating WHERE user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result;
        }
        // creates a new Song based on the passed in info, if the album_name is not null, it also add the song to the album. Also based on the type of user, 
        //the corressponding function is also called to create a relation
        function createSong($conn, $id, $album_name, $no_of_plays, $duration, $name, $date_created, $username, $type) //done2
        {
            $notify = 0;
            $published = 0;
            $monthly = 0;
            if($album_name == NULL)
            {
                // $sql = "INSERT INTO song (id, album_name, no_of_plays, duration, name, date_created)
                //         VALUES ('$id', NULL, '$no_of_plays', '$duration', '$name', '$date_created')";
                $sql = "INSERT INTO song (id, album_name, no_of_plays, duration, name, date_created, Published, Monthly_Listeners)
                VALUES (?, NULL, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('iidssii', $id, $no_of_plays, $duration, $name, $date_created, $published, $monthly);
                if ($stmt->execute() === TRUE) {
                    $notify = 1;
                } else {
                    $notify = 2;
                }
            }
            else
            {
                // $sql = "INSERT INTO song (id, album_name, no_of_plays, duration, name, date_created)
                //         VALUES ('$id', '$album_name', '$no_of_plays', '$duration', '$name', '$date_created')";
                $sql = "INSERT INTO song (id, album_name, no_of_plays, duration, name, date_created, Published, Monthly_Listeners)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isidssii', $id, $album_name, $no_of_plays, $duration, $name, $date_created, $published, $monthly);
                if ($stmt->execute() === TRUE) {
                    // echo "<script>alert('song created successfully');</script>";
                    $notify = 1;
                } else {
                    $notify = 2;
                //  echo "Error: " . $sql . "<br>" . $conn->error;
                }
                // $sql2 = "INSERT INTO album_song(album_name, song_id) VALUES('$album_name', '$id')";
                $sql2 = "INSERT INTO album_song(album_name, song_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('si', $album_name, $id);
                if ($stmt->execute() === TRUE) {
                    echo "<script>alert('song added to album successfully');</script>";
                } else {
                    echo "<script>alert('Error adding song to album');</script>";
                }
            }
            if($album_name != NULL)
                addSongToAlbum($conn, $album_name, $id);
            if($type == 'artist')
                addToArtistSong($conn, $username, $id);
            if($type == 'producer')
                addToProduceSong($conn, $username, $id);
            return $notify;
        }

        // Connects a artists and the song that they have created
        function addToArtistSong($conn, $username, $id) // done2
        {
            // $sql = "INSERT INTO artist_song (artist_username, song_id)
            //         VALUES('$username', '$id')";
            $sql = "INSERT INTO artist_song (artist_username, song_id)
                    VALUES(?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $username, $id);
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Connects a prducer and the song that they have produced
        function addToProduceSong($conn, $username, $id) //done2
        {
            // $sql = "INSERT INTO producer_song (producer_username, song_id)
            //         VALUES('$username', '$id')";
            // if ($conn->query($sql) === TRUE) {
            $sql = "INSERT INTO producer_song (producer_username, song_id)
                    VALUES(?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $username, $id);
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        function addSharesBought($conn, $user_username, $artist_username, $shares_bought, $new_balance)
        {
            $notify = 0;
            $sql = "INSERT INTO user_artist_share (user_username, artist_username, no_of_share_bought)
                    VALUES(?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssi', $user_username, $artist_username, $shares_bought);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }
            $sql = "UPDATE account SET balance = $new_balance WHERE username = '$user_username'";
            $conn->query($sql);
            return $notify;
        }

        //creates a playlist that has a unique name within the user's playlists
        function createPlaylist($conn, $name, $user_username) //done2
        {
            // $sql = "INSERT INTO playlist (user_username, name, no_of_songs, duration)
            //         VALUES('$user_username', '$name', 0, 0)";
            $sql = "INSERT INTO playlist (user_username, name, no_of_songs, duration)
            VALUES(?, ?, 0, 0)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $user_username, $name);
            $stmt->execute();
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }  
        }

        //Adds a specific song to a specific Album and calls the makeChangestoAlbum function to edit Album info based on Song info
        function addSongToAlbum($conn, $a_name, $songId) // done2
        {
            // $sql = "INSERT INTO album_song (album_name, song_id) 
            //         VALUES('$a_name', '$songId')";
            $sql = "INSERT INTO album_song (album_name, song_id) 
            VALUES(?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $a_name, $songId);
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }        
            // $sql = "UPDATE song SET album_name = '$a_name' WHERE id = '$songId'";
            $sql = "UPDATE song SET album_name = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $a_name, $songId);
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }       
            makeChangestoAlbum($conn, $a_name, $songId);
        }

        //Adds a specific song to a specific Playlist and calls the makeChangestoPlaylist function to edit Playlist info based on Song info
        function addSongToPlayList($conn, $p_name, $songId) //done2
        {

            $sql = "INSERT INTO playlist_song (playlist_name, song_id) 
                    VALUES('$p_name', '$songId')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }  
            makeChangestoPlaylist($conn, $p_name, $songId);
        }

        function increaseNoOfPlays($conn, $songId) //done2
        {
            $sql = "UPDATE song SET no_of_plays = no_of_plays + 1 WHERE id = '$songId' ";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }  
        }

        function publishSong($conn, $song_id)
        {
            $result = searchSong($conn, $song_id);
            $published = $result->fetch_assoc();
            if($published['Published'] == 0)
            {
                $sql = "UPDATE song SET Published = 1 WHERE id = '$song_id' ";
                $conn->query($sql);
            }
            if($published['Published'] == 1)
            {
                $sql = "UPDATE song SET Published = 0 WHERE id = '$song_id' ";
                $conn->query($sql);
            }
            
        }

        function publishAlbum($conn, $album_name)
        {
            $result = searchAlbum($conn, $album_name);
            $published = $result->fetch_assoc();
            if($published['Published'] == 0)
            {
                $sql = "UPDATE album SET Published = 1 WHERE name = '$album_name' ";
                $conn->query($sql);
            }
            if($published['Published'] == 1)
            {
                $sql = "UPDATE album SET Published = 0 WHERE name = '$album_name' ";
                $conn->query($sql);
            }
            
        }

        //creates a new Album and specifies the name of the album as well as the date created (no_of_songs and duration are set to 0 to begin with)
        function createAlbum($conn, $album_name, $date_created, $username) //done2
        {
            $notify = 0;
            // $sql = "INSERT INTO album (name, no_of_songs, duration, date_created)
            //         VALUES('$album_name', 0, 0, '$date_created')";
            $sql = "INSERT INTO album (name, no_of_songs, duration, date_created)
                    VALUES(?, 0, 0, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $album_name, $date_created);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
                echo "New record created successfully";
            } else {
                $notify = 2;
             echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $sql = "INSERT INTO artist_album(artist_username, album_name) VALUES ('$username', '$album_name')";
            $sql = "INSERT INTO artist_album(artist_username, album_name)
                    VALUES(?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $username, $album_name);  
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
             echo "Error: " . $sql . "<br>" . $conn->error;
            }
            return $notify;
        }

        // Adds a new rating to a specific song that is made by a specific user
        function addRating($conn, $username, $songId, $comment, $star_rating) //done2
        {
            $notify = 0;
            // $sql = "INSERT INTO rating (user_username, song_id, star_rating, comment)
            //         VALUES('$username', '$songId', '$star_rating', '$comment')";
            $sql = "INSERT INTO rating (user_username, song_id, star_rating, comment)
                     VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('siis', $username, $songId, $star_rating, $comment);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
                echo "New record created successfully";
            } else {
                $notify = 2;
             echo "Error: " . $sql . "<br>" . $conn->error;
            }  
            return $notify;
        }

        

        // function that is called whenever a new song is added to a Album which edits the no_of_songs and duration of the playlist
        function makeChangestoAlbum($conn, $a_name, $songId) // done2
        {
            $result = searchSong($conn, $songId);
            $dur = 0;
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $dur = $row['duration'];
                // $sql = "UPDATE album SET duration = duration + $dur WHERE name = '$a_name' ";
                $sql = "UPDATE album SET duration = duration + $dur WHERE name = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $a_name);
                if ($stmt->execute() === TRUE) {
                    echo "New record created successfully";
                } else {
                 echo "Error: " . $sql . "<br>" . $conn->error;
                } 
                // $sql = "UPDATE album SET no_of_songs = no_of_songs + 1 WHERE name = '$a_name' ";
                $sql = "UPDATE album SET no_of_songs = no_of_songs + 1 WHERE name = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $a_name);
                if ($stmt->execute() === TRUE) {
                    echo "New record created successfully";
                } else {
                 echo "Error: " . $sql . "<br>" . $conn->error;
                } 
            }

            else
            {
                echo "song not found";
            }
            
        }

        function decreaseDurationToAlbum($conn, $song_id, $a_name)
        {
            $result = searchSong($conn, $song_id);
            $dur = 0;
            $row = $result->fetch_assoc();
            $dur = $row['duration'];
            echo $row['duration'];
            $sql = "UPDATE album SET duration = duration - $dur WHERE name = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $a_name);
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
            // $sql = "UPDATE album SET no_of_songs = no_of_songs + 1 WHERE name = '$a_name' ";
            $sql = "UPDATE album SET no_of_songs = no_of_songs - 1 WHERE name = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $a_name);
            if ($stmt->execute() === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            } 
        }

        // function that is called whenever a new song is added to a playlist which edits the no_of_songs and duration of the playlist
        function makeChangestoPlaylist($conn, $p_name, $songId) // done2
        {
            $result = searchSong($conn, $songId);
            $dur = 0;
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $dur = $row['duration'];
                $sql = "UPDATE playlist SET duration = duration + $dur WHERE name = '$p_name' ";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                 echo "Error: " . $sql . "<br>" . $conn->error;
                }  
                $sql = "UPDATE playlist SET no_of_songs = no_of_songs + 1 WHERE name = '$p_name' ";
                if ($conn->query($sql) === TRUE) {
                    echo "New record created successfully";
                } else {
                 echo "Error: " . $sql . "<br>" . $conn->error;
                }  
            }
            else
            {
                echo "Song not found";  
            }
            
        }

        function increaseSharesBought($conn, $user_username, $artist_username, $shares_bought, $new_balance)
        {
            $notify = 0;
            $sql = "UPDATE user_artist_share SET no_of_share_bought = $shares_bought WHERE user_username = '$user_username' AND artist_username = '$artist_username'";
            if ($conn->query($sql) === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }  
            $sql = "UPDATE account SET balance = $new_balance WHERE username = '$user_username'";
            $conn->query($sql);
            return $notify;
        }

        function saveUserPaymentInfo($conn, $username, $full_name, $email, $address, $city, $state, $zip, $card_name, $card_number)
        {
            $sql = "UPDATE account SET Full_name = '$full_name', email='$email', billing_address='$address', City = '$city', State='$state', ZIP = '$zip', Card_number='$card_number' WHERE username='$username'";
            $conn->query($sql);
        }

        function saveUserDepositInfo($conn, $username, $full_name, $email, $address, $city, $state, $zip, $transit_no, $inst_no, $account_no, $swift)
        {
            $sql = "UPDATE account SET Full_name = '$full_name', email='$email', billing_address='$address', City = '$city', State='$state', ZIP = '$zip', Transit_no='$transit_no', Inst_no='$inst_no', Account_no='$account_no', Swift='$swift' WHERE username='$username'";
            $conn->query($sql);
        }

        function purchaseCoins($conn, $username, $coins)
        {
            $notify = 0;
            $sql = "UPDATE account SET balance = balance + $coins WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }  
            return $notify;
        }

        function increaseArtistRate($conn, $artist_username)
        {
            $sql = "UPDATE account SET rate = rate + 0.013 WHERE username = '$artist_username'";
            $conn->query($sql);
        }

        function setOriginalValues($conn, $share_distributed, $price_per_share, $artist_username)
        {
            $sql = "UPDATE account SET Original_Share = '$share_distributed' WHERE username = '$artist_username'";
            $conn->query($sql);

            $sql = "UPDATE artist_per_share SET Original_Price = '$price_per_share' WHERE artist_username = '$artist_username'";
            $conn->query($sql);
        }

        function setArtistDistributedShare($conn, $share_distributed, $price_per_share, $artist_username)
        {
            $sql = "UPDATE account SET Share_Distributed = '$share_distributed' WHERE username = '$artist_username'";
            $conn->query($sql);

            $sql = "UPDATE artist_per_share SET price_per_share = '$price_per_share' WHERE artist_username = '$artist_username'";
            $conn->query($sql);
        }

        function increaseArtistDistributedShare($conn, $artist_username, $added_share)
        {
            $sql = "UPDATE account SET Share_Distributed = Share_Distributed + $added_share WHERE username = '$artist_username'";
            $conn->query($sql);
        }

        function decreaseArtistDistributedShare($conn, $artist_username)
        {
            $sql = "UPDATE account SET Share_Distributed = Share_Distributed - 1 WHERE username = '$artist_username'";
            $conn->query($sql);
        }

        function increaseArtistPricePerShare($conn, $artist_username)
        {
            $rate = 1.2;
            $sql = "UPDATE artist_per_share SET price_per_share = price_per_share + $rate WHERE artist_username = '$artist_username'";
            $conn->query($sql);
        }

        function withdrawCoins($conn, $username, $coins)
        {
            $notify = 0;
            $sql = "UPDATE account SET balance = balance - $coins WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }  
            return $notify;
        }

        function addSharesToArtist($conn, $artist_username, $shares_bought)
        {
            $sql = "UPDATE account SET Shares = $shares_bought WHERE username = '$artist_username'";
            $conn->query($sql);
        }

        function editPassword($conn, $username, $new_password)
        {
            $notify = 0;
            $sql = "UPDATE account SET password = '$new_password' WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }  
            return $notify;
        }

        function editEmail($conn, $username, $new_email)
        {
            $notify = 0;
            $sql = "UPDATE account SET email = '$new_email' WHERE username = '$username'";
            if ($conn->query($sql) === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }  
            return $notify;
        }

        function sellShares($conn, $user_username, $artist_username, $selling_share, $profit)
        {
            $notify = 0;
            $sql = "UPDATE user_artist_share SET no_of_share_bought = no_of_share_bought - '$selling_share' WHERE user_username = '$user_username' AND artist_username = '$artist_username'";
            if($conn->query($sql) == TRUE)
                $notify = 1;
            else
                $notify = 2;
            // echo $notify;
            $sql = "UPDATE account SET Shares = Shares - $selling_share WHERE username = '$artist_username'";
            $conn->query($sql);

            $sql = "UPDATE account SET balance = balance + $profit WHERE username = '$user_username'";
            $conn->query($sql);
            return $notify;
        }

        // if the user is an admin, it lets the user delete a Song as well as all relations that song has
        function deleteSong($conn, $songID) //done2
        {
            $notify = 0;
            $result = searchSong($conn, $songID);

            $result = searchAlbumBySong($conn, $songID);
                while($al_names = $result->fetch_assoc())
                    decreaseDurationToAlbum($conn, $songID, $al_names['album_name']);
            // $sql = "DELETE FROM album_song WHERE song_id = $songID";
            $sql = "DELETE FROM album_song WHERE song_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $songID);
                if ($stmt->execute() === TRUE) {
                    //echo "<script>alert('Song deleted successfully');</script>";
                    $notify = 1;
                    } else {
                    //echo "Error deleting record: " . $conn->error;
                    $notify = 2;
                    }
                // $sql = "DELETE FROM playlist_song WHERE song_id = $songID";
                $sql = "DELETE FROM playlist_song WHERE song_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $songID);
                if ($stmt->execute() === TRUE) {
                    //echo "Record deleted successfully";
                    } else {
                    //echo "Error deleting record: " . $conn->error;
                    }
                // $sql = "DELETE FROM producer_song WHERE song_id = $songID";
                $sql = "DELETE FROM producer_song WHERE song_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $songID);
                if ($stmt->execute() === TRUE) {
                    //echo "Record deleted successfully";
                    } else {
                    //echo "Error deleting record: " . $conn->error;
                    }

                // $sql = "DELETE FROM rating WHERE song_id = $songID";
                $sql = "DELETE FROM artist_song WHERE song_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $songID);
                if ($stmt->execute() === TRUE) {
                   // echo "Record deleted successfully";
                    } else {
                    //echo "Error deleting record: " . $conn->error;
                    }
                

                $sql = "DELETE FROM rating WHERE song_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $songID);
                if ($stmt->execute() === TRUE) {
                    //echo "Record deleted successfully";
                    } else {
                    //echo "Error deleting record: " . $conn->error;
                    }

                // $sql = "DELETE FROM song WHERE id = '$songID'";
                $sql = "DELETE FROM song WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('i', $songID);
                if ($stmt->execute() === TRUE) {
                    $notify = 1;
                } else {
                    $notify = 2;
                }
            return $notify;
        }

        function removeSongFromAlbum($conn, $album_name, $song_id)
        {
            $sql = "DELETE FROM album_song WHERE album_name = ? AND song_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('si', $album_name, $song_id);
            $stmt->execute();

            $sql = "UPDATE song SET album_name = NULL WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $songId);
            $stmt->execute();

            $result = searchSong($conn, $song_id);
            $dur = 0;
            if ($result->num_rows > 0)
            {
                $row = $result->fetch_assoc();
                $dur = $row['duration'];
                // $sql = "UPDATE album SET duration = duration + $dur WHERE name = '$a_name' ";
                $sql = "UPDATE album SET duration = duration - $dur WHERE name = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $album_name);
                $stmt->execute();
                // $sql = "UPDATE album SET no_of_songs = no_of_songs + 1 WHERE name = '$a_name' ";
                $sql = "UPDATE album SET no_of_songs = no_of_songs - 1 WHERE name = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('s', $album_name);
                $stmt->execute();
            }
        }

        function deleteAlbum($conn, $album_name, $artist_username)
        {
            $notify = 0;
            $sql = "UPDATE song SET album_name = NULL WHERE album_name = '$album_name'";
            $conn->query($sql);

            $sql = "DELETE FROM album_song WHERE album_name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $album_name);
            $stmt->execute();

            $sql = "DELETE FROM artist_album WHERE album_name = ? AND artist_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $album_name, $artist_username);
            $stmt->execute();

            $sql = "DELETE FROM album WHERE name = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $album_name);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
            } else {
                $notify = 2;
            }
            return $notify;
        }

        //if the user is an admin, it lets the user delete a rating made by a specific user on a specific song 
        function deleteRating($conn, $username, $songId) //done2
        {
            $notify = 0;
            // $sql = "DELETE FROM rating WHERE song_id = $songId AND user_username = '$username'";
            $sql = "DELETE FROM rating WHERE song_id = ? AND user_username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('is', $songId, $username);
            if ($stmt->execute() === TRUE) {
                $notify = 1;
                echo "Record deleted successfully";
                } else {
                    $notify = 2;
                echo "song not found " . $conn->error;
                }
                return $notify;
        }

        //uses the DELETE keyword for SQL and remove a song in that playlist
        //after removing that song from that playlist in the playlist_song table, query for the song info using the searchSrong function
        //the return value of the searchSong function will contain the duration of the song
        //using the duration of this song to reduce the duration of the playlist and decrease the no_of_songs in the playlist by 1 as well
        function removeSongFromPlaylist($conn, $playlist_name, $songID, $username) //done2
        {
            $playlist = searchPlaylist($conn, $playlist_name);

            if ($playlist->num_rows > 0) {
                //     // output data of each row
                    while($row = $playlist->fetch_assoc()) {
                        if($row["user_username"] == $username)
                        {
                            $sql = "DELETE FROM playlist_song WHERE song_id = $songID AND playlist_name = '$playlist_name'";
                            if ($conn->query($sql) === TRUE) {
                                echo "Record deleted successfully";
                                } else {
                                echo "song not found " . $conn->error;
                                }
                            
                            $dur = 0;
                            $result = searchSong($conn, $songID);
                            if ($result->num_rows > 0)
                            {
                                $row = $result->fetch_assoc();
                                $dur = $row['duration'];
                                $sql = "UPDATE playlist SET no_of_songs = no_of_songs - 1 WHERE name = '$playlist_name'";
                                if ($conn->query($sql) === TRUE) {
                                    echo "Record deleted successfully";
                                    } else {
                                    echo "song not found " . $conn->error;
                                    }
                                $sql = "UPDATE playlist SET duration = duration - $dur WHERE name = '$playlist_name'";
                                if ($conn->query($sql) === TRUE) {
                                    echo "Record deleted successfully";
                                    } else {
                                    echo "song not found " . $conn->error;
                                    }
                            }
                            else
                                echo "song not found";
                        }
                    }
                }
        }
?>