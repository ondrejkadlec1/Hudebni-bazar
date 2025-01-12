$(document).ready(function(){

    // navbar
    const groupBar = $(document).find(".categories-bar", ".subcategories-bar", ".subsubcategories-bar");
    const categoriesButton = $(document).find(".show-categories");
    const subcategoriesButton = $(document).find(".category");
    const subsubcategoriesButton = $(document).find(".subcategory");
    const filterSelectBox = $(document).find("#frm-filterForm").find("select");
    function showElement(myElement){
        myElement.stop();
        myElement.slideDown(200, function(){});
    }
    function hideElement(myElement){
        myElement.stop().slideUp(200, function(){});
    }

    groupBar.on('mouseover', function(){
        showElement($(this));
    });

    groupBar.on('mouseleave', function(){
        hideElement($(this));
    });
    categoriesButton.on('mouseover', function (){
            showElement($(document).find(".categories-bar"));
        }
    );
    categoriesButton.on('mouseleave', function(){
            hideElement($(document).find(".categories-bar"));
        }
    );
    subcategoriesButton.on('mouseover', function(){
        showElement($(this).children("ul"));
        }
    );
    subcategoriesButton.on('mouseleave', function(){
            hideElement($(this).children("ul"));
        }
    );
    subsubcategoriesButton.on('mouseover', function(){
            showElement($(this).children("ul"));
        }
    );
    subsubcategoriesButton.on('mouseleave', function(){
            hideElement($(this).children("ul"));
        }
    );
    filterSelectBox.on('mouseover', function(){
        $(this).css("height", 'auto');
        }
    );
    filterSelectBox.on('mouseleave', function(){
            $(this).css("height", '30px');
        }
    );
    // form
    const windowBackground = $(document).find(".windowBackground");
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

    const newAdvert = $(document).find(".newAdvert");
    const form = $(document).find("#frm-advertForm");

    newAdvert.on('click', function (){
        showBackground();
        form.show();
    });

    windowBackground.on('click', function (){
        hideBackground();
        form.hide();
    });
});