<?php
$output = array();

if(isset($_POST["collection_id"])){
$search= $_POST["search"]["value"];
$start= $_POST["start"];
$collection_id = $_POST["collection_id"];
}
$curl = curl_init();
$load_this = "https://api-v2-mainnet.paras.id/collection-activities?collection_id=".$collection_id."&__limit=1000";
curl_setopt_array($curl, array(
  CURLOPT_URL => $load_this,
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
   $data = json_decode($response, true);
foreach($data as $key => $value) {
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                   $total_rowcount = count($Sub_value);
              }
          }
      }
}
;

$curl = curl_init();
$load_this = "https://api-v2-mainnet.paras.id/collection-activities?collection_id=".$collection_id."";
// if(!empty($search)){
// $load_this .= "&from=".strtolower($search)."";
// }
if($start!==0){
    $load_this .= "&__skip=".$start."";
}

$load_this .="&__limit=1000";
curl_setopt_array($curl, array(
  CURLOPT_URL => $load_this,
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
   $data = json_decode($response, true);
   $rowcount="";
foreach($data as $key => $Sub_value) {
      if($key === "data"){


                   $rowcount = count($Sub_value);
                   foreach($Sub_value as $sub_key_info => $Sub_value_info) {
                       
                       $image = $Sub_value_info["data"][0]["metadata"]["media"];
                       
                       $div = '<div class=""><img src="'.$image.'" style="height:65px;width:65px; border-radius:0px"></div>';
                       if($Sub_value_info["type"] == "resolve_purchase"){
                $sub_array = array();
                 
                  $type = $Sub_value_info["type"];
                  if ( $type=== null){
                    $type="";
                  }else{
                      switch ($type) {
                          case "delete_offer":
                             $type = '<p class="text-danger mb-0"  style="font-size: 12px;">Deleted Offer</p>' ;
                               break;
                        case "nft_transfer":
                             $type = '<p class="text-success mb-0"  style="font-size: 12px;">Nft Transfer</p>' ; 
                               break;
                        case "resolve_purchase":
                             $type = '<p class="text-info mb-0"  style="font-size: 12px;">Sold</p>' ;
                               break;
                        case "add_offer":
                             $type = '<p class="text-info mb-0"  style="font-size: 12px;">Added offer</p>' ;
                               break;
                               case "delete_market_data":
                             $type = '<p class="text-white mb-0"  style="font-size: 12px;">Delete Market data</p>' ;
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
                     $price ='<p class="text-white mb-0" style="font-size: 12px;">'.$Sub_value_info["price"]/1000000000000000000000000;
                   if ( $price=== null){
                    $price="";
                  }else{
                      
                  }
                  $price .= " Ⓝ </p>";

                  $datetime = $Sub_value_info["msg"]["datetime"];
                  $datetime = substr($datetime, 0, strpos($datetime, "."));
                  $datetime .="+00:00";
                  $sub_array[] = $div;
                  $sub_array[] = '<p class="text-white mb-0" style="font-size: 12px;">'.time_elapsed_string($datetime).'</p>';
                   $sub_array[] = $price;
                  $sub_array[] = $user;
                  $sub_array[] = $to;
                  $data_array[] = $sub_array;
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
$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>   $rowcount,
 "recordsFiltered" => $rowcount,
 "data"    => $data_array
);
echo json_encode($output);
?>