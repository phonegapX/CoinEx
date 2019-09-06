$(document).ready(function(){
	$('input').focus(function(){
		var t=$(this);
		if(t.attr('type')=='text'||t.attr('type')=='password'){
			t.css({'box-shadow':'0px 0px 3px #1583fb','border':'1px solid #1583fb','color':'#999'});
		}
		if(t.val()==t.attr('placeholder'))
			t.val('');
	});
	$('input').blur(function(){
		var t=$(this);
		if(t.attr('type')=='text'||t.attr('type')=='password')
			t.css({'box-shadow':'none','border':'1px solid #e1e1e1','color':'#999' });
		if(t.attr('type')!='password'&&!t.val())
			t.val(t.attr('placeholder'));
	});
});