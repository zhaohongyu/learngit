define(['jquery','scrollto'],function($,scrollto){
	//$('body').css('background-color','green');
	function BackTop(el,opts){
		this.opts=$.extend({},BackTop.DEFAULTS,opts);
		this.$el=$(el);
		this.scroll=new scrollto.ScrollTo({
			dest:this.opts.dest,
			speed:this.opts.speed
		});
		if(this.opts.mode=='move'){
			this.$el.on('click',$.proxy(this._move,this));
		}else{
			this.$el.on('click',$.proxy(this._go,this));
		}
		$(window).on("scroll",$.proxy(this._checkPosition,this));
	}
	BackTop.DEFAULTS={
		mode:'move',
		pos:$(window).height(),
		speed:800,
		dest:0
	}
	BackTop.prototype._move=function(){
		this.scroll.move();
	}
	BackTop.prototype._go=function(){
		this.scroll.go();
	}
	BackTop.prototype._checkPosition=function(){
		var $el=this.$el;
		//console.log($(window).scrollTop()+":"+this.opts.pos);
		if($(window).scrollTop()>this.opts.pos){
			$el.fadeIn();
		}else{
			$el.fadeOut();
		}
	}

	//注册成jquery插件
	$.fn.extend({
		backtop:function(opts){
			return this.each(function(){
				new BackTop(this,opts)._checkPosition();
			});
		}
	});

	return {
		BackTop:BackTop
	}
});