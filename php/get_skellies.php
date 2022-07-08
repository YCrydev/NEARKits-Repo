<?php error_reporting (E_ALL ^ E_NOTICE);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
 $perPage = 10;
$account_type = $_POST["account_type"];
$page = $_POST["page"];
$mode_selected = $_POST["mode_selected"];
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
                    $floor_price_raw = $data->data->results->floor_price;
                    $floor_price = $floor_price_raw/1000000000000000000000000;
                    $floor_price_usd = round($floor_price,2) * $data_price_usd;
                    $total_floor = $total_floor + $floor_price;
                    
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
                       <div class="col-6 col-sm-4 col-md-3 col-xl-3 grid-margin stretch-card d-flex justify-content-center"> ';
                     $paginationHtml.='
                     <div  data-toggle="modal" data-target="#'.$collection_id.'-'.$token_id.'" class="d-flex modal_show justify-content-center" style="background-image:url(https://nearkits.io/assets/images/squareskull2.jpeg);background-repeat:no-repeat;
background-size:contain;
background-position:center; padding: 30px; height:200px; width: 200px;">
                     <img class="" src="'.$image_url.'" style="width: 100%; ; object-fit: scale-down;" alt="">
    </div>
    </div>
    <div class="modal fade" id="'.$collection_id.'-'.$token_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">'.$title.'</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       <img class="" src="'.$image_url.'" style="width: 100%; ; object-fit: scale-down;" alt="">
        <div class="d-flex d-md-flex d-xl-flex justify-content-end">
                                  <div class="d-block">
                                    <h4 class="'.$text_color.'">Floor price: '.number_format($floor_price,2).' Ⓝ</h4>
                                    <h4 class="'.$text_color.' d-flex justify-content-end">
                                     ≈ $'.number_format(($floor_price_usd),2).'</h4>
                                     </div>
                                  </div>
      </div>
      <div class="modal-footer">
     <div class="flex-grow">
                                  <h4 class="'.$text_color.'"><a class="'.$text_color.'" href="https://paras.id/search?q='.$collection_id.'">'.$collection.'</a></h4>
                                <h4 class="'.$text_color.'">'.$description.'</h4>
                              </div>
                            </div>
      </div>
    </div>
  </div>
</div>
';
                  
                  
                 }
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