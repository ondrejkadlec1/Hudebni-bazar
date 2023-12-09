$(document).ready(function(){

    var windowBackground = $(document).find(".windowBackground");

    function showBackground(){
        windowBackground.show();
        $('html, body').css({
            overflow: 'hidden',
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
    var newPost = $(document).find(".newPost");
    var form = $(document).find("#frm-postForm");

    newPost.on('click', function (){
        showBackground();
        form.show();

    });

    windowBackground.on('click', function (){
        hideBackground();
        form.hide();
    });

});