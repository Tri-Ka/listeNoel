$('[data-toggle="popover"]').popover();
$('[data-toggle="tooltip"]').tooltip({ trigger: "hover" });
$('[data-toggle2="tooltip"]').tooltip({ trigger: "hover" });

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
            var objectId = form.find('[data-object-id]').val();
            form.parents('[data-comments]').find('[data-comments-list]').append(data);
            form.find('textarea').val('');
            $('[data-comments-count-' + objectId+']').html(parseInt($('[data-comments-count-'+objectId+']').html()) + 1);
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
                var objectId = comment.parents('.modal').attr('data-object-id');
                comment.remove();
                $('[data-comments-count-' + objectId+']').html(parseInt($('[data-comments-count-'+objectId+']').html()) - 1);
            }
        });
    }

    e.preventDefault();
})

$('[data-toggle-notif]').click(function(e){
    e.preventDefault();

    $('[data-notif]').toggleClass('opened');

    var url = $(this).attr('href');

    $.ajax({
        type: "GET",
        url: url
    });
})

$(document).ready(function(){
    var newNotifs = $('.notification.new').length

    if (0 < newNotifs) {
        $('<span>').html(newNotifs).addClass('notif-number').appendTo('[data-toggle-notif]');

    }

    var hash = window.location.hash;
    $(hash).find('[data-show-modal]').click();

    $('[data-notif-link').click(function(e){
        console.log('ok');
        var hash = '#' + $(this).attr('href').split("#")[1];
        $(hash).find('[data-show-modal]').click();
    })
})

$('body').click(function(e){
    if (0 == $(e.target).parents('[data-notif]').length) {
        $('[data-notif]').removeClass('opened');
    }
})

$('[data-switch-gifted]').on('change', function(e){
    if ($(this).is(':checked')) {
        $('body').addClass('show-gifted');
    } else {
        $('body').removeClass('show-gifted');
    }
});

autosize($('textarea'));
