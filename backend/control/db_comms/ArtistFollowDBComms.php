<?php
    function followArtist($conn, $user_username, $artist_username)
    {
        $sql = "INSERT INTO artist_followers (artist_username, user_username)
                VALUES(?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $artist_username, $user_username);
        $stmt->execute();
    }

    function unFollowArtist($conn, $user_username, $followed_artist)
    {
        $sql = "DELETE FROM artist_followers WHERE artist_username = ? AND user_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $followed_artist, $user_username);
        $stmt->execute();
    }
?>