<?php

require_once("json-connect.php");

$json_connect = new JsonConnect("vote");

$vote_name = "asdfasdf";
$sub_email = "";
$curr_vote = true;
$err_string = "";

$all_good = true;
$is_member = false;

if(isset($_REQUEST["vote_name"])) {
    if(ctype_alpha($_REQUEST["vote_name"])) {
        $vote_name = trim($_REQUEST["vote_name"]);
    } else {
        $err_string = "The `Vote ID` can only be alphabetic";
        $all_good = false;
    }
}

if(isset($_REQUEST["email"])) {
    $sub_email = trim($_REQUEST["email"]);    
} else {
    $all_good = false;
}

if($sub_email == "") {
    $all_good = false;  
}

if(isset($_REQUEST["vote"])) {
    if($_REQUEST["vote"] == "yes") {
        $curr_vote = true;
        $vote_text = "yes";
    } else {
        $curr_vote = false;
        $vote_text = "no";
    }
} else {
    $all_good = false;
}

if($all_good) {
    $is_member = $json_connect->set_vote_json($vote_name, $sub_email, $curr_vote);
}

?>

<html>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-indigo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <head>
        <title>PTA Vote</title>
    </head>
    <body style="height:100%">
        <div class="w3-row w3-padding w3-theme-d2 w3-xlarge" style="min-height:100%;">
        <?php if(!$all_good) { ?>
            <form action="/castvote.php" method="get">
                <br/>
                <label for="vote_name">Vote ID:</label>
                <input type="text" id="vote_name" name="vote_name" value="<?=$vote_name?>" readonly />
                
                <br/><br/>
                <label for="email">Registered Email or Name:</label>
                <input type="text" id="email" name="email" value="<?=htmlspecialchars($sub_email)?>" />
                
                <br/><br/>
                <label for="vote">Vote:</label>
                <select id="vote" name="vote">
                    <option value="yes" <?=($curr_vote ? "selected" : "")?>>Yes</option>
                    <option value="no" <?=(!$curr_vote ? "selected" : "")?>>No</option>
                </select>
                
                <br/><br/>
                <input type="submit" value="Submit Vote" />
            </form>
        <?php } else { ?>
            <br/>
            <h3>Thank you for voting!</h3>
            <?php if($is_member) { ?>
                <p>Your vote of <?php echo $vote_text; ?> has been counted</p>
            <?php } else { ?>
                <p>
                    As a non-member your vote of <?php echo $vote_text; ?> has been heard and noted
                </p>
                <p>
                    To affect future direction of the SJLES PTA, 
                    please consider <a href="https://sjles.memberhub.com/store?category=Memberships">becoming a member</a>
                </p> 
            <?php } ?>
        <?php } ?>
        </div>
    </body>
</html>