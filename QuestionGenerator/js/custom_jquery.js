$(document).ready(function ()
{
   // tooltip
   //$(document).tooltip({ position: "center right"});

   //		show editTitleDiv div
   $("#editTitleImage, #title").click(function ()
   {
      $("#editTitleDiv").show("slow");
   });

   //		hide editTitleDiv div
   $("#cancelTitle").click(function ()
   {
      $("#editTitleDiv").hide("slow");
   });

   //      save temporaryTitle on title input
   $("#saveTitle").click(function ()
   {
      $("#editTitleDiv").hide("slow");
      var a = document.getElementById('temporaryTitle');
      var b = document.getElementById('title');
      b.value = a.value;

      //display feedback

   });

   //      clear button
   $("#clearTitle").click(function ()
   {
      var a = document.getElementById('temporaryTitle');
      a.value = '';
   });

   // button style
   $("input[type=submit], button")
            .button()
            .click(function (event)
            {
               event.preventDefault();
            });

   $(function ()
   {
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
   $("#editHelpTextImage, #help").click(function ()
   {
      $("#editHelpTextDiv").show("slow");
   });

   //		hide editHelpTextDiv div
   $("#cancelHelp").click(function ()
   {
      $("#editHelpTextDiv").hide("slow");
   });

   //		show editInputTypeDiv div
   $("#editInputType, #input").click(function ()
   {
      $("#editInputTypeDiv").show("slow");
   });

   //		hide editInputTypeDiv div
   $("#cancelInputType").click(function ()
   {
      $("#editInputTypeDiv").hide("slow");
   });

   /*$("#saveHelp").click(function ()
   {
      $("#editHelpTextDiv").hide("slow");
      var a1 = document.getElementById('temporaryHelpText');
      var b1 = document.getElementById('help');
      b1.value = a1.value;
   });*/

   /*//send Ajax - POST
   $("#saveQuestion").click(function ()
   {
      var ajaxObject = {
         actionType: "new",
         name: $("#sessionName").val(),
         sessionName: $("#sessionEntry").val(),
         title: $("#title").val(),
         help: $("#help").val()
      };

      $.ajax({
         type: "POST",
         url: "php/QuestionGenerator.php",
         data: ajaxObject,
         success: function (response)
         {
            var JSONObject = JSON.parse(response);
            var feedbackDiv = document.getElementById("feedbackId");

            if (JSONObject.message == 'Success')
            {
               feedbackDiv.style.display = 'block';
            }
         },
         error: function ()
         {
            alert("ERROR !");
         }
      });
   });*/




    jQuery("#saveQuestion").click(function(){
        sendData();
    });

    function sendData()
    {

        var questionObject = {
            title : "dsa"
        };

        console.log(questionObject);
        ajaxRequest(questionObject,"setNewQuestion");

    }

    function ajaxRequest(questionObject,operation)
    {

        jQuery.ajax({
            type : "POST",
            url  : "php/QuestionGenerator.php",
            data : {
                operation      : operation,
                questionObject : questionObject
            }
        })
            .done(function( msg ) {
                alert( "Data Saved: " + msg );
            });

    }





});

