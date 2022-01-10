<?php
    function printParticipatingCampaignTable($username)
    {
        $participating_campaigns = fetchInvestedArtistCampaigns($username);

        if (sizeof($participating_campaigns) > 0) 
        {
            echo '
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Artist</th>
                            <th scope="col">Offering</th>
                            <th scope="col">Progess</th>
                            <th scope="col">⏳</th>
                            <th scope="col">Minimum Ethos</th>
                            <th scope="col">Owned Ethos</th>
                            <th scope="col">Chance of winning</th>
                            </form>
                            <th scope="col">Type</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for ($i = 0; $i < sizeof($participating_campaigns); $i++) {
                echo '
                            <tr>
                                <th>' . $participating_campaigns[$i]->getArtistUsername() . '</th>
                                <td>' . $participating_campaigns[$i]->getOffering() . '</td>
                                <td>' . round($participating_campaigns[$i]->getProgress(), 2) . '%</td>
                                <td>' . $participating_campaigns[$i]->getTimeLeft() . '</td>
                                <td>' . $participating_campaigns[$i]->getMinEthos() . '</td>
                                <td>' . $participating_campaigns[$i]->getUserOwnedEthos() . '</td>
                ';
                if ($participating_campaigns[$i]->getWinningChance() != -1) {
                    echo '
                                    <form action="../../backend/listener/IncreaseChanceBackend.php" method="post">
                                        <td>' . $participating_campaigns[$i]->getWinningChance() . '%<input name = "artist_name[' . $participating_campaigns[$i]->getArtistUsername() . ']" type = "submit" id="abc" class="no-background" role="button" aria-pressed="true" value = " +"></td>
                                    </form>
                    ';
                } else {
                    echo '
                                    <td>N/A</td>
                    ';
                }

                echo '
                                <td>' . $participating_campaigns[$i]->getType() . '</td>
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
        $participated_campaigns = fetchParticipatedCampaigns($username);

        if (sizeof($participated_campaigns) > 0) 
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

            for ($i = 0; $i < sizeof($participated_campaigns); $i++) 
            {
                if ($participated_campaigns[$i]->getWinner() == $username) 
                {
                    echo '
                                <tr>
                                    <th class="campaign_winner">' . $participated_campaigns[$i]->getArtistUsername() . '</th>
                                    <td class="campaign_winner">' . $participated_campaigns[$i]->getOffering() . '</td>
                                    <td class="campaign_winner">' . $participated_campaigns[$i]->getMinEthos() . '</td>
                                    <td class="campaign_winner">' . $participated_campaigns[$i]->getWinner() . '</td>
                                    <td class="campaign_winner">' . $participated_campaigns[$i]->getType() . '</td>
                                    <td class="campaign_winner">' . $participated_campaigns[$i]->getDatePosted() . '</td>
                                </tr>
                    ';
                } 
                else 
                {
                    echo '
                                <tr>
                                    <th>' . $participated_campaigns[$i]->getArtistUsername() . '</th>
                                    <td>' . $participated_campaigns[$i]->getOffering() . '</td>
                                    <td>' . $participated_campaigns[$i]->getMinEthos() . '</td>
                                    <td>' . $participated_campaigns[$i]->getWinner() . '</td>
                                    <td>' . $participated_campaigns[$i]->getType() . '</td>
                                    <td>' . $participated_campaigns[$i]->getDatePosted() . '</td>
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
        $near_parti_campaigns = fetchNearParticipationCampaign($username);

        if (sizeof($near_parti_campaigns) > 0) 
        {
            echo '
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Artist</th>
                                <th scope="col">Offering</th>
                                <th scope="col">Progress</th>
                                <th scope="col">Type</th>
                                <th scope="col">⏳</th>
                            </tr>
                        </thead>
                        <tbody>
            ';

            for ($i = 0; $i < sizeof($near_parti_campaigns); $i++) 
            {
                echo '
                            <tr>
                                <th>' . $near_parti_campaigns[$i]->getArtistUsername() . '</th>
                                <td>' . $near_parti_campaigns[$i]->getOffering() . '</td>
                                <td>' . $near_parti_campaigns[$i]->getUserOwnedEthos() . '/'. $near_parti_campaigns[$i]->getMinEthos() .' ('.$near_parti_campaigns[$i]->getProgress().')</td>
                                <td>' . $near_parti_campaigns[$i]->getType() . '</td>
                                <td>' . $near_parti_campaigns[$i]->getTimeLeft() . '</td>
                            </tr>
                ';
            }
            echo '
                            </tbody>
                        </table>
            ';
        }
    }

    function printArtistCurrentCampaignTable($artist_username)
    {
        $current_campaigns = fetchArtistCurrentCampaigns($artist_username);

        if (sizeof($current_campaigns) > 0) {
            echo '
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Offering</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Eligible Participants</th>
                                    <th scope="col">Minimum Ethos</th>
                                    <th scope="col">Time left</th>
                                    <th scope="col">Roll Result</th>
                                    <th scope="col">Time Released</th>
                                </tr>
                            </thead>
                            <tbody>';

            for ($i = 0; $i < sizeof($current_campaigns); $i++) {
                echo '
                                <tr>
                                    <th>' . $current_campaigns[$i]->getOffering() . '</th>
                                    <td>' . $current_campaigns[$i]->getType() . '</td>
                                    <td>' . $current_campaigns[$i]->getEligibleParticipants() . '</td>
                                    <td>' . $current_campaigns[$i]->getMinEthos() . '</td>
                                    <td>' . $current_campaigns[$i]->getTimeLeft() . '</td>
                                    <td>' . $current_campaigns[$i]->getWinner() . '</td>
                                    <td>' . $current_campaigns[$i]->getDatePosted() . '</td>
                                </tr>
                    ';
            }
            echo '
                            </tbody>
                        </table>
                ';
        }
    }

    function printArtistExpiredCampaignTable($artist_username)
    {
        $expired_campaigns = fetchArtistExpiredCampaigns($artist_username);

        if (sizeof($expired_campaigns) > 0) 
        {
            echo '
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Offering</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Eligible Participants</th>
                                        <th scope="col">Minimum Ethos</th>
                                        <th scope="col">Roll Result</th>
                                        <th scope="col">Time Released</th>
                                    </tr>
                                </thead>
                                <tbody>
            ';

            for ($i = 0; $i < sizeof($expired_campaigns); $i++) 
            {
                echo '
                                    <tr>
                                        <th>' . $expired_campaigns[$i]->getOffering() . '</th>
                                        <td>' . $expired_campaigns[$i]->getType() . '</td>
                                        <td>' . $expired_campaigns[$i]->getEligibleParticipants() . '</td>
                                        <td>' . $expired_campaigns[$i]->getMinEthos() . '</td>
                                        <td>' . $expired_campaigns[$i]->getWinner() . '</td>
                                        <td>' . $expired_campaigns[$i]->getDatePosted() . '</td>
                                    </tr>
                ';
            }
            echo '
                                </tbody>
                            </table>
            ';
        }
    }

    function printArtistApexInvestors($artist_username)
    {
        $investors = fetchAllInvestorsOfArtist($artist_username);

        if(sizeof($investors) > 0)
        {
            echo '
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
            ';
            if($_SESSION['artist_investor_amount_invested_sort'] == 0)
            {
                echo '
                            <form action = "../../backend/artist/include/SortInvestorAmountInvested.php" method="post">
                                <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Amount Invested ($) ↑"></th>
                            </form>
                ';
            }
            else if($_SESSION['artist_investor_amount_invested_sort'] == 1)
            {
                echo '
                            <form action = "../../backend/artist/include/SortInvestorAmountInvested.php" method="post">
                                <th scope="col"><input type = "submit" class="th-dark" role="button" aria-pressed="true" value = "Amount Invested ($) ↓"></th>
                            </form>
                ';
            }
            echo '
                            <th scope="col">Campaigns Participated</th>
                            <th scope="col">Campaigns Won</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for ($i = 0; $i < sizeof($investors); $i++) 
            {
                echo '
                                    <tr>
                                        <th>' . $investors[$i]->getUsername() . '</th>
                                        <td>' . $investors[$i]->getEmail() . '</td>
                                        <td>' . $investors[$i]->getAmountInvested() . '</td>
                                        <td>' . $investors[$i]->getCampaignsParticipated() . '</td>
                                        <td>' . $investors[$i]->getCampaignsWon() . '</td>
                                    </tr>
                ';
            }
            echo '
                                </tbody>
                            </table>
            ';
        }
        else
        {
            echo '
                <h4>No investors found</h4>
            ';
        }
    }

    function printArtistRaffleCampaignsWinners($artist_username)
    {
        $campaign_info = array();
        $campaign_winners = fetchArtistCampaignWinners($artist_username, $campaign_info);

        if(sizeof($campaign_winners) > 0)
        {
            echo '
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Username</th>
                            <th scope="col">Email</th>
                            <th scope="col">Amount Invested ($)</th>
                            <th scope="col">Fulfilled</th>
                            <th scope="col">Campaign Offering</th>
                            <th scope="col">End Date</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            for ($i = 0; $i < sizeof($campaign_winners); $i++) 
            {
                echo '
                                    <tr>
                                        <th>' . $campaign_winners[$i]->getUsername() . '</th>
                                        <td>' . $campaign_winners[$i]->getEmail() . '</td>
                                        <td>' . $campaign_winners[$i]->getAmountInvested() . '</td>
                ';
                if($campaign_info[$i]->getDeliverProgress() == CampaignDeliverProgress::POSITIVE)
                {
                    echo '<td>✔️</td>';
                }
                else if($campaign_info[$i]->getDeliverProgress() == CampaignDeliverProgress::NEGATIVE)
                {
                    echo '<td>❌</td>';
                }
                elseif($campaign_info[$i]->getDeliverProgress() == CampaignDeliverProgress::IN_PROGRESS)
                {
                    echo '<td>⌛</td>';
                }


                echo '
                                        <td>' . $campaign_info[$i]->getOffering() . '</td>
                                        <td>' . $campaign_info[$i]->getDateExpires() . '</td>
                                    </tr>
                ';
            }
            echo '
                                </tbody>
                            </table>
            ';
        }
        else
        {
            echo '
                <h4>No investors found</h4>
            ';
        }
    }
?>