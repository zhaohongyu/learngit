requirejs.config({
	paths:{
		jquery:'jquery.min'
	}
});
requirejs(['jquery','validate','scrollto','backtop'],function($,validate,scrollto,backtop){

	//使用jquery插件形式
	$("#backTop").backtop({
		mode:'move',
		speed:1000,
		pos:800,
		dest:100
	});

	// var backtop=new backtop.BackTop($('#backTop'),{
	// 	mode:'move',
	// 	speed:1000
	// });

	// //修复首屏刷新后显示回到顶部的bug
	// backtop._checkPosition();
	// //console.log(backtop._checkPosition);


	// var scroll = new scrollto.ScrollTo({
	// 	dest:0,
	// 	speed:2000
	// });

	// //$('body').css('background-color','blue');
	// //console.log(validate.isEqual("5","5"));
	// //$('#backTop').on('click',go);
	// //$('#backTop').on('click',move);
	// $('#backTop').on('click',$.proxy(scroll.move,scroll));
	// $(window).on("scroll",function(){
	// 	//console.log($(window).height());
	// 	//console.log($(window).scrollTop());
	// 	checkPosition($(window).height());
	// });

	// //修复首屏刷新后显示回到顶部的bug
	// checkPosition($(window).height());
	// /**
	//  * 快速移动到顶部
	//  */
	// function go(){
	// 	$('html,body').scrollTop(0);
	// }
	// /**
	//  * 移动到顶部
	//  */
	// function move(){
	// 	$('html,body').animate({
	// 		scrollTop:0
	// 	},800);
	// }

	// /**
	//  * 检查滚动到的位置
	//  * @param  {[pos]} 首屏高度
	//  */
	// function checkPosition(pos){
	// 	if($(window).scrollTop()>pos){
	// 		$('#backTop').fadeIn();
	// 	}else{
	// 		$('#backTop').fadeOut();
	// 	}
	// }


});