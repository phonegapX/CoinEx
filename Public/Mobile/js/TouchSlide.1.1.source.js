/*!
 * TouchSlide v1.1
 * javascript触屏滑动特效插件，移动端滑动特效，触屏焦点图，触屏Tab切换，触屏多图切换等
 * 详尽信息请看官网：http://www.SuperSlide2.com/TouchSlide/
 *
 * Copyright 2013 大话主席
 *
 * 请尊重原创，保留头部版权
 * 在保留版权的前提下可应用于个人或商业用途

 * 1.1 宽度自适应（修复安卓横屏时滑动范围不变的bug）
 */
var TouchSlide = function(a){
	
	a = a||{};
	var opts = {
		slideCell:a.slideCell || "#touchSlide", //运行效果主对象，必须用id！，例如 slideCell:"#touchSlide"
		titCell:a.titCell || ".hd li", // 导航对象，当自动分页设为true时为“导航对象包裹层”
		mainCell:a.mainCell || ".bd", // 切换对象包裹层
		effect:a.effect || "left", // 效果，支持 left、leftLoop
		autoPlay:a.autoPlay || false, // 自动播放
		delayTime:a.delayTime || 200, // 效果持续时间
		interTime:a.interTime ||2500, // 自动运行间隔
		defaultIndex:a.defaultIndex ||0, // 默认的当前位置索引。0是第一个； defaultIndex:1 时，相当于从第2个开始执行
		titOnClassName:a.titOnClassName ||"on", // 当前导航对象添加的className
		autoPage:a.autoPage || false, // 自动分页，当为true时titCell为“导航对象包裹层”
		prevCell:a.prevCell ||".prev", // 前一页按钮
		nextCell:a.nextCell ||".next", // 后一页按钮
		pageStateCell:a.pageStateCell ||".pageState", // 分页状态对象，用于显示分页状态，例如：2/3
		pnLoop:a.pnLoop=='undefined '?true:a.pnLoop , // 前后按钮点击是否继续执行效果，当为最前/后页是会自动添加“prevStop”/“nextStop”控制样色
		startFun:a.startFun || null, // 每次切换效果开始时执行函数，用于处理特殊情况或创建更多效果。用法 satrtFun:function(i,c){ }； 其中i为当前分页，c为总页数
		endFun:a.endFun || null, // 每次切换效果结束时执行函数，用于处理特殊情况或创建更多效果。用法 endFun:function(i,c){ }； 其中i为当前分页，c为总页数
		switchLoad:a.switchLoad || null //每次切换效果结束时执行函数，用于处理特殊情况或创建更多效果。用法 endFun:function(i,c){ }； 其中i为当前分页，c为总页数
	}

	var slideCell = document.getElementById(opts.slideCell.replace("#",""));
	if( !slideCell ) return false;


	//简单模拟jquery选择器
	var obj = function(str,parEle){ 
		str = str.split(" ");
		var par = [];
		parEle = parEle||document;
		var retn = [ parEle ] ;
		for( var i in str ){ if(str[i].length!=0) par.push(str[i]) } //去掉重复空格
		for( var i in par ){ 
			if( retn.length==0 ) return false;
			var _retn = [];
			for ( var r in retn )
			{
				if( par[i][0] =="#" ) _retn.push( document.getElementById( par[i].replace("#","") ) );
				else if( par[i][0] =="." ){
					var tag = retn[r].getElementsByTagName('*');
					for( var j=0; j<tag.length; j++ ){
						var cln = tag[j].className;
						if( cln && cln.search(new RegExp("\\b" + par[i].replace(".","") + "\\b"))!=-1 ){ _retn.push( tag[j] ); }
					}
				}
				else { var tag = retn[r].getElementsByTagName( par[i] ); for( var j=0; j<tag.length; j++ ){ _retn.push( tag[j] ) } }
			}
			retn =_retn;
		}
		
		return retn.length==0 || retn[0] == parEle ? false:retn;
	}// obj E

	// 创建包裹层
	var wrap = function(el, v){
			var tmp = document.createElement('div');
			tmp.innerHTML = v;
			tmp = tmp.children[0];
			var _el = el.cloneNode(true);
			tmp.appendChild(_el);
			el.parentNode.replaceChild(tmp, el);
			conBox = _el; // 重置conBox
			return tmp;
	};

	// 获取样色数值
	var getStyleVal =function(el, attr){ var v=0; if(el.currentStyle){ v= el.currentStyle[attr] } else { v= getComputedStyle(el,false)[attr]; } return parseInt(v.replace("px","")) } 

	// class处理
	var addClass =function(ele, className){
		 if (!ele || !className || (ele.className && ele.className.search(new RegExp("\\b" + className + "\\b")) != -1)) return;
		 ele.className += (ele.className ? " " : "") + className;
	}

	var removeClass = function(ele, className){
		 if (!ele || !className || (ele.className && ele.className.search(new RegExp("\\b" + className + "\\b")) == -1)) return;
		 ele.className = ele.className.replace(new RegExp("\\s*\\b" + className + "\\b", "g"), "");
	}

	//全局对象
	var effect = opts.effect;
	var prevBtn = obj( opts.prevCell,slideCell )[0];
	var nextBtn = obj( opts.nextCell,slideCell )[0];
	var pageState = obj( opts.pageStateCell )[0];
	var conBox = obj( opts.mainCell,slideCell )[0];//内容元素父层对象
	if( !conBox ) return false;
	var conBoxSize= conBox.children.length;
	var navObj = obj( opts.titCell,slideCell );//导航子元素结合
	var navObjSize = navObj?navObj.length:conBoxSize;
	var sLoad=opts.switchLoad;

	/*字符串转换*/
	var index=parseInt(opts.defaultIndex);
	var delayTime=parseInt(opts.delayTime);
	var interTime=parseInt(opts.interTime);
	var autoPlay = (opts.autoPlay=="false"||opts.autoPlay==false)?false:true;
	var autoPage = (opts.autoPage=="false"||opts.autoPage==false)?false:true;
	var loop = (opts.pnLoop=="false"||opts.pnLoop==false)?false:true;
	var oldIndex = index;
	var inter=null;// autoPlay的setInterval
	var timeout = null; // leftLoop的setTimeout
	var endTimeout = null;  //translate的setTimeout
	
	var startX = 0;
	var startY = 0;
	var distX = 0;
	var distY = 0;
	var dist = 0; //手指滑动距离
	var isTouchPad = (/hp-tablet/gi).test(navigator.appVersion);
	var hasTouch = 'ontouchstart' in window && !isTouchPad;
	var touchStart = hasTouch ? 'touchstart' : 'mousedown';
	//var touchMove = hasTouch ? 'touchmove' : 'mousemove';
	var touchMove = hasTouch ? 'touchmove' : '';
	var touchEnd = hasTouch ? 'touchend' : 'mouseup';
	var slideH=0;
	var slideW= conBox.parentNode.clientWidth;// mainCell滑动距离
	var twCell;
	var scrollY ;
	var tempSize = conBoxSize;
	
	//处理分页
	if( navObjSize==0 )navObjSize=conBoxSize;
	if( autoPage ){
		navObjSize=conBoxSize;
		navObj=navObj[0];
		navObj.innerHTML=""; 
		var str="";

		if( opts.autoPage==true|| opts.autoPage=="true" ){ for( var i=0; i<navObjSize; i++ ){ str+="<li>"+(i+1)+"</li>" } }
		else{ for( var i=0; i<navObjSize; i++ ){ str+=opts.autoPage.replace("$",(i+1))  } }
		
		navObj.innerHTML=str;
		navObj = navObj.children;//重置navObj
	}

	
	
	if( effect == "leftLoop" ){
		tempSize +=2;
		conBox.appendChild( conBox.children[0].cloneNode(true) );
		conBox.insertBefore( conBox.children[conBoxSize-1].cloneNode(true),conBox.children[0] );
	}
	twCell = wrap(conBox,'<div class="tempWrap" style="overflow:hidden; position:relative;"></div>');
	conBox.style.cssText="width:"+tempSize*slideW+"px;"+"position:relative;overflow:hidden;padding:0;margin:0;";
	for ( var i =0; i<tempSize; i++ ){  conBox.children[i].style.cssText="display:table-cell;vertical-align:top;width:"+slideW+"px"  }
	

	var doStartFun=function(){ if ( typeof opts.startFun =='function' ){ opts.startFun( index,navObjSize ) } }
	var doEndFun=function(){ if (  typeof opts.endFun =='function' ){ opts.endFun( index,navObjSize ) } }
	var doSwitchLoad=function( moving ){ 
				var curIndex = ( effect=="leftLoop"?index+1:index ) + moving;
				var changeImg = function( ind ){
						var img = conBox.children[ind].getElementsByTagName("img");
						for ( var i=0; i<img.length ; i++ )
						{
							if ( img[i].getAttribute(sLoad) ){ 
								img[i].setAttribute("src", img[i].getAttribute(sLoad) ); 
								img[i].removeAttribute( sLoad );
							} 
						}
				}// changeImg E
				changeImg( curIndex );
				if( effect=="leftLoop" ){
					switch ( curIndex )
					{
						case 0: changeImg( conBoxSize );break;
						case 1: changeImg( conBoxSize+1 );break;
						case conBoxSize: changeImg( 0 );break;
						case conBoxSize+1: changeImg( 1 );break;
					}
				}
	}// doSwitchLoad E

	//动态设置滑动宽度
	var orientationChange = function(){ 
		slideW = twCell.clientWidth;
		conBox.style.width = tempSize*slideW +"px";
		for ( var i =0; i<tempSize; i++ ){  conBox.children[i].style.width=slideW+"px";  }
		var ind = effect == "leftLoop"? index+1:index;
		translate(  -ind*slideW ,0 );
	} 
	window.addEventListener("resize", orientationChange, false); 


	//滑动效果
	var translate = function( dist, speed, ele ) {
		if( !!ele ){ ele=ele.style; }else{ ele=conBox.style; }
		ele.webkitTransitionDuration =  ele.MozTransitionDuration = ele.msTransitionDuration = ele.OTransitionDuration = ele.transitionDuration =  speed + 'ms';
		ele.webkitTransform = 'translate(' + dist + 'px,0)' + 'translateZ(0)';
		ele.msTransform = ele.MozTransform = ele.OTransform = 'translateX(' + dist + 'px)';		
	}

	//效果函数
	var doPlay=function(isTouch){
		
		switch (effect)
		{
			case "left": 
				if ( index >= navObjSize) { index = isTouch?index-1:0; } else if( index < 0) { index = isTouch?0:navObjSize-1; } 
				if( sLoad!=null ){ doSwitchLoad(0) } 
				translate(  (-index*slideW),delayTime ); oldIndex=index; break;
				
				
			case "leftLoop":
				if( sLoad!=null ){ doSwitchLoad(0) }
				translate(  -(index+1)*slideW ,delayTime );
				if ( index==-1){ 
						timeout= setTimeout( function(){ translate( -navObjSize*slideW ,0 ); }, delayTime );
						index = navObjSize-1; 
				}
				else if( index==navObjSize ){ timeout= setTimeout( function(){ translate( -slideW ,0 ); }, delayTime );
						index = 0; 
				}
				oldIndex=index;
				break;// leftLoop end

		}//switch end
		doStartFun();
		endTimeout= setTimeout( function(){ doEndFun() }, delayTime );

		//设置className
		for ( var i=0; i<navObjSize; i++ )
		{
			removeClass(navObj[i],opts.titOnClassName);
			if( i == index ){ addClass(navObj[i],opts.titOnClassName) }
		}

		if( loop==false ){ //loop控制是否继续循环
			removeClass( nextBtn,"nextStop" );removeClass( prevBtn,"prevStop" );
			if (index==0 ){ addClass( prevBtn,"prevStop" ) }
			else if (index==navObjSize-1 ){ addClass( nextBtn,"nextStop" ) }
		}
		if(pageState){ pageState.innerHTML= "<span>"+(index+1)+"</span>/"+navObjSize; }

	};// doPlay end

	//初始化执行
	doPlay();
	
	//自动播放
	if (autoPlay) {
		 inter=setInterval(function(){ index++; doPlay() }, interTime); 
	}

	//点击事件
	if( navObj ){
		for ( var i=0; i<navObjSize; i++ )
		{		
			(function(){
				var j = i;
				navObj[j].addEventListener('click', function(e){ clearTimeout( timeout ); clearTimeout( endTimeout );  index=j; doPlay();  })
			})()
			
		}
	}
	if(nextBtn){ nextBtn.addEventListener('click', function(e){ if ( loop==true || index!=navObjSize-1 ){ clearTimeout( timeout ); clearTimeout( endTimeout ); index++; doPlay(); } }) }
	if(prevBtn){ prevBtn.addEventListener('click', function(e){ if ( loop==true || index!=0 ){ clearTimeout( timeout ); clearTimeout( endTimeout ); index--; doPlay(); } }) }



	//触摸开始函数
	var tStart = function(e){
		clearTimeout( timeout );clearTimeout( endTimeout );
		scrollY = undefined;
		distX = 0;
		var point = hasTouch ? e.touches[0] : e;
		startX =  point.pageX;
		startY =  point.pageY;

		//添加“触摸移动”事件监听
		conBox.	addEventListener(touchMove, tMove,false);
		//添加“触摸结束”事件监听
		conBox.addEventListener(touchEnd, tEnd ,false);
	}

	//触摸移动函数
	var tMove = function(e){
		if( hasTouch ){ if ( e.touches.length > 1 || e.scale && e.scale !== 1) return }; //多点或缩放

		var point = hasTouch ? e.touches[0] : e;
		distX = point.pageX-startX;
		distY = point.pageY-startY;

		if ( typeof scrollY == 'undefined') { scrollY = !!( scrollY || Math.abs(distX) < Math.abs(distY) ); }
		if( !scrollY ){ 
			e.preventDefault(); if(autoPlay){clearInterval(inter) }
			switch (effect){
				case "left":
					if( (index==0 && distX>0) || (index>=navObjSize-1&&distX<0 )){ distX=distX*0.4 }
					translate( -index*slideW+distX ,0 );
				break;
				case "leftLoop":translate( -(index+1)*slideW+distX ,0 );break;
			}
			
			if(  sLoad!=null && Math.abs(distX)>slideW/3 ){ 
				doSwitchLoad( distX>-0?-1:1 ) 
			}
		}
	}

	//触摸结束函数
	var tEnd = function(e){
		if(distX==0) return;
		e.preventDefault(); 
		if( !scrollY )
		{
			if( Math.abs(distX) > slideW/10  ){ distX>0? index--: index++; }
			doPlay( true );  
			if (autoPlay) {
				 inter=setInterval(function(){ index++; doPlay() }, interTime); 
			}
		}

		conBox.removeEventListener(touchMove, tMove, false);
		conBox.removeEventListener(touchEnd, tEnd, false);
	}


	//添加“触摸开始”事件监听
	conBox.addEventListener(touchStart, tStart ,false);


}// TouchSlide E

