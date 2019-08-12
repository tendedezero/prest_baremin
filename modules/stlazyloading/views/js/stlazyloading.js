$(document).ready(function() {
  prestashop.stlazyloading = function(images) {
    images.each(function(){
          $(this).waypoint(function () {
              var $element = $(this.element);
              this.destroy();
              $element.on('load', function () {
                $(this).parent().removeClass('is_stlazyloading');
                $(this).removeClass('stlazyloadthis');
                Waypoint.refreshAll();
              });
              $element.attr('src', $element.data('src'));
          },{offset: '100%'});
    });
  };


  prestashop.on('updateProductList', function(data) {
    var images = $('img.stlazyloadthis');
    if(images.size())
      prestashop.stlazyloading(images);
  });
  var images = $('img.stlazyloadthis');
  if(images.size())
    prestashop.stlazyloading(images);
});