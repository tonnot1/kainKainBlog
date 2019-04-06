window.addEventListener('load',function(){
    $(".outil[value=1]").addClass('outil_selected');
    var display = document.querySelector(".paint_canvas");
    var display$ = $(".paint_canvas");
    backgroundRenk = 'white';

    var pressed = false;

    var opacity = document.getElementById("opacity");
    var $button = $(".outil");

    var $eraseStroke = $('.erase_div');

    px = document.getElementById("tselect");
    var form = document.getElementById("form");
    var pxel = 48;

    // outils/ 0:eraser; 1:pinceau; 2:delete all; 3:pipette; 4:pot
    var op = 1;
    var mode = 'paint';

    // forme du pinceau au chargement
    var fm = 50;
    document.getElementById('form').value=50;

    // display.style.cursor = 'url("spray.png"), auto';

    color = document.getElementById("renk").value;

    i_counter = 0;
    j_counter = 0;

    $('.undo_stroke').attr('disabled', 'disabled');

    var potHandle = function() {
        display$.css('background-color', color);
        $(".paint_canvas").find('.erase_div').css('background-color', color);
        backgroundRenk = color;
    };

        $button.click(function(){
            console.log(mode);
            switch ($(this).val()){
                case '0':
                    mode = 'erase';
                    // display.style.cursor = 'url("eraser.png"), auto';
                    display$.unbind('click',potHandle);
                    $button.removeClass('outil_selected');
                    $(this).addClass('outil_selected');

                    break;
                case '1':
                    mode = 'paint';
                    // display.style.cursor = 'url("spray.png"), auto';
                    display$.unbind('click',potHandle);
                    $button.removeClass('outil_selected');
                    $(this).addClass('outil_selected');

                    break;
                case '2':
                    mode = 'deleteAll';
                    display.innerHTML = "";
                    display.style.backgroundColor = "white";
                    display$.unbind('click',potHandle);
                    $button.removeClass('outil_selected');
                    $(this).addClass('outil_selected');

                    break;
                case '3':
                    mode = 'pipette';
                    display.addEventListener('click',function (e) {
                    });
                    display$.unbind('click',potHandle);
                    $button.removeClass('outil_selected');
                    $(this).addClass('outil_selected');

                    break;
                case '4':
                    mode = 'pot';
                    display$.bind('click',potHandle);
                    $button.removeClass('outil_selected');
                    $(this).addClass('outil_selected');

                    break;
            }
        });

    display.addEventListener('mouseleave', function(){
        pressed = false;
    });

    document.body.addEventListener('mousedown',function(e){
        pressed = true;
    });

    document.body.addEventListener('mouseup',function(e){
        pressed = false;
    });
    var canX = display.offsetLeft;
    var canY = display.offsetTop;

    // display.addEventListener('mousemove',function(e){
    //     if(pressed){
    //         drawPix(e.clientX-120,e.clientY-120);
    //     }
    // });
    // display.addEventListener('click',function(e){
    //     drawPix(e.clientX-120,e.clientY-120);
    // });
    display$.mousemove(function(e){
        if(pressed){
            drawPix(e.clientX-120,e.clientY-120);
        }
    });
    display$.click(function(e){
        drawPix(e.clientX-120,e.clientY-120);
    });

    display.addEventListener('mousedown',function(e){
        i_counter++;
        j_counter = i_counter;
        console.log(i_counter+' '+j_counter);
        if(i_counter <= 0){
            $('.undo_stroke').attr('disabled', 'disabled');
        }else{
            $('.undo_stroke').removeAttr('disabled');
        }
    });

    px.addEventListener("change",function(){
        pxel = this.value;
    });

    form.addEventListener("change",function(){
        fm = this.value;
    });
    opacity.addEventListener("change",function () {
        op = this.value;
    });

    function drawPix(x,y){
        color = document.getElementById("renk").value;

        pix = document.createElement('div');
        pix.className = 'stroke'+i_counter;
        if(mode === 'erase'){
            pix.className = 'erase_div stroke'+i_counter;
        }

        pix.style.height = pxel+'px';
        pix.style.width = pxel+'px';

        switch(mode){
            case 'paint':
                pix.style.backgroundColor = color;
                break;
            case 'erase':
                pix.style.backgroundColor = backgroundRenk;
                break;
        }

        pix.style.borderRadius = fm+'%';
        pix.style.opacity = op;

        pix.style.top = y+"px";
        pix.style.left = x+"px";
        pix.style.position = 'absolute';

        display.appendChild(pix);
    }

    $('.paint_submit').click(function () {
       var mailInput = $('input[type=email]').val();
       setPaint(mailInput);
    });

    $('.undo_stroke').click(function (e) {
        e.preventDefault();

        var lastStroke = $(".stroke" + j_counter);
        $(".paint_canvas").find(lastStroke).remove();

        j_counter--;
        i_counter = j_counter;
        console.log(i_counter+' '+j_counter);
        if(i_counter <= 0){
            $('.undo_stroke').attr('disabled', 'disabled');
        }else{
            $('.undo_stroke').removeAttr('disabled');
        }
    });
});

function setPaint(_email) {
    var urlDlPaint = Routing.generate('bb_download_paint');

    html2canvas($('.paint_canvas').get(0)).then(function (canvas) {

        var image = canvas.toDataURL("image/png");

        $.download = function (urlDlPaint, key, data, key2, data2) {

            // Build a form
            var form = $('<form></form>').attr('action', urlDlPaint).attr('method', 'post');
            // Add the one key/value
            form.append($("<input></input>").attr('type', 'hidden').attr('name', key).attr('value', data));
            form.append($("<input></input>").attr('type', 'hidden').attr('name', key2).attr('value', data2));
            //send request
            form.appendTo('body').submit().remove();
        };

        $.download(urlDlPaint, 'data', image, 'email', _email);
    });
}
