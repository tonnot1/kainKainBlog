PreReady = (function () {
    var navRubriquesMin;

    function init() {
        navRubriquesMin = $('#bs-example-navbar-collapse-1')
    }
    function hide() {
        navRubriquesMin.hide()
    }
    function slide() {
        navRubriquesMin.slideToggle()
    }

    return {
        init: init,
        hide: hide,
        slide: slide
    }
})();

$(document).ready(function(){

    PreReady.init();
    // PreReady.hide();
    $('.comments').hide();
    $('.slide_comments').click(function () {
        $(this).parent().next().slideToggle();
    });

    $("<div class='toTop glyphicon glyphicon-collapse-up'></div>").appendTo($('div#toTop')).hide();
    $(window).scroll(function(){
        if($(window).scrollTop()>2000){
            $('div.toTop').fadeIn('slow');

        }else{
            $('div.toTop').fadeOut('slow');
        }
    });

    $('div.toTop').click(function(){
        $("html, body").animate({scrollTop:0}, 300);

    });

    function refreshCoeur(){
        var thumb = $('.countPouces');
        thumb.innerHTML = "";
    }

    $('div.toTop').css({
        "width":"100%",
        "height":"100%",
        "text-align":"center",
        "font-size":"42px",
        "color":"white",
        "cursor":"pointer"
    });

    var coeur = $('.coeur');
    var id = coeur.data('id');
    coeur.each(function () {
        var th = $(this);
        var id = th.data('id');
        $.get(Routing.generate('bb_ajax_pouces',{id:id}), function (data) {
            th.next().html(data.pou[0].pouces);
         });
    });

    coeur.click(function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var t = $(this);
        var idClass = '.countPouces' + id;
        $.ajax({
            type: 'POST',
            url: Routing.generate('bb_pouces_up',{id:id}),
            dataType: "json",
            async: true,
            success: function (data) {
                t.next().html(data.pouce[0].pouces);
                $(idClass).html(data.pouce[0].pouces);
            }
        });

        return false;
    });
});
