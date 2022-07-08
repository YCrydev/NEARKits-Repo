<?php
$output = array();


$account_id = $_POST["account_id"];
$page = $_POST["page"];
$skip = 0;
 $page_load='';
 //$account_id = "thecryptoasian.near";
$limit = 25;
$rowcount=0;
while($skip <=400){
    load_page_content($skip,$account_id);
    $skip = $skip+1;
}
function load_page_content($skip,$account_id){
        $load_this ="https://nearblocks.io/api/account/txns?address=".$account_id."&limit=".$limit."&offset=".($skip*25)."";
$curl = curl_init();
curl_setopt_array($curl, array(
 CURLOPT_URL => $load_this,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "
-----011000010111000001101001--"
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $data_json = json_decode($response);
   $data = json_decode($response, true);
   $rowcount="";
  
}

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  $data = json_decode($response, true);
  $rowcount=0;
foreach($data as $key => $Sub_value) {
      if($key === "txns"){
        
            foreach($Sub_value as $sub_key_info => $Sub_value_info) {
                      if(($Sub_value_info["type"] == "Buy" || $Sub_value_info["type"] == "Transfer" || $Sub_value_info["type"] == "Nft Mint")){
                $sub_array = array();
                 if(check_status($Sub_value_info["transaction_hash"])){
                  $type = $Sub_value_info["type"];
                  if ( $type=== null){
                    $type="";
                  }else{

                      if($rowcount==(25)){
                          global $skip;
                          $skip=401;
                      }
                      global $page_load;
                      
                      switch ($type) {
                          case "Buy":
                              if($Sub_value_info["from"]==$account_id){
                             $type = '<btn class="btn-success btn-sm mb-0"  style="font-size: 12px;">Bought</btn>' ;
                             $direction='<btn class="btn-danger btn-sm mb-0"  style="font-size: 12px;">Out</btn>';
                              }else{
                                 $type = '<btn class="btn-warning btn-sm mb-0"  style="font-size: 12px;">Sold</btn>' ; 
                                 $direction='<btn class="btn-success btn-sm mb-0"  style="font-size: 12px;">In</btn>';
                              }
                              break;
                        case "Transfer":
                             $type = '<btn class="btn-success btn-sm mb-0"  style="font-size: 12px;">Transfer</btn>' ;
                             if($Sub_value_info["from"]==$account_id){
                             $direction='<btn class="btn-danger btn-sm mb-0"  style="font-size: 12px;">Out</btn>';
                             }else{
                                    $direction='<btn class="btn-success btn-sm mb-0"  style="font-size: 12px;">In</btn>';
                             }
                              break;
                        case "Nft Mint":
                             $type = '<btn class="btn-info btn-sm mb-0"  style="font-size: 12px;">Nft Mint</btn>' ;
                             $direction='<btn class="btn-danger btn-sm mb-0"  style="font-size: 12px;">Out</btn>';
                              break;
  
                              $direction='<btn class="btn-success btn-sm mb-0"  style="font-size: 12px;">In</btn>';
                        default:
                              break;
                      }
                      
                  }
                  $user = '<p class="text-white mb-0" style="font-size: 12px;">'.substr($Sub_value_info["from"], 0, 20).'</p>';
                  
                  if ( $user=== null){
                    $user="";
                  }
                  $to = '<p class="text-white mb-0">'.substr($Sub_value_info["to"], 0, 20).'</p>';
                  if ( $to=== null){
                    $to="";
                  }
                     $price ='<p class="text-white mb-0" style="font-size: 12px;">'.number_format($Sub_value_info["deposit_value"],0,'','')/1000000000000000000000000;
                  if ( $price=== null){
                    $price="";
                  }else{
                      
                  }
                  $price .= " Ⓝ </p>";

                  $seconds = $Sub_value_info["block_timestamp"]/1000000000;
                 $datetime = date("m/d/Y H:i:s", $seconds);
                  $select_txn = '<a class="btn-secondary btn-sm mb-0"  style="font-size: 12px;" href="https://nearblocks.io/txns/'.$Sub_value_info["transaction_hash"].'">Show Txn</a>';
                  $timestamp = '<p class="text-white mb-0" style="font-size: 12px;">'.time_elapsed_string($datetime).'</p>';
                  if(($page-1)*$rowcount <= $rowcount && $page*$rowcount >= $rowcount ){
                 $page_load.=' <tr>
      <td data-heading="Action">'.$type
      .'</td>
      <td data-heading="Amount">'.$price
      .'</td>
      <td data-heading="Direction">'.$direction
      .'</td>
      <td data-heading="check TXn">'.$select_txn
      .'</td>
      <td data-heading="Date">'.$timestamp
      .'</td>
    </tr>';
                  }
                      }
                  }
              }
          }
}
      
  
}
}
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
function check_status($transaction_hash){
$load_this="https://nearblocks.io/api/txn?hash=".$transaction_hash."";
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $load_this,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "
-----011000010111000001101001--"
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    $data_json = json_decode($response);
   $data = json_decode($response, true);
   $rowcount="";
}
$status= $data_json->txn->status;
if($status=='Succeeded'){
    global $rowvalue;
    $rowcount = $rowcount +1;
    return true;
    //  echo '['.$rowvalue.']';
}else{
   return false; 
}
}
$output = array(
 "html"    => $page_load
);
echo json_encode($output);
?>