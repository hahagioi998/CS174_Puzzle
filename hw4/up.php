<?php
require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;



$id=$_REQUEST['data'];
header('access-Control-Allow-Origin:*');

if($id == "1"){

     $file = "src/resources/active_image.jpg";
     //判断文件是否存在
    if(file_exists($file)){
        
        if($fp = fopen($file,"rb", 0))
        {
            $gambar = fread($fp,filesize($file));
            fclose($fp);
            $base64 = base64_encode($gambar);
            // 输出
            $encode = 'data:image/jpg/png/gif;base64,' . $base64;
            
            echo $encode;
        } 
    }else{
        echo 1;
    }
    
    
}else if($id == "2"){
    
      //数组写入文件
      $arr=$_REQUEST['arr'];
      $da = explode("-",$arr);
      writetxt($da);
      echo "ok";
    
}

else{
    


// 允许上传的图片后缀
$allowedExts = array("gif", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
//echo $_FILES["file"]["size"];
$extension = end($temp);     // 获取文件后缀名
//echo  $_FILES["file"]["name"];
//echo $_FILES["file"]["type"];

if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/jpg")
|| ($_FILES["file"]["type"] == "image/pjpeg")
|| ($_FILES["file"]["type"] == "image/x-png")
|| ($_FILES["file"]["type"] == "image/png"))
&& ($_FILES["file"]["size"] < 2097152)   // 小于 2MB
&& in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "error";
    }
    else
    {
        echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
        echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
        
        
     
        
         $imgdata = resize_image($_FILES["file"]["name"],$_FILES["file"]["tmp_name"],360,360);
         
         //保存文件
         imagejpeg($imgdata, 'src/resources/active_image.jpg');
         
         
        // 创建日志服务
        $logger = new Logger('my_logger');
        
        // 添加一些处理器
        $logger->pushHandler(new StreamHandler(__DIR__.'/src/resources/jigsaw.log', Logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        
        // 写入日志
        $logger->info( "上传文件名: " . $_FILES["file"]["name"]);
        $logger->info( "文件类型: " . $_FILES["file"]["type"]);
        $logger->info( "文件大小: " . ($_FILES["file"]["size"] / 1024));
        $logger->info( "---------------------------------------------------------------------------------------");
        
        
        //数组写入文件
        $cars=array(0,1,2,3,4,5,6,7,8);
        writetxt($cars);
      
                 
         /**
        
        // 判断当前目录下的 upload 目录是否存在该文件
        // 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
        if (file_exists("src/resources/" . $_FILES["file"]["name"]))
        {
            echo $_FILES["file"]["name"] . " 文件已经存在。 ";
        }
        else
        {
            // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
            move_uploaded_file($_FILES["file"]["tmp_name"], "src/resources/" . $_FILES["file"]["name"]);
            echo "文件存储在: " . "src/resources/" . $_FILES["file"]["name"];
        }*/
    }
}
else
{
    echo "error";
}

}


//数据写入active_image.txt文件
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




// 重置图片文件大小
function resize_image($filename, $tmpname, $xmax, $ymax)
{
    $ext = explode(".", $filename);
    $ext = $ext[count($ext)-1];
 
    if($ext == "jpg" || $ext == "jpeg")
        $im = imagecreatefromjpeg($tmpname);
    elseif($ext == "png")
        $im = imagecreatefrompng($tmpname);
    elseif($ext == "gif")
        $im = imagecreatefromgif($tmpname);
 
    $x = imagesx($im);
    $y = imagesy($im);
 
    if($x <= $xmax && $y <= $ymax)
        return $im;
 
    if($x >= $y) {
        $newx = $xmax;
       // $newy = $newx * $y / $x;
    }
    else {
        $newy = $ymax;
       // $newx = $x / $y * $newy;
    }
 
    $im2 = imagecreatetruecolor($xmax, $ymax);
    imagecopyresized($im2, $im, 0, 0, 0, 0, floor($xmax), floor($ymax), $x, $y);
    return $im2; 
}


//图片转base64
function imgToBase64($img_file) {

    $img_base64 = '';
    if (file_exists($img_file)) {
        $app_img_file = $img_file; // 图片路径
        $img_info = getimagesize($app_img_file); // 取得图片的大小，类型等

        //echo '<pre>' . print_r($img_info, true) . '</pre><br>';
        $fp = fopen($app_img_file, "r"); // 图片是否可读权限

        if ($fp) {
            $filesize = filesize($app_img_file);
            $content = fread($fp, $filesize);
            $file_content = chunk_split(base64_encode($content)); // base64编码
            switch ($img_info[2]) {           //判读图片类型
                case 1: $img_type = "gif";
                    break;
                case 2: $img_type = "jpg";
                    break;
                case 3: $img_type = "png";
                    break;
            }

            $img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;//合成图片的base64编码

        }
        fclose($fp);
    }

    return $img_base64; //返回图片的base64
}

?>