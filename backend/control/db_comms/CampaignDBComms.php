<?php
    function searchArtistCampaigns($conn, $artist_username)
    {
        $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active FROM campaign WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchArtistActiveCampaignsID($conn, $artist_username)
    {
        $sql = "SELECT id FROM campaign WHERE artist_username = ? AND is_active = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    } 

    function searchUserParticipatingCampaign($conn, $user_username)
    {
        $result = 0;

        $sql = "SELECT campaign_id FROM campaign_participant WHERE user_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchUserSpecificParticipatingCampaign($conn, $user_username, $campaign_id)
    {
        $result = 0;

        $sql = "SELECT campaign_id FROM campaign_participant WHERE user_username = ? AND campaign_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $user_username, $campaign_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchArtistCampaignsByExpDateNotEnough($conn, $artist_username, $user_owned_shares)
    {
        $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active 
                FROM campaign 
                WHERE artist_username = ? AND minimum_ethos > ? AND is_active = 0
                ORDER BY minimum_ethos ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $artist_username, $user_owned_shares);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchTrendingCampaign($conn)
    {
        $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active
                FROM campaign
                ORDER BY eligible_participants DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchCampaignWinnerByArtist($conn, $artist_username)
    {
        $sql = "SELECT winner FROM campaign WHERE artist_username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $artist_username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchCampaignEligibleParticipants($conn, $campaign_id)
    {
        $sql = "SELECT eligible_participants FROM campaign WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchCampaignMinimumEthos($conn, $campaign_id)
    {
        $sql = "SELECT minimum_ethos, artist_username FROM campaign WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchCampaignsByType($conn, $campaign_type)
    {
        $sql = "SELECT id, artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active FROM campaign WHERE type = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $campaign_type);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchMaxCampaignID($conn)
    {
        $ret = 0;

        $sql = "SELECT MAX(id) AS max_campaign_id FROM campaign";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $ret = $result->fetch_assoc();

        return $ret['max_campaign_id'];
    }

    function searchCampaignByID($conn, $id)
    {
        $reult = 0;

        $sql = "SELECT * FROM campaign WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function searchCampaignActiveStatus($conn, $campaign_id)
    {
        $reult = 0;

        $sql = "SELECT is_active FROM campaign WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $campaign_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function decreaseCampaignEligibleParticipant($connPDO, $campaign_id, $reduce_number)
    {
        $status = StatusCodes::NONE;

        try
        {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("UPDATE campaign SET eligible_participants = eligible_participants - ? WHERE id = ?");
            $stmt->bindValue(1, $reduce_number);
            $stmt->bindValue(2, $campaign_id);
            $stmt->execute(array($reduce_number, $campaign_id));

            $connPDO->commit();
            $status = StatusCodes::Success;
        }
        catch (PDOException $e) 
        {
            $connPDO->rollBack();
            hx_error(HX::DB, "DB error occured: " . $e->getMessage());
            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }

    function increaseCampaignEligibleParticipant($connPDO, $campaign_id, $increase_number)
    {
        $status = StatusCodes::NONE;

        try
        {
            $connPDO->beginTransaction();

            $stmt = $connPDO->prepare("UPDATE campaign SET eligible_participants = eligible_participants + ? WHERE id = ?");
            $stmt->bindValue(1, $increase_number);
            $stmt->bindValue(2, $campaign_id);
            $stmt->execute(array($increase_number, $campaign_id));

            $connPDO->commit();
            $status = StatusCodes::Success;
        }
        catch (PDOException $e) 
        {
            $connPDO->rollBack();
            hx_error(HX::DB, "DB error occured: " . $e->getMessage());
            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }

    function updateCampaignEligibleParticipants($conn, $campaign_id, $eligible_participant)
    {
        $sql = "UPDATE campaign SET eligible_participants = '$eligible_participant' WHERE id='$campaign_id'";
        $conn->query($sql);
    }

    function updateRaffleCampaignWinner($conn, $campaign_id, $winner)
    {
        $sql = "UPDATE campaign SET winner = '$winner' WHERE id='$campaign_id'";
        $conn->query($sql);
    }

    function updateCampaignActiveStatus($conn, $campaign_id, $is_active)
    {
        $sql = "UPDATE campaign SET is_active = '$is_active' WHERE id='$campaign_id'";
        $conn->query($sql);
    }

    function postCampaign($conn, $artist_username, $offering, $release_date, $expiration_date, $type, $minimum_ethos)
    {
        $status = 0;
        $eligible_participant = 0;
        $winner = NULL;
        $is_active = 1;

        $sql = "INSERT INTO campaign (artist_username, offering, date_posted, date_expires, type, minimum_ethos, eligible_participants, winner, is_active)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssdisi', $artist_username, $offering, $release_date, $expiration_date, $type, $minimum_ethos, $eligible_participant, $winner, $is_active);
        if($stmt->execute() == TRUE)
        {
            $status = StatusCodes::Success;
        }
        else
        {
            $status = StatusCodes::ErrServer;
        }

        return $status;
    }

    function addToCampaignParticipant($conn, $user_username, $campaign_id)
    {
        $status = StatusCodes::NONE;

        $sql = "INSERT INTO campaign_participant (user_username, campaign_id)
                VALUES(?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $user_username, $campaign_id);
        if($stmt->execute() == true)
        {
            $status = StatusCodes::Success;
        }
        else
        {
            $msg = "db error occured: ".$conn->mysqli_error($conn);
            hx_error(HX::DB, $msg);
            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }

    function removeCampaignParticipant($conn, $user_username, $campaign_id)
    {
        $status = 0;

        $sql = "DELETE FROM campaign_participant WHERE user_username = ? AND campaign_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $user_username, $campaign_id);
        if($stmt->execute() == true)
        {
            $status = StatusCodes::Success;
        }
        else
        {
            $msg = "db error occured: ".$conn->mysqli_error($conn);
            hx_error(HX::DB, $msg);
            $status = StatusCodes::ErrGeneric;
        }

        return $status;
    }
?>