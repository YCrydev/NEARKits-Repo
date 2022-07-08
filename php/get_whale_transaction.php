<?php error_reporting (E_ALL ^ E_NOTICE);

 $perPage = 10;
 $total_nftcount = 0;
// $account_type = $_POST["account_type"];
// $page = $_POST["page"];
// $mode_selected = $_POST["mode_selected"];
 //Query
 $account_id = $_POST["account_id"];
//   $account_id = "ycrydev.near";
 $account_type = $_POST["account_type"];
$total_rowcount= 0;
class Nft
{
    public $contract_id;
    public $token_id;
    public $type;
    public $price;
    public $to;
    public $from;
    public $datetime;
    public $datetimeraw;
    public $transaction_hash;
}
class CollectionObject
{
    public $collection;
    public $list_item;
}

$allNfts=array();
// $account_id = $_POST["account_id"];

    $type = "buy";
    $load_this = "https://api-v2-mainnet.paras.id/activities?__limit=10&to=".$account_id."&__skip=".($skip*30)."&type=market_sales";
    load_all_nfts($load_this,$type,0,$account_id);

    $type = "sale";
    $load_this = "https://api-v2-mainnet.paras.id/activities?__limit=10&from=".$account_id."&__skip=".($skip*30)."&type=market_sales";
    load_all_nfts($load_this,$type,0,$account_id);

function load_all_nfts($load_this,$type,$skip,$account_id){

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
foreach($data as $key => $value) {
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                  $rowcount = count($Sub_value);

                  if($rowcount==0){
                     global $skip;
                     $skip = 11;
                  }
                  global $total_rowcount;
                  $total_rowcount = $total_rowcount + $rowcount;
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
$score="";
if (is_array($data) || is_object($data))
{
  foreach($data as $key => $value) {
      if($key === "data"){
          foreach($value as $sub_key => $Sub_value) {
              if($sub_key === "results"){
                 foreach($Sub_value as $sub_key_info => $Sub_value_info) {
                  $collection_id = $Sub_value_info["metadata"]["collection_id"];
                  $contract_id = $Sub_value_info["contract_id"];
                  
                  $token_id = $Sub_value_info["token_id"];
                  $from = $Sub_value_info["from"];
                  $to= $Sub_value_info["to"];
                    $datetime = $Sub_value_info["msg"]["datetime"];
                  $datetime = substr($datetime, 0, strpos($datetime, "."));
                  $datetime .="+00:00";
                    $transaction_hash = $Sub_value_info["msg"]["receipt_id"];
                   $price = $Sub_value_info["msg"]["params"]["price"]/1000000000000000000000000;
                   $Nft = new Nft();
                   $Nft->contract_id  = $contract_id;
                   $Nft->token_id = $token_id;
                   $Nft->type = $type;
                   $Nft->price = $price;
                   $Nft->from = $from;
                   $Nft->to = $to;
                   $Nft->datetime = time_elapsed_string($datetime);
                   $Nft->datetimeraw =new DateTime($datetime);
                   $Nft->transaction_hash = $transaction_hash;
                   
                   
                   global $allNfts;
                  $allNfts[] = $Nft;
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

    $jsonData = array(
        "html"	=> $allNfts,
        "transaction_count" =>$total_rowcount
    );
    echo json_encode($jsonData);


?> 