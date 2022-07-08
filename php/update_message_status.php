<?php
include "database_connection.php";
$message_id  = $_POST["message_id"];
$account_id  = $_POST["account_id"];
 $query="SELECT link_status FROM nft_messages 
        WHERE
        nft_image=:nft_image 
        AND to_user=:to_user
        AND link_status='active'";
        $stmt= $connect->prepare($query);
        $stmt->bindParam(":nft_image",$message_id);  
        $stmt->bindParam(":to_user",$account_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count>0){
$query="UPDATE nft_messages 
        SET
        link_status='recieved'
        WHERE
        nft_image=:nft_image 
        AND to_user=:to_user";
        $stmt= $connect->prepare($query);
        $stmt->bindParam(":nft_image",$message_id);  
        $stmt->bindParam(":to_user",$account_id);
        $stmt->execute();
        $count = $stmt->rowCount();
        echo "updated";
}else{
    echo "exists";
}
 

?>