<?php error_reporting (E_ALL ^ E_NOTICE);

if(isset($_POST["pageSize"])){
 $perPage = $_POST["pageSize"];
 if(empty($perPage)){
   $perPage = 10;  
 }
}else{
    $perPage = 10; 
}
$account_type = $_POST["account_type"];
$page = $_POST["page"];
$mode_selected = $_POST["mode_selected"];
 //Query
// $account_id = "bigbrainfarmer.near";
$account_id = $_POST["account_id"];
$curl = curl_init();

curl_setopt_array($curl, array(
 CURLOPT_URL => "https://api-v2-mainnet.paras.id/token?__skip=".($page-1)*$perPage."&__limit=".$perPage."&owner_id=".$account_id."",
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
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                  $rowcount = count($Sub_value);
              }
          }
      }
  }
 $paginationHtml='';

  $paginationHtml.='</p>';
 $total_floor = 0;
 $collection_id = "";
$edition = "";
$owner_id = "";
$title = "";
$description = "";
$collection = "";
$edition = "";
$image_url = "";
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
  foreach($data as $key => $value) {
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                 foreach($Sub_value as $sub_key_info => $Sub_value_info) {
                  $collection_id = $Sub_value_info["metadata"]["collection_id"];
                  $contract_id = $Sub_value_info["contract_id"];
                  $token_id = $Sub_value_info["token_id"];
                  $token_series_id = $Sub_value_info["token_series_id"];
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
                    
                    $floor_price_raw = $data->data->results->floor_price;
                    $floor_price = $floor_price_raw/1000000000000000000000000;
                    $floor_price_usd = round($floor_price,2) * $data_price_usd;
                    $total_floor = $total_floor + $floor_price;
                    $score = $Sub_value_info["metadata"]["score"];
                    $title = $Sub_value_info["metadata"]["title"];
                    $collection= $Sub_value_info["metadata"]["collection"];
                    if(empty($collection)){
                        if (!empty($collection_id)){
                        }else{
                            $collection_id=$contract_id;
                        }
                                            $curl = curl_init();
                    curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api-v2-mainnet.paras.id/collections?collection_id=".$collection_id."",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 30,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET"
                ));
                
                $response = curl_exec($curl);
                $err = curl_error($curl);
                
                curl_close($curl);

                if ($err) {
                              echo "cURL Error #:" . $err;
                            } else {
                            
                                 $data = json_decode($response);
                            }
                              $collection=  $data->data->results[0]->collection;
                    }
                    
                    
                    $description = $Sub_value_info["metadata"]["description"];
                    $edition = $Sub_value_info["metadata"]["edition"];
                    $image_url = $Sub_value_info["metadata"]["media"];
                    $ipfs="https";
                    if(strpos($image_url,$ipfs) ===false){
                        $image_url = "https://ipfs.io/ipfs/";
                        $image_url.=$Sub_value_info["metadata"]["media"];;
                    }
                    $owner_id = $Sub_value_info["metadata"]["owner_id"];
                    if($mode_selected=="light"){
                        $text_color = "text-dark";
                    }else{
                        $text_color = "text-white";
                    }
                    
                    $paginationHtml.='
                       <div class="col-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 grid-margin stretch-card display_nft_info" style="text-decoration: none;" href="#display_nft_info"
                    data-token_id="'.$token_id.'"
                    data-contract_id="'.$contract_id.'"
                    data-image="'.$image_url.'"
                    data-title="'.$title.'"
                    data-floor_price="'.number_format($floor_price,2).' Ⓝ"
                    data-floor_price_usd="≈ $'.number_format(($floor_price_usd),2).'"
                    data-score="Rarity: '.number_format(($score),2).'"
                    data-collection="'.$collection.'"
                    data-title_nft="'.$title_nft.'"
                    data-staked_link=""
                    data-type="get_nfts"
                    >';
                    
                     $paginationHtml.='<a  style="text-decoration: none;" href="#display_nft_info">
                    <div class="card switch_theme">
                     <img class="card-img-top img-fluid" src="'.$image_url.'" style="width:100% ; height: 200px;
    object-fit: cover;" alt="">
    
                      <div class="card-body p-2">
                        <h5 class="card-title text-white">'.$title.'</h5>
                        <div class="d-flex">
                          <div class="preview-list w-100">
                            <div class="preview-item p-0">
                              <div class="preview-item-content d-flex flex-grow p-0">
                                <div class="flex-grow">
                                  <div class="d-flex d-md-flex d-xl-flex justify-content-start">
                                  <div class="d-block">
                                    <p class="text-white text-small">Floor price: '.number_format($floor_price,2).' Ⓝ</p>
                                    <p class="text-white text-small d-flex justify-content-start">
                                     ≈ $'.number_format(($floor_price_usd),2).'</p>
                                     <p class="text-white text-small d-flex justify-content-start">
                                     Rarity : '.number_format(($score),2).'</p>
                                     </div>
                                  </div>
                                 
                                  <h5 class="text-white"><a class="text-white" href="https://paras.id/search?q='.$collection.'">'.$title_nft.'</a></h5>
                                  </div>
                                  
                        
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      
                    </div>
                    </a>
                  </div>';
                                  }
                                 
                                  $paginationHtml.='
                                   </div>
                    </div>
                  </div>
                  ';
                  
                  
                 }
              }
          }
      }
  }
   
   
   







    $jsonData = array(
        "html"	=> $paginationHtml,
        "nfts_count" =>$rowcount
    );
    echo json_encode($jsonData);


?> 