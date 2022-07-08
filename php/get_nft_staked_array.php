<?php error_reporting (E_ALL ^ E_NOTICE);

 $perPage = 10;
 $total_nftcount = 0;
// $account_type = $_POST["account_type"];
// $page = $_POST["page"];
// $mode_selected = $_POST["mode_selected"];
 //Query
$account_id = $_POST["account_id"];
  //$account_id = "ycrydev.near";
 $account_type = $_POST["account_type"];
$total_rowcount= 0;
  $curl_price = curl_init();
                    curl_setopt_array($curl_price, array(
                  CURLOPT_URL => "https://api.coingecko.com/api/v3/simple/price?ids=NEAR&vs_currencies=USD",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET"
                ));
                
                $response_price = curl_exec($curl_price);
                
                $err = curl_error($curl_price);
                $data_price = json_decode($response_price);
                $data_price_usd =   $data_price->near->usd;

function get_nft_collection_data($contract_id,$data_price_usd){
    $load_this ="https://api-v2-mainnet.paras.id/collection-stats?collection_id=".$contract_id."";
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
                      $data = json_decode($response);
                    }
                  
                    $floor_price_raw = $data->data->results->floor_price;
                    $floor_price = $floor_price_raw/1000000000000000000000000;
                    $floor_price_usd = round($floor_price,2) * $data_price_usd;
                    $total_floor = $total_floor + $floor_price;
                    return [$floor_price,$floor_price_usd];
}

    $jsonData = array(
        "near_dragons"	=> get_nft_collection_data("dragonnation.near",$data_price_usd),
        "vexed_apes"	=> get_nft_collection_data("nft1.vexedapesmint.near",$data_price_usd),
        "kokumokongz"	=> get_nft_collection_data("kokumokongz.near",$data_price_usd),
        "undead"	=> get_nft_collection_data("undead.secretskelliessociety.near",$data_price_usd),
        "skellies"	=> get_nft_collection_data("secretskelliessociety.near
r",$data_price_usd),
"grimms"	=> get_nft_collection_data("grimms.secretskelliessociety.near
r",$data_price_usd),
        "estates"	=> get_nft_collection_data("estates.secretskelliessociety.near",$data_price_usd)
        ,
        "vexed_apes_2"	=> get_nft_collection_data("vexedapesclub.near",$data_price_usd)
    );
    echo json_encode($jsonData);


?> 