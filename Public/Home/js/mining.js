xuetong={
    //图片轮换
    slide:function(name){
        $(name).slide({
            titCell: ".hd ul",
            mainCell: ".bd ul",
            effect: "fade",
            autoPlay: true,
            autoPage: true,
            trigger: "click",
            interTime: 5000,
            delayTime: 1200,
            startFun: function (i) {
                var curLi = jQuery(".fullSlide .bd li").eq(i);
                if (!!curLi.attr("_src")) {
                    curLi.css("background-image", curLi.attr("_src")).removeAttr("_src")
                }
            }
        });

    },
    gwc:function(){
        $("#subCrowdNum").click(function (){
            var num = $("#crowd_num").val();
            num = parseInt(num);
            if (isNaN(num)) num = 0;
            num --;
            num = num < 1 ? 1 : num;
			$('.invite_num').text(num);
            $("#crowd_num").val(num);
        });
        $("#addCrowdNum").click(function (){
            var num = $("#crowd_num").val();
            num = parseInt(num);
            if (isNaN(num)) num = 0;
            num ++;
			$('.invite_num').text(num);
            $("#crowd_num").val(num);
        });
    }
}