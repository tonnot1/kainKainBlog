$(document).ready(function($){


    var $container = $('#container');
    // initialize
    var $grid = $('.grid').masonry({
        // columnWidth: 292,
        gutter: 30,
        //containerStyle: null,
        // percentPosition: true,
        horizontalOrder: true,

        itemSelector: '.grid-item'
    });
        // effet gigante
    $grid.on('click','.grid-item', function (){
        $(this).toggleClass('grid-item--gigante');

        //$(".grid-item img").toggleClass("item_clicked");

        $grid.masonry();

    });

    $grid.on('click','.grid-item img', function (){
        $(this).toggleClass('item_clicked');

        //$(".grid-item img").toggleClass("item_clicked");

        $grid.masonry();

    });

        //images loaded
     $grid.imagesLoaded().progress(function(){
         $grid.masonry();
     });







});