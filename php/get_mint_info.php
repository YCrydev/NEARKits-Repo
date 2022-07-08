<?php

$db_connection = pg_connect("host=104.199.89.51 port=5432 dbname=mainnet_explorer user=public_readonly password=nearprotocol");
 $result = pg_query($db_connection, "select 
    date_trunc('minute', to_timestamp(block_timestamp/1000/1000/1000)) as time,
    signer_account_id as signer,
    receiver_account_id as receiver
from 
    transactions t
where
    receiver_account_id = 'ycrydev.near'");
    print_r($result);
    
//      $myPDO = new PDO('pgsql:host=mainnet.db.explorer.indexer.near.dev;dbname=mainnet_explorer', 'public_readonly', 'nearprotocol');
     
//       $result = $myPDO->query("select 
//     date_trunc('minute', to_timestamp(block_timestamp/1000/1000/1000)) as time,
//     signer_account_id as signer,
//     receiver_account_id as receiver
// from 
//     transactions t
// where
//     receiver_account_id = 'ycrydev.near'");
//       print_r($result);
?>