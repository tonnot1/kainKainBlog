$(document).ready(function($){

    var $container = $('#container');
    // initialize
    var $grid = $('.grid').masonry({
        // columnWidth: 292,
        gutter: 3,
        //containerStyle: null,
        percentPosition: true,
        horizontalOrder: true,

        itemSelector: '.grid-item'
    });
        // effet gigante
    $grid.on('click','.grid-item', function (){
        // $(this).toggleClass('grid-item--gigante');
        // $(this).children('.doodle_title_bandeau').toggle();
        // $(this).next().modal();
        $(this).next().next().css('display', 'block');

        //$(".grid-item img").toggleClass("item_clicked");

        // $grid.masonry();

    });

    $('.discart-doodle').click(function () {
        $(this).css('display', 'none');
        $(this).prev().modal('hide');
        $('.modal-backdrop.in').remove()
    });

    // $grid.on('click','.grid-item img', function (){
    //     $(this).toggleClass('item_clicked');
    //
    //     //$(".grid-item img").toggleClass("item_clicked");
    //
    //     $grid.masonry();
    //
    // });
        //images loaded

     $grid.imagesLoaded().progress(function(){
         $grid.masonry();
     });
});
