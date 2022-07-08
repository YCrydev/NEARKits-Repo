<?php
$account_id = $_POST["account_id"];
$page = $_POST["page"];
$skip = 0;
$total_rowcount=0;
$rowvalue = 0;
while($skip <=400){
    load_all_transactions($skip,$account_id);
    $skip = $skip+1;
}
   
function load_all_transactions($skip,$account_id){
    $load_this ="https://nearblocks.io/api/account/txns?address=".$account_id."&limit=".$limit."&offset=".($skip*25)."";
$curl = curl_init();
// echo $load_this;
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

foreach($data as $key => $value) {
      if($key === "txns"){
          foreach($value as $sub_key_info => $Sub_value_info) {
                      if(!($Sub_value_info["type"] == "Add Key")){
                $sub_array = array();
                global $rowvalue;
                //  echo '['.$rowvalue.']';
                  $type = $Sub_value_info["type"];
                  if ( $type=== null){
                    $type="";
                  }else{
                      switch ($type) {
                          case "Buy":
                             check_status($Sub_value_info["transaction_hash"]);
                              break;
                        case "Transfer":
                             check_status($Sub_value_info["transaction_hash"]);
                              break;
                        case "Nft Mint":
                            check_status($Sub_value_info["transaction_hash"]);
                              break;
                        default:
                              break;
                      }
                  }
                      }
          }
          $rowcount = count($value);

                 
                  global $total_rowcount;
                  $total_rowcount = $total_rowcount + $rowcount;
                   if($rowcount==0){
                     global $skip;
                    //  echo $total_rowcount;
                    //  echo $rowvalue;
                     $skip = 401;
                  }
              }
  }
}
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
     $rowvalue =$rowvalue+1;
    //  echo '['.$rowvalue.']';
}
}
 $jsonData = array(
        "total_count"	=> $rowvalue,
    );
    echo json_encode($jsonData);
?>