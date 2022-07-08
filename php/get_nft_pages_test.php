<?php error_reporting (E_ALL ^ E_NOTICE);
 //Query
$account_id = "";
$account_id = $_POST["account_id"];
$totalRecords = 0;
$skip = 0;
 $total_floor = 0;
 $total_average = 0;
 $collection_id = "";
$collection ="";
$title = "";
$description = "";
$edition = "";
$image_url = "";
while($skip <=10){
    load_total_floor($skip,$account_id);
    $skip = $skip+1;
}
function load_total_floor($skip,$account_id){
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
    $data_json = json_decode($response);
   $data = json_decode($response, true);
   $rowcount="";
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
 
}
if (is_array($data) || is_object($data))
{
  foreach($data as $key => $value) {
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                 foreach($Sub_value as $sub_key_info => $Sub_value_info) {
                  $collection_id = $Sub_value_info["metadata"]["collection_id"];
                  $contract_id = $Sub_value_info["contract_id"];
                                       $curl = curl_init();
                    if (!empty($collection_id)){
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api-v2-mainnet.paras.id/collection-stats?collection_id=".$collection_id."",
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
                      $data = json_decode($response);
                    }
                    }else{
                        $curl = curl_init();
                    
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => "https://api-v2-mainnet.paras.id/collection-stats?collection_id=".$contract_id."",
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
                      $data = json_decode($response);
                    }
                    }
                    // print_r($response);
                    $floor_price_raw = $data->data->results->floor_price;
                    
                    $floor_price = $floor_price_raw/1000000000000000000000000;
                    $average_price_raw = $data->data->results->avg_price;
                    $average_price= $average_price_raw/1000000000000000000000000;
                    global $total_average;
                    global $total_floor;
                    $total_average = $total_average + $average_price;
                    $total_floor = $total_floor + $floor_price;
                 }
              }
          }
      }
  }
   
}
   
}

}





    $jsonData = array(
        "total_floor"	=> round($total_floor,2),
        "total_average" =>round($total_average,2)
    );
    echo json_encode($jsonData);


?> 