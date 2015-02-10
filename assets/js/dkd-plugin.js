 jQuery(document).ready(function($) {
   $(window).scroll(function () {
    if ( $(this).scrollTop() > 500 )
    $("a.rtt_button").fadeIn();
    else
    $("a.rtt_button").fadeOut();
    });

    $("a.rtt_button").click(function () {
    $("body,html").animate({ scrollTop: 0 }, 800 );
    return false;
    });
});