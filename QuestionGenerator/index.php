<!DOCTYPE html>

<html lang="en">
<!-- [Start Head] -->
<head>
   <meta charset="UTF-8">
   <title>Question generator</title>

   <!-- start css included -->
   <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
   <link rel="stylesheet" type="text/css" href="css/style.css">
   <!-- end css included -->

   <!-- start js included -->
   <script type="text/javascript" src="js/jquery-1.10.2.js"></script>
   <script type="text/javascript" src="js/jquery-ui.js"></script>
   <script type="text/javascript" src="js/custom_jquery.js"></script>
   <script type="text/javascript" src="js/AddInputHandler.js"></script>
   <script type="text/javascript" src="js/InputClickHandler.js"></script>
   <!-- end js included -->

</head>
<!-- [End Head] -->

<!-- [Start Body] -->
<body>
<div id="siteContent" class="siteContent ui-widget">
<!-- start header -->
<div class="sectionHeader">
   <div id="header">
      <a href="http://www.quotezone.co.uk/" title="Quotezone Home" rel="home" id="logo">
         <img src="css/images/logo.png" alt="Quotezone Home">
      </a>
   </div>
</div>
<!-- end header -->

<!-- start center -->
<div class="sectionCenter">
<div class="leftSection">

   <br clear="all"/>
</div>
<div class="rightSection">
<form action="" method="get" name="" id="myForm">
<div class="rowClassNoBorder">
   <p class="rowTitle ui-widget-header ui-corner-top">Question Details</p>
</div>
<div class="rowClassBorder">
   <div class="rowContent">
      <div class="rowQuestion">
         <p class="labelText">Name:</p>
         <input type="text" name="sessionName" id="sessionName" size="35" class="" title="Tooltip on right">

         <br clear="all"/>
      </div>
      <div class="rowQuestion">
         <p class="labelText">Session Entry:</p>
         <input type="text" name="sessionEntry" id="sessionEntry"
                title="Please specify the session entry E.g DRIVERS,0" class="" size="35">
         <br clear="all"/>
      </div>
   </div>
</div>
<div class="rowClassNoBorder">
   <p class="rowTitle ui-widget-header ui-corner-top">Question Generator</p>
</div>
<div class="rowClassBorder">
   <div class="rowContent">
      <div class="generatedQuestionContent">

         <div class="divClass" id="title" style="float: left; width:250px;">Title...<img src="css/images/edit.ico"
                                                                                         class="pointerClass"
                                                                                         id="editTitleImage"
                                                                                         width="16px" height="16px">
         </div>


         <div class="divClass" id="help" style="float: right; width:250px">Help text...<img src="css/images/help.png"
                                                                                            class="pointerClass"
                                                                                            id="editHelpTextImage">
         </div>

         <div class="divClass" id="input">Input type...<img src="css/images/edit.ico" class="pointerClass"
                                                            id="editInputType" width="16px" height="16px"></div>

      </div>
      <div class="optionPanel">
         <button type="submit" id="saveQuestion">Save Question</button>
         <div style="display: none;" id="feedbackId">
            <img src="css/images/small_tick.png" class="marginRight5" id=""><span class="marginRight5">Success</span>
            <img src="css/images/cancel.png" class="marginRight5" id=""><span class="marginRight5">Failed</span>
         </div>

      </div>

   </div>

</div>
<!-- edit Title -->
<div class="" id="editTitleDiv" style="display: none;">
   <div class="rowClassNoBorder">
      <p class="rowTitle ui-widget-header ui-corner-top">Question Title</p>
   </div>
   <div class="rowClassBorder textAlignRight">
      <div class="rowContent">
         <input type="text" name="temporaryTitle" id="temporaryTitle">
         <br clear="all"/>
         <button class='margin10' type="button" name="clearTitle" id="clearTitle">Clear</button>
         <button class='margin10' type="button" name="cancelTitle" id="cancelTitle">Cancel</button>
         <button class='margin10' type="button" name="saveTitle" id="saveTitle">Save Title</button>
      </div>
   </div>
