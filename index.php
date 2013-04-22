<!doctype html>
<?php
	require_once('db.class.php');
 	require_once('nav.function.php');
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>宠物照片管理中心</title>
	<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>	
	<script type="text/javascript" src="js/jquery.flexslider.js"></script>			
	<script type="text/javascript" src="js/jquery.scrolltotop.js"></script>		
	<script type="text/javascript" src="js/jquery.lazyload.js"></script>	
	<script type="text/javascript">
		$(window).load(function() {
			$('.flexslider').flexslider({
				animation: "slide",
				controlsContainer: ".flex-container"
			});
/*			$.scrolltotop({
			   	className: 'totop',
			   	controlHTML : '<a title="返回顶部" hidefocus="hidefocus" href="javascript:void(0);" class="up_to_top"> </a>',
			   	offsety:0
		   });	*/
			$("#pic-box li.flow").mouseover(function(){
				$(".pic-action",this).css('visibility','visible');
			});
			$("#pic-box li.flow").mouseout(function(){
				$(".pic-action",this).css('visibility','hidden');
			});
			function getValueToAjax() {
				var typeRank, typeSize, name, tag, queryIndex;
				var n=location.search.substring(1);
				if(n.indexOf('=') !== -1){
					name = n.split('=')[1];
				} else {
					name = 0;
				}
				if($("#pic-size a.on").attr('id') === 'm-size'){
					typeSize = '2' ;//middle size pic
					queryIndex = Math.ceil($('.pic-link img').length/5);
				} else {
					typeSize = '1' ;//large size pic
					queryIndex = Math.ceil($('.pic-link img').length/4);
				}
				if($("#pic-rank a.current").attr('id') === 'hot-rank') {
					typeRank = 'hit';
				} else {
					typeRank = 'date';
				}
				return {
					typeRank : typeRank,
					typeSize : typeSize,
					name     : name,
					tag      : $("#s-input").val(),
					queryIndex : queryIndex 
				};
			}
			var lastScrollTop = $(window).scrollTop();
			var lastScrollTopForAjax = $(window).scrollTop();
			var INTERVALID;	
			$(window).scroll(function(event){
			   var st = $(this).scrollTop();
			   if(INTERVALID) {
					clearInterval(INTERVALID);
			   }
			   if (st < lastScrollTop){
			   		//up scroll coding
			   		if(st>400) {
						if($("#tag-suggest").height()!=='58') {
				       	  $("#tag-suggest").css('visibility','visible').animate({
				       	  	height: 58
				       	  },200);
				       }			   			
			   		} else {
						$("#tag-suggest").css('visibility','hidden').height('0');
			   		}				       
			   } else {
			   	   //down scroll coding
			       $("#tag-suggest").css('visibility','hidden').height('0');
			       if(st-lastScrollTopForAjax>600) {
						var vObject = getValueToAjax();
						$.get("Flow.php", {
							typeRank : vObject.typeRank,
							typeSize : vObject.typeSize,
							name     : vObject.name,
							tag      : vObject.tag,							
							queryIndex : vObject.queryIndex,
							queryType : 'flow'
						}, function (data,textStatus){
						var picFlowBox = $("#pic-box ul.col");
							if(vObject.typeSize==2){
								for(var i=0; i<5; i++) {
									for(var j=0; j<4; j++) {					
										var innerString   =  '<li class="flow">';
											innerString +=		'<div class="pic-innerbox">';
											innerString +=			'<a href="" class="pic-link"><img src="images/loadingPD.gif" data-original="PetImage/'+data[j*5+i].url+'" width="'+data[j*5+i].xSize+'" height="'+data[j*5+i].ySize+'" class="pic-url"></a>';
											innerString +=				'<div class="pic-info">';
											innerString +=					'<a href="" class="pic-title">'+data[j*5+i].tag+'</a>';
											innerString +=					'<a href="" class="from-site">来自';
											innerString +=              			'<span class="site-name"></span>';
											innerString +=              			'<span class="site-url">'+data[j*5+i].fromSite+'</span>';
											innerString +=            		'</a>';
											innerString +=					'<span class="size-info">'+data[j*5+i].xSize+'x'+data[j*5+i].ySize+'</span>';
											innerString +=				'</div>';									
											innerString +=		'</div>';
											innerString +=		'<div class="pic-action">';
											innerString +=			'<a class="btn download"  href="javascript:void(0);"><span>'+data[j*5+i].hit+'</span></a>';
											innerString +=			'<a class="btn addfav"  id="addfav_1" href="javascript:void(0);"></a>';
											innerString +=		'</div>';	
											innerString += '</li>';
										$(picFlowBox[i]).append(innerString);
										$("#pic-box li.flow").mouseover(function(){
											$(".pic-action",this).css('visibility','visible');
										});
										$("#pic-box li.flow").mouseout(function(){
											$(".pic-action",this).css('visibility','hidden');
										});											
									}
								}
							}	
						},"json");
			       		lastScrollTopForAjax = st;
			       }
			   }
			   lastScrollTop = st;
			   $(".flow img").lazyload({ threshold : 500 });
			});
			$('#pic-rank a').click(function(){
				lastScrollTopForAjax = $(window).scrollTop();
				if(INTERVALID) {
					clearInterval(INTERVALID);
			   }
				if($(this).attr('class') === 'current') {
					return false;
				} else {
					if($(this).attr('id') === 'hot-rank') {
						$('#new-rank').removeClass('current');
						$('#pic-rank .new').removeClass('current');
						$('#pic-rank .hot').addClass('current');
					} else {
						$('#hot-rank').removeClass('current');
						$('#pic-rank  .hot').removeClass('current');
						$('#pic-rank  .new').addClass('current');
					}					
					$(this).addClass('current');
					var vObject = getValueToAjax();
					$.get("Flow.php", {
						typeRank : vObject.typeRank,
						typeSize : vObject.typeSize,
						name     : vObject.name,
						tag      : vObject.tag,							
						queryType : 'list'
					}, function (data,textStatus){
						var picFlowBox = $("#pic-box ul.col");
						picFlowBox.empty();
						if(vObject.typeSize==2){
							for(var i=0; i<5; i++) {
								for(var j=0; j<4; j++) {
									var innerString   =  '<li class="flow">';
										innerString +=		'<div class="pic-innerbox">';
										innerString +=			'<a href="" class="pic-link"><img src="images/loadingPD.gif" data-original="PetImage/'+data[j*5+i].url+'" width="'+data[j*5+i].xSize+'" height="'+data[j*5+i].ySize+'" class="pic-url"></a>';
										innerString +=				'<div class="pic-info">';
										innerString +=					'<a href="" class="pic-title">'+data[j*5+i].tag+'</a>';
										innerString +=					'<a href="" class="from-site">来自';
										innerString +=              			'<span class="site-name"></span>';
										innerString +=              			'<span class="site-url">'+data[j*5+i].fromSite+'</span>';
										innerString +=            		'</a>';
										innerString +=					'<span class="size-info">'+data[j*5+i].xSize+'x'+data[j*5+i].ySize+'</span>';
										innerString +=				'</div>';									
										innerString +=		'</div>';
										innerString +=		'<div class="pic-action">';
										innerString +=			'<a class="btn download"  href="javascript:void(0);"><span>'+data[j*5+i].hit+'</span></a>';
										innerString +=			'<a class="btn addfav"  id="addfav_1" href="javascript:void(0);"></a>';
										innerString +=		'</div>';	
										innerString += '</li>';
									$(picFlowBox[i]).append(innerString);
									$("#pic-box li.flow").mouseover(function(){
										$(".pic-action",this).css('visibility','visible');
									});
									$("#pic-box li.flow").mouseout(function(){
										$(".pic-action",this).css('visibility','hidden');
									});											
								}
							}
						}						
					},"json");
				};
				$(".flow img").lazyload({ threshold : 500 });
				return false;
			});	

			$('#s-input').focus(function(){
				INTERVALID = setInterval(function(){
					lastScrollTopForAjax = $(window).scrollTop();
					var vObject = getValueToAjax();
					$.get("Flow.php", {
						typeRank : vObject.typeRank,
						typeSize : vObject.typeSize,
						name     : vObject.name,
						tag      : vObject.tag,							
						queryType : 'tag'
					}, function (data,textStatus){
						var picFlowBox = $("#pic-box ul.col");
						picFlowBox.empty();
						if(vObject.typeSize==2){
							for(var i=0; i<5; i++) {
								for(var j=0; j<4; j++) {
									var innerString   =  '<li class="flow">';
										innerString +=		'<div class="pic-innerbox">';
										innerString +=			'<a href="" class="pic-link"><img src="PetImage/'+data[j*5+i].url+'" alt=""></a>';
										innerString +=				'<div class="pic-info">';
										innerString +=					'<a href="" class="pic-title">'+data[j*5+i].tag+'</a>';
										innerString +=					'<a href="" class="from-site">来自';
										innerString +=              			'<span class="site-name"></span>';
										innerString +=              			'<span class="site-url">'+data[j*5+i].fromSite+'</span>';
										innerString +=            		'</a>';
										innerString +=					'<span class="size-info">'+data[j*5+i].xSize+'x'+data[j*5+i].ySize+'</span>';
										innerString +=				'</div>';									
										innerString +=		'</div>';
										innerString +=		'<div class="pic-action">';
										innerString +=			'<a class="btn download"  href="javascript:void(0);"><span>'+data[j*5+i].hit+'</span></a>';
										innerString +=			'<a class="btn addfav"  id="addfav_1" href="javascript:void(0);"></a>';
										innerString +=		'</div>';	
										innerString += '</li>';
									$(picFlowBox[i]).append(innerString);
									$("#pic-box li.flow").mouseover(function(){
										$(".pic-action",this).css('visibility','visible');
									});
									$("#pic-box li.flow").mouseout(function(){
										$(".pic-action",this).css('visibility','hidden');
									});											
								}
							}
						}				
					},"json");
				},500);
				$(".flow img").lazyload({ threshold : 500 });
			});
			$('#s-input').blur(function(){
				clearInterval(INTERVALID);
			});	
			$(".flow img").lazyload({ threshold : 500 });			
		});
	</script>	
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="flexslider.css">
</head>
<body>
	
	<div id="header">
		<div id="header-innerbox" class="center">
			<h1 id="logo"><a href=""><img src="images/logo.gif" alt=""></a></h1>
			<form action="" id="search">				
				<input type="text"  name="word" size="35" maxlength="120" class="i" id="s-input" autocomplete="off">
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
									<a href="#"><img src="<?php echo "PetImage/".$re['url'];?>" alt=""></a>
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
				<h2 class="tag-title">宠物标签</h2>
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
								echo "<li".$cl."><a href=\"#\"".$cl.">".$re['tag']."</a></li>";
								$i++;
							}
						?>												
					</ul>
				</div>
				<div class="tag-bottom"></div>
			</div>
			<div id="pic-rank">
				<ul>
					<li class="current new"><a href="#" class="current" id="new-rank">最新</a></li>
					<li class="hot"><a href="#" id="hot-rank">本周最热</a></li>
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
				<div id="prompt"><a href="javascript:void(0);"></a></div>			
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