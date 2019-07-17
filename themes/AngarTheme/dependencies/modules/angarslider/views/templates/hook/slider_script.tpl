{*
* @author	Krzysztof Pecak
* @copyright	2017 Krzysztof Pecak
* @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<script>
$(window).load(function(){
		$('#angarslider').bxSlider({
			maxSlides: 1,
			slideWidth: 1920,
			infiniteLoop: true,
			auto: true,
			pager: {$pager},
			autoHover: {$pause_hover},
			speed: 500,
			pause: {$pause},
			adaptiveHeight: true,
			touchEnabled: true
		});
});
</script>
