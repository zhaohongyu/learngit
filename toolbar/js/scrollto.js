define(['jquery'],function($){
	function ScrollTo(opts){
		//将用户传递的参数opts覆盖ScrollTo.DEFAULTS生成{}赋值给this.opts
		this.opts=$.extend({},ScrollTo.DEFAULTS,opts);
		this.$el=$('html,body');
	}
	ScrollTo.prototype.move=function(){
		var opts=this.opts,
			dest=this.opts.dest;
		//没有到滚动达目的地
		if($(window).scrollTop!=dest){
			//没有在进行动画
			if(!this.$el.is(":animated")){
				this.$el.animate({
					scrollTop:dest
				},opts.speed);
			}
		}
	}
	ScrollTo.prototype.go=function(){
		var dest=this.opts.dest;
		//没有到滚动达目的地
		if($(window).scrollTop!=dest){
			this.$el.scrollTop(dest);
		}
	}
	ScrollTo.DEFAULTS={
		dest:0,
		speed:800
	};
	return {
		ScrollTo:ScrollTo
	}
});