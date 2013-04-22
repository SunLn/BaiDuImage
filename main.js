
$(window).load(function() {
	$('.flexslider').flexslider({
		animation: "slide",
		controlsContainer: ".flex-container"
	});
	$("#pic-box li.flow").mouseover(function(){
		$(".pic-action",this).css('visibility','visible');
	});
	$("#pic-box li.flow").mouseout(function(){
		$(".pic-action",this).css('visibility','hidden');
	});
    $.scrolltotop({
	   	className: 'totop',
	   	controlHTML : '<a title="返回顶部" hidefocus="hidefocus" href="javascript:void(0);" class="up_to_top"> </a>',
	   	offsety:0
   });		
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
									innerString +=			'<a href="javascript:void(0);" class="pic-link"><img src="images/loadingPD.gif" data-original="PetImage/'+data[j*5+i].url+'" width="'+data[j*5+i].xSize+'" height="'+data[j*5+i].ySize+'" class="pic-url"></a>';
									innerString +=				'<div class="pic-info">';
									innerString +=					'<a href="javascript:void(0);" class="pic-title">'+data[j*5+i].tag+'</a>';
									innerString +=					'<a href="javascript:void(0);" class="from-site">来自';
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
								innerString +=			'<a href="javascript:void(0);" class="pic-link"><img src="PetImage/'+data[j*5+i].url+'" width="'+data[j*5+i].xSize+'" height="'+data[j*5+i].ySize+'" class="pic-url"></a>';
								innerString +=				'<div class="pic-info">';
								innerString +=					'<a href="javascript:void(0);" class="pic-title">'+data[j*5+i].tag+'</a>';
								innerString +=					'<a href="javascript:void(0);" class="from-site">来自';
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
		INTERVALID = setInterval(queryTag,500);
		$(".flow img").lazyload({ threshold : 500 });
	});
	$('#s-input').blur(function(){
		clearInterval(INTERVALID);
	});
	$(".tag-content a").click(function(){
		queryTag($(this).html());
		$(".tag-content a").removeClass("current");
		$(this).parents().siblings().removeClass("current");
		$(this).parents().addClass("current");
		$(this).addClass("current");
		return false;
	});	
	$(".tag-suggest-link").click(function(){
		queryTag($('span',this).html());		
		return false;
	});	
	$(".flow img").lazyload({ threshold : 500 });	
	function queryTag(tag) {
		lastScrollTopForAjax = $(window).scrollTop();
		var vObject = getValueToAjax();
		$.get("Flow.php", {
			typeRank : vObject.typeRank,
			typeSize : vObject.typeSize,
			name     : vObject.name,
			tag      : (tag? tag :vObject.tag),							
			queryType : 'tag'
		}, function (data,textStatus){
			var picFlowBox = $("#pic-box ul.col");
			picFlowBox.empty();
			if(vObject.typeSize==2){
				for(var i=0; i<5; i++) {
					for(var j=0; j<4; j++) {
						var innerString   =  '<li class="flow">';
							innerString +=		'<div class="pic-innerbox">';
							innerString +=			'<a href="javascript:void(0);" class="pic-link"><img src="PetImage/'+data[j*5+i].url+'" ></a>';
							innerString +=				'<div class="pic-info">';
							innerString +=					'<a href="javascript:void(0);" class="pic-title">'+data[j*5+i].tag+'</a>';
							innerString +=					'<a href="javascript:void(0);" class="from-site">来自';
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
	}
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
});
