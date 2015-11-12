$(document).ready(function(){
    $(".tabContents").hide(); // Hide all tab content divs by default
    $(".tabContents:first").show(); // Show the first div of tab content by default

    $(".tabContaier ol li a").click(function(){ //Fire the click event

        var activeTab = $(this).attr("href"); // Catch the click link
        $(".tabContaier ol li a").removeClass("active"); // Remove pre-highlighted link
        $(this).addClass("active"); // set clicked link to highlight state
        $(".tabContents").hide(); // hide currently visible tab content div
        $(activeTab).fadeIn(); // show the target tab content div by matching clicked link.

        return false; //prevent page scrolling on tab click
    });
});