</div>
<!-- edit Input Type -->
<div id="editInputTypeDiv" style="display: none;">
   <div class="rowClassNoBorder">
      <p class="rowTitle ui-widget-header ui-corner-top">Input type</p>
   </div>
   <div class="rowClassBorder">
      <div class="rowQuestion">
         <br clear="all"/>
         Select input types:
         <select id="selectInputHolder">
            <option value="" selected>Please select...</option>
            <option value="L">Label</option>
            <option value="T">Text</option>
            <option value="R">Radio button</option>
            <option value="C">Checkbox</option>
            <option value="S">Select</option>
            <option value="B">Button</option>
         </select>
         <button class='margin10' type="button" name="" id="" onclick="addInputAction();">Add input</button>
      </div>

      <div class="optionInputPanel" id="selectedInputPanel">
         <br clear="all"/>

      </div>
      <br clear="all"/>
      <fieldset type="label" style="display: none">
         <legend>LABEL</legend>
         <div class="rowQuestion">
            <p class="labelText">Label description: </p>
            <input type="text" name="" id="" title="" class="" size="70">
            <br clear="all"/>
         </div>

         <div class="rowQuestion">
            <p class="labelText">Label name:</p>
            <input type="text" name="" id="" title="" class="" size="70">
            <br clear="all"/>
         </div>

         <div class="rowQuestion textAlignCenter">
            <button class='margin10' type="button" name="" id="">Delete input</button>
            <button class='margin10' type="button" name="" id="">Cancel</button>
            <button class='margin10' type="button" name="" id="">Save LABEL input</button>
         </div>
      </fieldset>


      <fieldset type="text" style="display: none">
         <legend>TEXT</legend>
         <div class="rowQuestion">
            <p class="labelText">Session name:</p>
            <input type="text" name="" id="" title="" class="" size="35">
            <br clear="all"/>
         </div>
         <div class="rowQuestion textAlignCenter">
            <button class='margin10' type="button" name="" id="">Delete input</button>
            <button class='margin10' type="button" name="" id="">Cancel</button>
            <button class='margin10' type="button" name="" id="">Save TEXT input</button>
         </div>
      </fieldset>

      <fieldset type="radio" style="display: none">
         <legend>RADIO</legend>
         <div class="rowQuestion">

            <label for="radio1">
               <input id="radio1" name="checkbox" type="radio" checked="checked"/>
               <input type="text" name="" id="" title="" class="" size="35" value="Choice 1...">
               <input type="text" name="" id="" title="" class="" size="30" value="Choice name...">
               <input type="text" name="" id="" title="" class="" size="25" value="Session value...">
            </label>
            <br clear="all"/>
         </div>
         <div class="rowQuestion textAlignCenter">
            <button class='margin10' type="button" name="" id="">Delete input</button>
            <button class='margin10' type="button" name="" id="">Cancel</button>
            <button class='margin10' type="button" name="" id="">Save RADIO input</button>
         </div>
      </fieldset>

      <fieldset type="checkbox" style="display: none">
         <legend>CHECKBOX</legend>
         <div class="rowQuestion">

            <label for="checkbox1">
               <input id="checkbox1" name="checkbox" type="checkbox" checked="checked"/>
               <input type="text" name="" id="" title="" class="" size="35" value="Choice 1...">
               <input type="text" name="" id="" title="" class="" size="30" value="Choice name...">
               <input type="text" name="" id="" title="" class="" size="25" value="Session value...">
            </label>

            <br clear="all"/>
         </div>
         <div class="rowQuestion textAlignCenter">
            <button class='margin10' type="button" name="" id="">Delete input</button>
            <button class='margin10' type="button" name="" id="">Cancel</button>
            <button class='margin10' type="button" name="" id="">Save CHECKBOX input</button>
         </div>
      </fieldset>


      <fieldset type="select" style="display: none">
         <legend>SELECT</legend>
         <div class="rowQuestion" optionType="option" optionNumber="0">
            <p class="labelOption">Option 1: </p>
            <input type="text" name="" id="" title="" class="" size="25">
            <span class="labelOption">Session value:</span>
            <input type="text" name="" id="" title="" class="" size="25">
            <br clear="all"/>
         </div>

         <div class="rowQuestion" optionType="option" optionNumber="1">
            <p class="labelOption">Option 2: </p>
            <input type="text" name="" id="" title="" class="" size="25">
            <span class="labelOption">Session value:</span>
            <input type="text" name="" id="" title="" class="" size="25">
            <br clear="all"/>
         </div>

         <div class="rowQuestion" style="border: 1px solid black" onclick="addSelectOption();">
            <p class="labelOption">Add more...</p>
         </div>

         <br clear="all"/>

         <div class="rowQuestion textAlignCenter">
            <button class='margin10' type="button" name="" id="">Delete input</button>
            <button class='margin10' type="button" name="" id="">Cancel</button>
            <button class='margin10' type="button" name="" id="">Save SELECT input</button>
         </div>
      </fieldset>

      <fieldset type="button" style="display: none">
         <legend>BUTTON</legend>
         <div class="rowQuestion">
            <div class="rowQuestion">
               <p class="labelText">Button description: </p>
               <input type="text" name="" id="" title="" class="" size="70">
               <br clear="all"/>
            </div>

            <div class="rowQuestion">
               <p class="labelText">Button name:</p>
               <input type="text" name="" id="" title="" class="" size="70">
               <br clear="all"/>
            </div>
            <br clear="all"/>
         </div>
         <div class="rowQuestion textAlignRight">
            <button class='margin10' type="button" name="" id="">Delete input</button>
            <button class='margin10' type="button" name="" id="">Cancel</button>
            <button class='margin10' type="button" name="" id="">Save BUTTON input</button>
         </div>
      </fieldset>
      <br clear="all"/>

      <div class="optionPanel">
         <button class='margin10' type="button" name="clearHelp" id="clearInputType">Reset</button>
         <button class='margin10' type="button" name="cancelHelp" id="cancelInputType">Cancel</button>
         <button class='margin10' type="button" name="saveHelp" id="saveInputType">Save inputs</button>
      </div>
   </div>


</div>
<!-- edit Help Text -->
<div id="editHelpTextDiv" style="display: none;">
   <div class="rowClassNoBorder">
      <p class="rowTitle ui-widget-header ui-corner-top">Help Text</p>
   </div>
   <div class="rowClassBorder textAlignRight">
      <div class="rowContent">
         <input type="text" name="temporaryHelpText" id="temporaryHelpText">
         <br clear="all"/>
         <button class='margin10' type="button" name="clearHelp" id="clearHelp">Clear</button>
         <button class='margin10' type="button" name="cancelHelp" id="cancelHelp">Cancel</button>
         <button class='margin10' type="button" name="saveHelp" id="saveHelp">Save Help Text</button>
      </div>
   </div>
</div>
</div>
</div>

</form>
<br clear="all"/>
</div>
<br clear="all"/>
</div>
<br clear="all"/>
<!-- end center -->

<!-- start footer -->
<div class="sectionFooter">
</div>
<!-- end footer -->

</div>


</body>
<!-- [End Body] -->

</html>