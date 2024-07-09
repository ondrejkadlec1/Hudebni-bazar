$(document).ready(function(){

    var windowBackground = $(document).find(".windowBackground");

    function showBackground(){
        windowBackground.show();
        $('html, body').css({
            height: '100%'
        });
    }
    function hideBackground(){
        windowBackground.hide();
        $('html, body').css({
            overflow: 'auto',
            height: 'auto'
        });
    }
    var newAdvert = $(document).find(".newAdvert");
    var form = $(document).find("#frm-advertForm");

    newAdvert.on('click', function (){
        showBackground();
        form.show();

    });

    windowBackground.on('click', function (){
        hideBackground();
        form.hide();
    });

});