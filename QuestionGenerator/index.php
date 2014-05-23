<!DOCTYPE html>

<html lang="en">
<!-- [Start Head] -->
<head>
   <meta charset="UTF-8">
   <title>Question generator</title>
   <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
   <script src="//code.jquery.com/jquery-1.10.2.js"></script>
   <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
   <link rel="stylesheet" type="text/css" href="css/style.css">
   
   <script type="text/javascript" src="js/custom_jquery.js"></script>
   <!--  <link rel="stylesheet" type="text/css" href="css/jquery-ui.css"> -->

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
         <form action="" method="get" name="" id="myform">
              <div class="rowClassNoBorder">
               <p class="rowTitle ui-widget-header ui-corner-top">Question Details</p>
			  </div>
              <div class="rowClassBorder">
               <div class="rowContent">
                  <div class="rowQuestion">
                     <p class="labelText">Name:</p>
                     <input type="text" name="sessionName" id="sessionName" title="Please specify the session name" class=""
                            size="35">

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
				     
					 <div class="divClass" id="resizable" style="float: left; width:250px;">Title...<img src="css/images/edit.ico" class="pointerClass" id="editTitleImage" width="16px" height="16px"></div>
                     
                     
                     <div class="divClass" id="help" style="float: right; width:250px">Help text...<img src="css/images/help.png" class="pointerClass" id="editHelpTextImage"></div>
				
					 <div class="divClass" id="input" >Input type...<img src="css/images/edit.ico" class="pointerClass" id="editInputType" width="16px" height="16px"></div>
                  
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
					  <select>
					      <option value="" selected>Please select...</option>
						  <option value="L">Label</option>
						  <option value="R">Radio button</option>
						  <option value="C">Checkbox</option>
						  <option value="S">Select</option>
					</select>
					
					<button class='margin10' type="button" name="" id="">Add input</button>
					  
					  <br clear="all"/>
					  <br clear="all"/>
					  <div class="textAlignRight">
						  <button class='margin10' type="button" name="clearHelp" id="clearInputType">Clear</button>
						  <button class='margin10' type="button" name="cancelHelp" id="cancelInputType">Cancel</button>
						  <button class='margin10' type="button" name="saveHelp" id="saveInputType">Save Help Text</button>
					  </div>
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