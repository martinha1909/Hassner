<?php
    include 'connection.php';
    // include 'logic.php';
    

    $conn = connect();
    $album_name = "New Mania";
    $sql = "DELETE FROM album_song WHERE album_name = ? AND song_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $album_name, $song_id);
    $stmt->execute();
    // increaseArtistRate($conn, 'Travis Scott');
        
        


    // echo json_encode($output, JSON_PRETTY_PRINT);
    closeCon($conn);
?>