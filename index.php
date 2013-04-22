<!doctype html>
<?php
	require_once('phpInc/db.class.php');
 	require_once('phpInc/nav.function.php');
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>宠物照片管理中心</title>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>	
	<script type="text/javascript" src="js/jquery.flexslider.js"></script>			
	<script type="text/javascript" src="js/jquery.scrolltotop.js"></script>		
	<script type="text/javascript" src="js/jquery.lazyload.js"></script>	
	<script type="text/javascript" src="main.js"></script>	
	<link rel="stylesheet" href="style.min.css">
	<link rel="stylesheet" href="flexslider.css">
	<!--[if IE 6]>
	<script src="js/DD_belatedPNG.js"></script>
	<script>
	  DD_belatedPNG.fix('.png_bg,.prev,.next,.btn,.flex-control-nav a,.slide-info');	
	</script>
	<link rel="stylesheet" href="ie6.css">
	<![endif]-->
</head>
<body>
	
	<div id="header">
		<div id="header-innerbox" class="center">
			<h1 id="logo"><a href="javascript:void(0);"><img src="images/logo.gif" ></a></h1>
			<form action="" id="search" class="png_bg">				
				<input type="text"  name="search" size="35" maxlength="120" class="i" id="s-input" autocomplete="off">
				<input type="submit" id="s-submit" value="">
			</form>
		</div>
	</div>
	<div id="menu">
		<div id="menu-innerbox" class="center">
			<ul>
				<li><a href="index.php?name=1" class="current">喵咪朵朵</a></li>
				<li><a href="index.php?name=2">喵咪赛赛</a></li>
				<li><a href="index.php?name=3">狗狗寻寻</a></li>
			</ul>
		</div>
	</div>
	<div id="slide">
		<div id="slide-innerbox" class="center">
			<div class="flex-container" id="slide-middle">
				<div class="flexslider">		
					<ul class="slides">
						<?php
							for($i=0; $i<=4; $i++) {
								$from = $i*6;
								$to = ($i+1)*6;
								$sql = "select * from `petpic` where xSize>=162 and ySize>=162 order by date desc limit ".$from.",".$to;
								echo "<li class=\"slides-box\">";
								echo "<ul class=\"slide-content\">";
								$BaiDuImage->Db_Query($sql);
								$count = 1;								
								while ( $re=$BaiDuImage->Db_Fetch_Array()) {									
						?>
								<li <?php if($count==6){
									echo "class=\"last\"";
									} ?> >
									<a href="#"><img src="<?php echo "PetImage/".$re['url'];?>" ></a>
									<div class="slide-info"><a href="#"><?php echo $re['tag'];?></a><span>120张</span></div>
								</li>					
						<?php
									$count++;			
								};
								echo "</ul>";
								echo "</li>";
							}
						?>	
					</ul>
				</div>	
			</div>
		</div>
	</div>
	<div id="content">
		<div id="content-innerbox" class="center">
			<div id="pic-tag">
				<h2 class="tag-title png_bg">宠物标签</h2>
				<div class="tag-content">
					<ul>
						<?php						
							$sql = "select tag ,count(*) as number from `petpic`  GROUP BY tag order by number desc limit 0,10";	
							$BaiDuImage->Db_Query($sql);
							$i=0;
							while ( $re=$BaiDuImage->Db_Fetch_Array()) {
								if($i==0) {
									$cl = " class=\"current\" ";
								} else {
									$cl = "";
								}
								echo "<li".$cl."><a href=\"javascript:void(0)\"".$cl.">".$re['tag']."</a></li>";
								$i++;
							}
						?>												
					</ul>
				</div>
				<div class="tag-bottom"></div>
			</div>
			<div id="pic-rank">
				<ul>
					<li class="current new"><a href="#" class="current" id="new-rank" hidefocus="hidefocus">最新</a></li>
					<li class="hot"><a href="#" id="hot-rank" hidefocus="hidefocus">本周最热</a></li>
				</ul>				
			</div>
			<div id="pic-size">
				<h3>图片尺寸</h3>
				<ul class="size-filter">
					<li class="first">
						<a label="1" hidefocus="hidefocus" href="#3" class="" id="l-size">
							<span class="l-size-ico">大图</span>
						</a>
					</li>
					<li class="last on">
						<a label="0" hidefocus="hidefocus" href="#4" class="on" id="m-size">
							<span class="m-size-ico">中图</span>
						</a>
					</li>
				</ul>		
			</div>
			<div id="pic-box">
				<?php
					$arr = $_REQUEST;
					if(!isset($arr['name'])) {
						$con = "";
					} else {
						$con = "and name=".$arr['name'];
					}
					$sql = "select * from `petpic` where typeSize=2 ".$con." order by date desc limit 0,20";	
					$BaiDuImage->Db_Query($sql);
					$col = 6;
					while ($re=$BaiDuImage->Db_Fetch_Array()) {
						switch ($col%5) {
							case 0:
								if(!isset($coloum_5_content)) {
									$coloum_5_content  =  "<li class=\"flow\">";
									$coloum_5_content .=		"<div class=\"pic-innerbox\">";
									$coloum_5_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_5_content .=				"<div class=\"pic-info\">";
									$coloum_5_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_5_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_5_content .=              			"<span class=\"site-name\"></span>";
									$coloum_5_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_5_content .=            		"</a>";
									$coloum_5_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_5_content .=				"</div>";									
									$coloum_5_content .=		"</div>";
									$coloum_5_content .=		"<div class=\"pic-action\">";
									$coloum_5_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_5_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_5_content .=		"</div>";	
									$coloum_5_content .= "</li>";
								} else {
									$coloum_5_content .=  "<li class=\"flow\">";
									$coloum_5_content .=		"<div class=\"pic-innerbox\">";
									$coloum_5_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_5_content .=				"<div class=\"pic-info\">";
									$coloum_5_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_5_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_5_content .=              			"<span class=\"site-name\"></span>";
									$coloum_5_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_5_content .=            		"</a>";
									$coloum_5_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_5_content .=				"</div>";									
									$coloum_5_content .=		"</div>";
									$coloum_5_content .=		"<div class=\"pic-action\">";
									$coloum_5_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_5_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_5_content .=		"</div>";	
									$coloum_5_content .= "</li>";
								}
								break;
							case 4:
								if(!isset($coloum_4_content)) {
									$coloum_4_content  =  "<li class=\"flow\">";
									$coloum_4_content .=		"<div class=\"pic-innerbox\">";
									$coloum_4_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_4_content .=				"<div class=\"pic-info\">";
									$coloum_4_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_4_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_4_content .=              			"<span class=\"site-name\"></span>";
									$coloum_4_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_4_content .=            		"</a>";
									$coloum_4_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_4_content .=				"</div>";									
									$coloum_4_content .=		"</div>";
									$coloum_4_content .=		"<div class=\"pic-action\">";
									$coloum_4_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_4_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_4_content .=		"</div>";	
									$coloum_4_content .= "</li>";
								} else {
									$coloum_4_content .=  "<li class=\"flow\">";
									$coloum_4_content .=		"<div class=\"pic-innerbox\">";
									$coloum_4_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_4_content .=				"<div class=\"pic-info\">";
									$coloum_4_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_4_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_4_content .=              			"<span class=\"site-name\"></span>";
									$coloum_4_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_4_content .=            		"</a>";
									$coloum_4_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_4_content .=				"</div>";									
									$coloum_4_content .=		"</div>";
									$coloum_4_content .=		"<div class=\"pic-action\">";
									$coloum_4_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_4_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_4_content .=		"</div>";	
									$coloum_4_content .= "</li>";
								}
								break;							
							case 3:
								if(!isset($coloum_3_content)) {
									$coloum_3_content  =  "<li class=\"flow\">";
									$coloum_3_content .=		"<div class=\"pic-innerbox\">";
									$coloum_3_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_3_content .=				"<div class=\"pic-info\">";
									$coloum_3_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_3_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_3_content .=              			"<span class=\"site-name\"></span>";
									$coloum_3_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_3_content .=            		"</a>";
									$coloum_3_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_3_content .=				"</div>";									
									$coloum_3_content .=		"</div>";
									$coloum_3_content .=		"<div class=\"pic-action\">";
									$coloum_3_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_3_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_3_content .=		"</div>";	
									$coloum_3_content .= "</li>";
								} else {
									$coloum_3_content .=  "<li class=\"flow\">";
									$coloum_3_content .=		"<div class=\"pic-innerbox\">";
									$coloum_3_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_3_content .=				"<div class=\"pic-info\">";
									$coloum_3_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_3_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_3_content .=              			"<span class=\"site-name\"></span>";
									$coloum_3_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_3_content .=            		"</a>";
									$coloum_3_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_3_content .=				"</div>";									
									$coloum_3_content .=		"</div>";
									$coloum_3_content .=		"<div class=\"pic-action\">";
									$coloum_3_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_3_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_3_content .=		"</div>";	
									$coloum_3_content .= "</li>";
								}
								break;
							case 2:
								if(!isset($coloum_2_content)) {
									$coloum_2_content  =  "<li class=\"flow\">";
									$coloum_2_content .=		"<div class=\"pic-innerbox\">";
									$coloum_2_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_2_content .=				"<div class=\"pic-info\">";
									$coloum_2_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_2_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_2_content .=              			"<span class=\"site-name\"></span>";
									$coloum_2_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_2_content .=            		"</a>";
									$coloum_1_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_2_content .=				"</div>";									
									$coloum_2_content .=		"</div>";
									$coloum_2_content .=		"<div class=\"pic-action\">";
									$coloum_2_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_2_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_2_content .=		"</div>";	
									$coloum_2_content .= "</li>";
								} else {
									$coloum_2_content .=  "<li class=\"flow\">";
									$coloum_2_content .=		"<div class=\"pic-innerbox\">";
									$coloum_2_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_2_content .=				"<div class=\"pic-info\">";
									$coloum_2_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_2_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_2_content .=              			"<span class=\"site-name\"></span>";
									$coloum_2_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_2_content .=            		"</a>";
									$coloum_2_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_2_content .=				"</div>";									
									$coloum_2_content .=		"</div>";
									$coloum_2_content .=		"<div class=\"pic-action\">";
									$coloum_2_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_2_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_2_content .=		"</div>";	
									$coloum_2_content .= "</li>";
								}
								break;
							case 1:
								if(!isset($coloum_1_content)) {
									$coloum_1_content  =  "<li class=\"flow\">";
									$coloum_1_content .=		"<div class=\"pic-innerbox\">";
									$coloum_1_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_1_content .=				"<div class=\"pic-info\">";
									$coloum_1_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_1_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_1_content .=              			"<span class=\"site-name\"></span>";
									$coloum_1_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_1_content .=            		"</a>";
									$coloum_1_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_1_content .=				"</div>";									
									$coloum_1_content .=		"</div>";
									$coloum_1_content .=		"<div class=\"pic-action\">";
									$coloum_1_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_1_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_1_content .=		"</div>";									
									$coloum_1_content .= "</li>";
								} else {
									$coloum_1_content .=  "<li class=\"flow\">";
									$coloum_1_content .=		"<div class=\"pic-innerbox\">";
									$coloum_1_content .=			"<a href=\"\" class=\"pic-link\"><img src=\"PetImage/".$re['url']."\" class=\"pic-url\"></a>";
									$coloum_1_content .=				"<div class=\"pic-info\">";
									$coloum_1_content .=					"<a href=\"\" class=\"pic-title\">".$re['tag']."</a>";
									$coloum_1_content .=					"<a href=\"\" class=\"from-site\">来自";
									$coloum_1_content .=              			"<span class=\"site-name\"></span>";
									$coloum_1_content .=              			"<span class=\"site-url\">".$re['fromSite']."</span>";
									$coloum_1_content .=            		"</a>";
									$coloum_1_content .=					"<span class=\"size-info\">".$re['xSize']."x".$re['ySize']."</span>";
									$coloum_1_content .=				"</div>";									
									$coloum_1_content .=		"</div>";
									$coloum_1_content .=		"<div class=\"pic-action\">";
									$coloum_1_content .=			"<a class=\"btn download\"  href=\"javascript:void(0);\"><span>".$re['hit']."</span></a>";
									$coloum_1_content .=			"<a class=\"btn addfav\"  id=\"addfav_1\" href=\"javascript:void(0);\"></a>";
									$coloum_1_content .=		"</div>";									
									$coloum_1_content .= "</li>";
								}
								break;																
							default:
								break;
						}
						$col++;
					}
				?>
				<ul id="coloum-1" class="col">
					<?php
						echo $coloum_1_content;
					?>
				</ul>
				<ul id="coloum-2" class="col">
					<?php
						echo $coloum_2_content;
					?>
				</ul>
				<ul id="coloum-3" class="col">
					<?php
						echo $coloum_3_content;
					?>
				</ul>
				<ul id="coloum-4" class="col">
					<?php
						echo $coloum_4_content;
					?>
				</ul>
				<ul id="coloum-5" class="col">
					<?php
						echo $coloum_5_content;
					?>
				</ul>				
			</div>
		</div>
	</div>
	<div class="float-bottom" id="tag-suggest">
      <div id="hot-tags-con" class="con hot">
      	<h2 class="title hot-title">热门标签</h2>
      	<div class="menu">      		
			<?php						
				$sql = "select tag ,count(*) as number from `petpic`  GROUP BY tag order by number desc limit 0,10";	
				$BaiDuImage->Db_Query($sql);
				$i=0;
				while ( $re=$BaiDuImage->Db_Fetch_Array()) {
			?>		
				<a href="javascript:void(0)" class="tag-suggest-link"><span><?php echo $re['tag'];?></span></a>
			<?php
				}
			?>
      	</div>
      </div>
	</div>      
</body>
</html>