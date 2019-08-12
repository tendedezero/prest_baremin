var stlazyloading = function(images) {
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
var stlazyloading_mater = function(){
  var images = $('img.stlazyloadthis');
  if(images.size())
    stlazyloading(images);
}
$(document).ready(function() {
  stlazyloading_mater();
  $(document).on('click', '#grid,#list', function(e){stlazyloading_mater();});
});