jQuery(function(t) {
   t(".phone-mask").mask("+7 (999) 999-99-99")
}),
new WOW().init();
$(function(){

$('.scrollink').on('click', function(e){
  $('html,body').stop().animate({ scrollTop: $('#contact_us').offset().top }, 1000);
  e.preventDefault();
});

});
$(window).load(function () {

		$("#scrolling").endlessScroll({
			width: "100%", // Ширина строки
			height: "50px", // Высота строки
			steps: -2, // Шаг анимации в пикселях. Если число отрицательное - движение влево, положительное - вправо
			speed: 60, // Скорость анимации (0 - максимальная)
			mousestop: false // Останавливать ли полосу при наведении мыши (да - true, нет - false)
		});

	});
  $(window).load(function () {

  		$("#scrolling_top").endlessScroll({
  			width: "100%", // Ширина строки
  			height: "50px", // Высота строки
  			steps: -2, // Шаг анимации в пикселях. Если число отрицательное - движение влево, положительное - вправо
  			speed: 60, // Скорость анимации (0 - максимальная)
  			mousestop: false // Останавливать ли полосу при наведении мыши (да - true, нет - false)
  		});

  	});

    function setVideoCenter() {
    	var $box = $('.video-box');
    	var height = $box.height();
    	var width = $box.width();
    	var new_height = width / 1.78;
    	if (new_height > height) {
    		$box.find('video').css({
    			width: width,
    			height: new_height,
    			top: '-' + (new_height / 2 - height / 2) + 'px',
    			left: '0',
    		});
    	} else {
    		var new_width = height * 1.78;
    		$box.find('video').css({
    			width: new_width,
    			height: height,
    			top: '0',
    			left: '-' + (new_width / 2 - width / 2) + 'px'
    		});
    	}
    }

    $(function(){
    	setVideoCenter();
    	$(window).resize(setVideoCenter);
    });
