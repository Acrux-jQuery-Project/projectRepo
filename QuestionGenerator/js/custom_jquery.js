$( document ).ready(function() {
// tooltip			
$(document).tooltip({ position: "center right"});

//		show editTitleDiv div 	
$("#editTitleImage, #title").click(function () {
    $("#editTitleDiv").show("slow");
});

//		hide editTitleDiv div 
$("#cancelTitle").click(function () {
    $("#editTitleDiv").hide("slow");
});

//      save temporaryTitle on title input   
$("#saveTitle").click(function () {
    $("#editTitleDiv").hide("slow");
    var a = document.getElementById('temporaryTitle');
    var b = document.getElementById('title');
    b.value = a.value;
});

//      clear button 
$("#clearTitle").click(function () {
    var a = document.getElementById('temporaryTitle');
    a.value = '';
});

// button style
$("input[type=submit], button")
    .button()
    .click(function (event) {
        event.preventDefault();
    });

$(function () {
    var availableTags = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++"
    ];
    $("#sessionEntry").autocomplete({
        source: availableTags
    });
});

//		show editHelpTextDiv div 	
$("#editHelpTextImage").click(function () {
    $("#editHelpTextDiv").show("slow");
});

//		hide editHelpTextDiv div 
$("#cancelHelp").click(function () {
    $("#editHelpTextDiv").hide("slow");
});
    });