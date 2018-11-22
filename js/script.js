$('[data-toggle="popover"]').popover();
$('[data-toggle="tooltip"]').tooltip();
$('[data-toggle2="tooltip"]').tooltip();

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

$('[data-form-comment]').on('submit', function(e){
    var form = $(this);
    var url = form.attr('action');

    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function(data) {
            form.parents('[data-comments]').find('[data-comments-list]').append(data);
            form.find('textarea').val('');
        }
    });

    e.preventDefault();
})

$(document).on('click', '[data-delete-comment]', function(e){
    if (confirm('Supprimer ce commentaire ?')) {
        var comment = $(this).parents('[data-comment]');
        var url = $(this).attr('href');

        $.ajax({
            type: "GET",
            url: url,
            success: function(data) {
                comment.remove();
            }
        });
    }

    e.preventDefault();
})
