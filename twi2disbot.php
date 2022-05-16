<?php
    #Database Details
    $servername = "ip or dns-name";
    $username = "admin";
    $password = "#secure2";
    $dbname = "database";
    #Twitter API Bearer Key
    $authorization = 'Authorization: Bearer Key';
    #Discord Webhook URL
    $url = "https://discord.com/api/webhooks/";
    #Database Query
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT * FROM `twi2disbot`";
    $result = $conn->query($sql);
    $conn->close();
    while($row = $result->fetch_assoc()) {
        $twitteruserid = $row["twitteruserid"];
        $twitterusername = $row["twitterusername"];
        $lastweet = $row["lasttweet"];
        #get latest tweet form user
        $url = "https://api.twitter.com/2/users/$twitteruserid/tweets?max_results=5&exclude=retweets,replies";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $resultc = curl_exec($ch);
        curl_close($ch);
        $resultc = json_decode($resultc);
        $tweetid = $resultc->meta->newest_id;
        #post tweet in discord (only if "new")
        if($tweetid != $lastweet){
            $headers = [ 'Content-Type: application/json; charset=utf-8' ];
            $POST = [ 'username' => 'Displayname of Webhook', 'content' => "https://twitter.com/$twitterusername/status/$tweetid" ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
            $response   = curl_exec($ch);
            #Update Database with new Tweetid
            $conn = new mysqli($servername, $username, $password, $dbname);
            $sql = "UPDATE `twi2disbot` SET `lasttweet`='$tweetid' WHERE `twitteruserid`='$twitteruserid'";
            $conn->query($sql);
            $conn->close();
        }
        
    }
  
?>
