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
    public $title;
    public $image;
    public $collection;
    public $contract_id;
    public $token_id;
    public $metadata;
    public $royalty;
    public $price;
    public $has_price;
}
class CollectionObject
{
    public $collection;
    public $list_item;
}

$allNfts=array();
// $account_id = $_POST["account_id"];
while($skip <=10){
    load_all_nfts($skip,$account_id);
    $skip = $skip+1;
}
function load_all_nfts($skip,$account_id){
$load_this = "https://api-v2-mainnet.paras.id/token?__limit=100&owner_id=".$account_id."&__skip=".($skip*100)."";
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
                  
                  $token_series_id = $Sub_value_info["token_series_id"];
                  $royalty= $Sub_value_info["royalty"];
                  $has_price= $Sub_value_info["has_price"];
                  $price= $Sub_value_info["price"];
                    $score = $Sub_value_info["metadata"]["score"];
                    $title = $Sub_value_info["metadata"]["title"];
                    $collection= $Sub_value_info["metadata"]["collection"];
                    if(empty($collection)){
                        if (!empty($collection_id)){
                        }else{
                            $collection_id=$contract_id;
                        }
                    }
                    
                    $token_id = $Sub_value_info["token_id"];
                    $description = $Sub_value_info["metadata"]["description"];
                    $edition = $Sub_value_info["metadata"]["edition"];
                    $image_url = $Sub_value_info["metadata"]["media"];
                    $ipfs="http";
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
                    
                   $Nft = new Nft();
                   $Nft->title = $title;
                   $Nft->image = $image_url;
                   $Nft->collection = $collection;
                   $Nft->contract_id  = $contract_id;
                   $Nft->token_id = $token_id;
                   $Nft->metadata = $Sub_value_info["metadata"];
                   $Nft->royalty = $royalty;
                   $Nft->has_price = $has_price;
                   $Nft->price = $price;
                   
                   
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




    $jsonData = array(
        "html"	=> $allNfts,
        "nfts_count" =>$total_rowcount
    );
    echo json_encode($jsonData);


?> 