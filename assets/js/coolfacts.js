/*
 Cool Facts Ajax Load Another Quote
*/
jQuery(document).ready(function($) {

    // Setup click handler to initiate the Ajax Request
    $("#anotherwidgetquote").click(function(e) {

        e.preventDefault(); // prevent hash mark

        $.ajax(Ajax.ajax_url, {
            type: "GET", // only a get request
            data: {
                action: 'load_random_quote', // the action hooked into the function
            },
            beforeSend: function() {
                $("#coolwidgetquote").empty(); // empty the container before each request
            },
            success: function(response) {
                $("#coolwidgetquote").html(response); // send the data to the container
            }
        });

    });

});
