<?php
	header('Content-type: text/json');
	require_once('phpInc/db.class.php');
 	require_once('phpInc/nav.function.php');
	$arr = $_REQUEST;
	$typeRank = $arr['typeRank'];
	$typeSize = $arr['typeSize'];
	$name = 	$arr['name'];
	$tag = $arr['tag'];
	$queryType = $arr['queryType']; 

	$sql = '';
	if(isset($name)&&$name!=0) {
		$strName = " and name=".$name;
	} else {
		$strName ="";
	}
	if(isset($tag)) {
		$strTag = " and tag like '%".$tag."%' ";
	}else{
		$strTag = "";
	}
	if(isset($typeRank)) {
		$strRank = " order by ".$typeRank;
	}else{
		$strRank = "";
	}
	if(isset($queryType)) {
		switch ($queryType) {
			case 'list':
				$sql = "select * from `petpic` where typeSize=".$typeSize.$strName.$strRank." desc limit 0,20";
				break;
			case 'tag':
				$sql="select * from `petpic` where typeSize=".$typeSize.$strName.$strTag.$strRank." desc limit 0,20";
				break;			
			case 'flow':
				$queryIndex = $arr['queryIndex'];
				$from = $queryIndex*5;
				$sql = "select * from `petpic` where typeSize=".$typeSize.$strName.$strTag.$strRank." desc limit ".$from.",20";
				break;	
			default:
				break;
		}
	}
	//echo $sql;
	$BaiDuImage->Db_Query($sql);
	$re=$BaiDuImage->Db_Fetch_All();
	echo json_encode($re);	
	exit();
?>