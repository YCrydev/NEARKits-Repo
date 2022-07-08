<?php 

include "database_connection.php";

$imageDir = '../messages/'; 
$response = array( 
    'status' => 0, 
    'image_link' => '',
    'message' => 'Form submission failed, please try again.' 
); 
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 function generate_string($input, $strength = 16) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
 
    return $random_string;
}
// If form is submitted 
if( isset($_POST['name'])){ 
    // Check whether submitted data is not empty 
    if(!empty($_POST['name'])){ 
        // Validate manuscript_author 
        $from_user = $_POST['from'];
        $to_user = $_POST['to'];
            $uploadStatus = 1; 
            // Upload manuscript 
            $uploadedmanuscript = ''; 
            if(!empty($_POST["name"])){ 
                 
                // File path config 
                $fileName_start = basename($_FILES["image"]["name"]); 
                $ext= substr(strrchr($fileName_start, "."), 1); 
                echo $ext;
               $image_name =generate_string($permitted_chars, 10);
                $fileName = preg_replace("/[^A-Za-z0-9_-]+/", '-',
                $image_name).'.'."png";
                $targetFilePath = $imageDir . $fileName; 
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                 
                // Allow certain image formats 
                $allowTypes = array('webp', 'jpg', 'png', 'jpeg'); 
                if(in_array($fileType, $allowTypes)){ 
                    // Upload file to the server 
                    if(copy($_FILES["image"]["tmp_name"], $targetFilePath)){ 
                        $manuscript_image_file_name = $fileName; 
                        $uploadStatus = 1; 
                        $response['image_link'] = $fileName; 
                        $response['message'] = 'Done'; 
                          $query="INSERT INTO nft_messages 
                SET
                nft_image=:nft_image,
                from_user=:from_user,
                to_user=:to_user,
                link_status='active'";
                $stmt= $connect->prepare($query);
                $stmt->bindParam(":nft_image",$fileName);
                $stmt->bindParam(":from_user",$from_user);
                $stmt->bindParam(":to_user",$to_user);
                if($stmt->execute()){ 
                    $response['status'] = 1; 
                    $response['message'] = 'Form data submitted successfully!'; 
                } 
                    }else{ 
                        $uploadStatus = 0; 
                        $response['message'] = 'Sorry, there was an error uploading your image.'; 
                    } 
                }else{ 
                    $uploadStatus = 0; 
                    $response['message'] = 'Sorry, only, WebP, JPG, JPEG, & PNG files are allowed to upload.'; 
                } 
            } 
             
            if($uploadStatus == 1){ 
                // Include the database config manuscript 
                 
                // Insert form data in the database 
              
            } 
      
    }else{ 
         $response['message'] = 'Error Please try again later.'; 
    } 
} 
 
// Return response 
echo json_encode($response);
?>