$('[data-toggle="popover"]').popover();
$('[data-toggle="tooltip"]').tooltip();

$('[data-confirm]').on('click', function(e){

    if (!confirm($(this).data('confirm'))) {
        return false;
    }

    return true;
});

var $container = $('.grid');

$container.imagesLoaded( function(){
  $container.masonry({
    itemSelector : '.grid-item'
  });
});

$('[data-toggle-comment]').on('click', function(e){
    e.preventDefault();
    e.stopPropagation();
    $('.right-panel').toggleClass('opened');
});

$('[data-more-info]').on('click', function(e){
    e.preventDefault();
    e.stopPropagation();
});

$('[data-error-close]').on('click', function(e){
    e.preventDefault();
    e.stopPropagation();

    $('.error').remove();
});
