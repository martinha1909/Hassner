<?php
    function printParticipatingCampaignTable($username)
    {
        $artists = array();
        $offerings = array();
        $progress = array();
        $time_left = array();
        $minimum_ethos = array();
        $owned_ethos = array();
        $types = array();
        $chances = array();

        fetchInvestedArtistCampaigns(
            $username,
            $artists,
            $offerings,
            $progress,
            $time_left,
            $minimum_ethos,
            $owned_ethos,
            $types,
            $chances
        );

        if (sizeof($offerings) > 0) 
        {
            echo '
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Artist</th>
                            <th scope="col">Offering</th>
                            <th scope="col">Progess</th>
                            <th scope="col">Time left</th>
                            <th scope="col">Minimum Ethos</th>
                            <th scope="col">Owned Ethos</th>
                            <th scope="col">Chance of winning</th>
                            </form>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for ($i = 0; $i < sizeof($artists); $i++) {
                echo '
                            <tr>
                                <th>' . $artists[$i] . '</th>
                                <td>' . $offerings[$i] . '</td>
                                <td>' . round($progress[$i], 2) . '%</td>
                                <td>' . $time_left[$i] . '</td>
                                <td>' . $minimum_ethos[$i] . '</td>
                                <td>' . $owned_ethos[$i] . '</td>
                ';
                if ($chances[$i] != -1) {
                    echo '
                                    <form action="../../backend/listener/IncreaseChanceBackend.php" method="post">
                                        <td>' . round($chances[$i], 2) . '%<input name = "artist_name[' . $artists[$i] . ']" type = "submit" id="abc" class="no-background" role="button" aria-pressed="true" value = " +"></td>
                                    </form>
                    ';
                } else {
                    echo '
                                    <td>N/A</td>
                    ';
                }

                echo '
                                <td>' . $types[$i] . '</td>
                            </tr>
                ';
            }
            echo '
                        </tbody>
                    </table>
            ';
        }
    }

    function printPastParticipatedCampaignTable($username)
    {
        $artists = array();
        $offerings = array();
        $minimum_ethos = array();
        $winners = array();
        $time_releases = array();
        $types = array();
        fetchParticipatedCampaigns(
            $username,
            $artists,
            $offerings,
            $minimum_ethos,
            $winners,
            $time_releases,
            $types
        );

        if (sizeof($offerings) > 0) 
        {
            echo '
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Artist</th>
                                <th scope="col">Offering</th>
                                <th scope="col">Minimum Ethos</th>
                                <th scope="col">Winner</th>
                                <th scope="col">Type</th>
                                <th scope="col">Date Released</th>
                            </tr>
                        </thead>
                        <tbody>
            ';

            for ($i = 0; $i < sizeof($artists); $i++) 
            {
                if ($winners[$i] == $username) 
                {
                    echo '
                                <tr>
                                    <th class="campaign_winner">' . $artists[$i] . '</th>
                                    <td class="campaign_winner">' . $offerings[$i] . '</td>
                                    <td class="campaign_winner">' . $minimum_ethos[$i] . '</td>
                                    <td class="campaign_winner">' . $winners[$i] . '</td>
                                    <td class="campaign_winner">' . $types[$i] . '</td>
                                    <td class="campaign_winner">' . $time_releases[$i] . '</td>
                                </tr>
                    ';
                } 
                else 
                {
                    echo '
                                <tr>
                                    <th>' . $artists[$i] . '</th>
                                    <td>' . $offerings[$i] . '</td>
                                    <td>' . $minimum_ethos[$i] . '</td>
                                    <td>' . $winners[$i] . '</td>
                                    <td>' . $types[$i] . '</td>
                                    <td>' . $time_releases[$i] . '</td>
                                </tr>
                    ';
                }
            }
            echo '
                            </tbody>
                        </table>
            ';
        } 
        else 
        {
            echo '<h5>No campaigns participated</h5>';
        }
    }

    function printNearParticipationCampaignTable($username)
    {
        
    }
?>