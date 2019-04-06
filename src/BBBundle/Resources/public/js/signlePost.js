$(document).ready(function() {

    var comms_route = Routing.generate('bb_load_comments', {drawId:idDraw});
    $('.load_comms').click(function (e) {
        e.preventDefault();
        $('.load_comms').hide();
        $.getJSON(comms_route)
            .done(function(data){
                $.each(data,function (key, val) {
                    var date = new Date(val.c_createdAt.date.substr(0, 10));
                    var heure = val.c_createdAt.date.substr(11, 5);
                    $('.comms_container').append('<div class="row single_post_row"> '+
                        '<div class="col-sm-12 head_com">'+
                        '<span class="">le '+ date.toLocaleDateString('fr-FR') +' à '+ heure +'</span>'+
                        '</div>'+
                        '</div>'+
                        '<div class="row single_post_row">'+
                        '<div class="col-sm-12 form_bg com_area">'+
                        '<div>'+
                        '<span class="comment_quote">"</span>'+
                        '<p class="com_content">'+ val.c_message +'</p>'+
                        '<span class="comment_quote_bottom">"</span>'+
                        '</div>'+
                        '<span class="pull-right com_author"><strong>'+ val.c_pseudo +'</strong></span><span class="pull-right">Ecrit par &nbsp</span>'+
                        '</div>'+
                        '</div>');
                });
            });
    });

    $('#commentaire').hide();

    $('.com').click(function () {
        $('#commentaire').fadeToggle(100);
    });
});
