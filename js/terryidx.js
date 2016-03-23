/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */
$(document).ready(function(){
//    load_container("home.php");
    $("a.menuUtama").click(function(e)
    {
        e.preventDefault();
        var href = $(this).attr("href");
        
        load_container(href);     
    });  
    
    $("a.lgout").click(function(e)
    {
        e.preventDefault();
        $('<div></div>').appendTo('body')
            .html('<div>Are you sure you want to logout?</div>')
            .dialog({
                modal: true, title: 'message', zIndex: 10000, autoOpen: true,
                width: 'auto', resizable: false,
                buttons: {
                    Yes: function () {
                        window.location="logout.php";
                        $(this).dialog("close");
                    },
                    No: function () {
                        $(this).dialog("close");
                    }
                },
                close: function (event, ui) {
                    $(this).remove();
                }
          });
    });
    
    $("#accordion").accordion();
	load_container("home.php");
});

var downloadURL = function downloadURL(url) {
    var hiddenIFrameID = 'hiddenDownloader',
        iframe = document.getElementById(hiddenIFrameID);
    if (iframe === null) {
        iframe = document.createElement('iframe');
        iframe.id = hiddenIFrameID;
        iframe.style.display = 'none';
        document.body.appendChild(iframe);
    }
    iframe.src = url;
};