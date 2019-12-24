// main js funcs are here

var timer;


$(document).ready(function()
{
    $(".result").on("clicks", function()
    {
        var id = $(this).attr("data-linkId");
        var url = $(this).attr("href");

        if(!id)
        {   alert("data-linkId attribute not found");   }

        increaseLinkClicks(id, url);

        return false;
    });

    var grid =$(".imageResults");

    grid.on("layoutComplete", function()
    {
        $(".gridItem img").css("visibility", "visible");
    });

    grid.masonry({
        itemSelected: ".gridItem",
        columnWidth: 200,
        getter: 5,
        // transitionDuration: '0.2s',
        isInitLayout: false 

    });

    $("[data-fancybox]").fancybox({

        caption : function( instance, item ) {
            var caption = $(this).data('caption') || '';
            var siteUrl = $(this).data('siteurl') || '';
    
            if ( item.type === 'image' ) {
                caption = (caption.length ? caption + '<br />' : '')
                 + '<a href="' + item.src + '"> View image </a>' 
                 + '<a href="' + siteUrl + '">| Visit page </a>' 
            }
    
            return caption;
        },

        afterShow : function (instance, item)
        {
            increaseImageClicks(item.src);
        }


    });

});

function loadImage(src, className)
{
    var image = $("<img>");
    
    image.on("load", function() 
    {
        $("." + className + " a").append(image);
        
        clearTimeout(timer); // 

        timer = setTimeout(function()
            {   $(".imageResults").masonry();}, 500);
    });

        image.on("error", function() {

            $("." + className).remove();

            $.post("setBroken.php", {src :src});

        } ); 

    image.attr("src", src);

}

function increaseLinkClicks(linkId, url)
{
    $.post("updateLinkCount.php", {linkId: linkId})
    .done( function(result)
    {
        if(result != "")
        {
            alert(result);
            return;
        }
    });
}

function increaseImageClicks(imageUrl)
{
    $.post("updateImageCount.php", {imageUrl:imageUrl})
    .done( function(result)
    {
        if(result != "")
        {
            alert(result);
            return;
        }
    });
}