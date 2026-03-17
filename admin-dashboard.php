<?php

require_once("json-connect.php");

$json_connect = new JsonConnect("vote");

$vote_name = "";

if(isset($_REQUEST["vote_name"])) {
    if(ctype_alpha($_REQUEST["vote_name"])) {
        $vote_name = trim($_REQUEST["vote_name"]);
        $json_connect->create_vote_json($vote_name);
    } else {
        $err_string = "The `Vote ID` can only be alphabetic";
    }
}

$vote_table = "";
$member_list = "";
if($vote_name != "") {
    $vote_json = $json_connect->get_vote_json($vote_name);

    $vote_link = "https://sjlespta.halvo.me/castvote.php?vote_name=$vote_name";

    $vote_table = "
        <h2>Member Votes:</h2>
        <table class='w3-table-all w3-card-4' style='width:auto;'>
            <tr>
                <th colspan='2'>Member Votes</th>
            </tr>
            <tr>
                <th>Yes</th>
                <td>$vote_json->member_yes</td>
            </tr>
            <tr>
                <th>No</th>
                <td>$vote_json->member_no</td>
            </tr>
        </table>
        <h2>Non-Member Votes</h2>
        <table class='w3-table-all w3-card-4' style='width:auto;'>
            <tr>
                <th colspan='2'>Non Member Votes</th>
            </tr>
            <tr>
                <th>Yes</th>
                <td>$vote_json->non_member_yes</td>
            </tr>
            <tr>
                <th>No</th>
                <td>$vote_json->non_member_no</td>
            </tr>
        </table>
    ";

    $member_emails = print_r($vote_json->member_emails, true);
    $non_member_emails = print_r($vote_json->non_member_emails, true);

    $member_list = "
        <h2>Members Who Voted</h2>
        <pre>$member_emails</pre>
        <br/>
        <h2>Non-Members Who Voted</h2>
        <pre>$non_member_emails</pre>
    ";
}

?>

<html>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <head>
        <title>SJLES PTA Vote Dashboard</title>
    </head>
    <body style="height:100%">
        <div class="w3-row w3-padding w3-theme-d2 w3-xlarge" style="min-height:100%">
            <form action="/dashboard.php" method="get">
                <label for="vote_name">Vote ID:</label>
                <input type="text" id="vote_name" name="vote_name" value="<?=$vote_name?>" />
            </form>
            <br/>
            <a href="<?=$vote_link?>"><?=$vote_link?></a>
            <br/>
            <br/>
            <?=$vote_table?>
            <br/>
            <?=$member_list?>
        </div>
    </body>
</html>