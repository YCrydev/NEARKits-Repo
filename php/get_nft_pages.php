<?php error_reporting (E_ALL ^ E_NOTICE);
if(isset($_POST["pageSize"])){
 $perPage = $_POST["pageSize"];
}else{
     $perPage = 10;
}
 //Query
 $data ="";
$account_id = $_POST["account_id"];
// $account_id = "thecryptoasian.near";
$totalRecords = 0;
$skip = 0;
while($skip <=10){
    load_nftpages($skip,$account_id);
    $skip = $skip+1;
}

function load_nftpages($skip,$account_id){
    $load_this = "https://api-v2-mainnet.paras.id/token?__limit=1000&owner_id=".$account_id."&__skip=".($skip*100)."";
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
   $data = json_decode($response, true);
}
if (is_array($data) || is_object($data))
{
     foreach($data as $key => $value) {
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                  $rowcount = count($Sub_value);
              }
          }
      }
  }
  global $totalRecords;
  $totalRecords = $totalRecords +$rowcount;
}
  
}
    $totalPages = ceil($totalRecords/$perPage);
    $jsonData = array(
        "total_pages"	=> $totalPages,
        "total_nft_count" => $totalRecords
    );
    echo json_encode($jsonData);
?>