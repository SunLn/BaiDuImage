<?php
  header("Content-type: text/html; charset=utf-8");
  require_once('phpInc/global.php');
  set_time_limit(0);
  //利用PHP目录和文件函数遍历用户给出目录的所有的图片名称,并随机生成Tag等信息，插入数据库
  function getPetInfo($dirname){
    if(!is_dir($dirname)){
      echo "{$dirname}不是一个有效的目录！";
      exit();
    }
    $handle = opendir($dirname);
    $i = 1;
    while(($fn = readdir($handle))!== false){
     if($fn!='.' && $fn!='..'){
       $curDir = $dirname.'/'.$fn;
        if(is_dir($curDir)){
          getPetInfo($curDir);
        }else{
          $path = pathinfo($curDir);         
          $name = rand(1,3); //图片类型
          $hit = rand(0,10000); //图片点击率
          $XY = getimagesize($curDir);
          if($XY){
            $xSize = $XY[0]; //图片X
            $ySize = $XY[1]; //图片Y
            if($xSize === 310){
              $typeSize = 1; //large size image type 
            }elseif($xSize === 230){
              $typeSize = 2; //middle size image type
            }else{
              $typeSize = 0;
            }
          }else{
            $xSize = 0;
            $ySize = 0;
            $typeSize = 0;
          }
          $data1 = mktime(0,0,0,1,1,2012);
          $data2 = mktime(0,0,0,4,22,2013);
          $date = date('Ymd',rand($data1,$data2));//生成随机日期
          $rand_time = rand(1,24*60*60);
          $time = date('His',$rand_time);//生成随机时间
          $url = $fn;//图片名
          $fromSite = 'http://baidu.com';
          $tagArray = Array('萌货','喵星人','狗狗','猫叔','哈士奇','泰迪','金毛','博美','萨摩耶','可爱无敌多','傻傻的喵咪','不是地球人','火星的两2货','傻傻傻傻分不清吃货','猫哥哥大战狗迪迪','睡觉的神','口水汪汪','萌货无敌','黑夜的灵魂','闪耀的精灵','可爱不是一点点','有这么笨的猫','亲如一家','睡觉流口水');          
          $tag = $tagArray[rand(0,count($tagArray)-1)];
         
          //wFile($i,$name,$hit,$xSize,$ySize,$typeSize,$date,$time,$url,$fromSite,$tag);
          if(!isset($sql)) {
             $sql = "INSERT INTO `petpic`".
             "(`id`,`name`,`hit`,`xSize`,`ySize`,`typeSize`,`date`,`time`,`url`,`fromSite`,`tag`)".
             "VALUES (NULL,$name,$hit,$xSize,$ySize,$typeSize,'$date','$time','$url','$fromSite','$tag')";
          } else {
            $sql .= ",(NULL,$name,$hit,$xSize,$ySize,$typeSize,'$date','$time','$url','$fromSite','$tag')";
          } 
          $i++;          
        }
      }
    }
    return $sql;
  }
//  读取图片相关信息并写入PetImageInfo.txt
//  function wFile($id,$name,$hit,$xSize,$ySize,$typeSize,$date,$time,$url,$fromSite,$tag) {
//    $record = $id.','.$name.','.$hit.','.$xSize.','.$ySize.','.$typeSize.','.$date.','.$time.','.$url.','.$fromSite.','.$tag;
//   $file = fopen("F:\\WWW\\BaiDuMianShi\\PetImageInfo.txt","a+");
//   fwrite($file,$record."\r\n");
//    fclose($file);
//  }

  //给出一个目录名称调用函数
  $sql = getPetInfo('F:\WWW\BaiDuMianShi\PetImage');
  //echo $sql;
  $BaiDuImage->Db_Query($sql);
?>
