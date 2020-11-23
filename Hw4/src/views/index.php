<?php
require_once __DIR__ . '/vendor/autoload.php';

$id=$_REQUEST['data'];
if($id == "1"){

}else if($id == "2"){
    $arr=$_REQUEST['arr'];
      $da = explode("-",$arr);
      writetxt($da);
      echo "ok";
}else{
    
}
//write data into txt file
function writetxt($data){
    
    $myfile = fopen(__DIR__.'/src/resources/active_image.txt', "w");
    
    flock($myfile, LOCK_EX);
    for ($i=0; $i < count($data); $i++) {
        
        $txt = $data[$i]." ";
       
       fwrite($myfile, $txt);
      
    }
    flock($myfile, LOCK_UN);
    fclose($myfile);
}
?>