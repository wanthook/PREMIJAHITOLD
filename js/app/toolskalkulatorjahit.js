/*
 * Created by Taufiq Hari Widodo (c) 2013
 * wanthook@gmail.com
 * http://www.facebook.com/taufiq
 * http://wanthook.wordpress.com
 * 
 */

$(document).ready(function()
{
    $("#cmdCalc")
    .button()
    .click(function(e)
    {
        e.preventDefault();
        
        var dt = $("#kalkulator").serialize();
//        console.log(dt);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "./controller/con_kalkulator_jahit.php",
            data: dt
          }).done(function( msg ) {
            //alert( "Data Saved: " + msg );
//            var oMsg = JQuery.parseJSON(msg);
//            console.log(msg.ukuran);
                $("#persen").val(msg.persen);
                $("#pcs").val(msg.pcs);
                $("#targetpcs").val(msg.minpcs);
                $("#size").val(msg.ukuran+" CM");
                $("#duit").val(msg.premi);
          });
    });
});

