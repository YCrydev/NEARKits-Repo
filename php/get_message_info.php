<?php
include "database_connection.php";
$message_id  = $_POST["message_id"].".png";
$account_id  = $_POST["account_id"];
    $query="SELECT message_timestamp,link_status FROM nft_messages 
        WHERE
        nft_image=:nft_image";
        $stmt= $connect->prepare($query);
        $stmt->bindParam(":nft_image",$message_id);
    if($stmt->execute()){ 
        $count = $stmt->rowCount();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if($count>0){
          
        foreach($result as $row) {
    $message_timestamp_format = time_elapsed_string($row['message_timestamp']);
    $message_timestamp = $row['message_timestamp'];
}
 $query="SELECT link_status FROM nft_messages 
        WHERE
        nft_image=:nft_image 
        AND to_user=:to_user";
        $stmt= $connect->prepare($query);
        $stmt->bindParam(":nft_image",$message_id);  
        $stmt->bindParam(":to_user",$account_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if($count>0){
 foreach($result as $row) {
    $link_status= $row['link_status'];
}
}else{
      $link_status= 0;
}
}else{
   $message_timestamp = "Before Index"; 
   $message_timestamp_format = "Before Index"; 
   $link_status= 0;
}
    } 
    $output = array(
 "message_timestamp"    => $message_timestamp,
 "message_timestamp_format"    => $message_timestamp_format,
 "link_status" =>$link_status
);
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
echo json_encode($output);
?>