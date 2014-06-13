/**
 * Created by Razvan on 6/13/14.
 */

var generalInputValues = {

    inputClass : "inputHolderDesign"

};

var LabelObject = {

    labelClass : "",
    labelClickAction : "openLabelEditor();",
    labelText : "",
    inputType : "label",
    defaultText : "Label",
    inputTemplate : null,

    create : function(){
        this.inputTemplate = document.createElement("div");
        this.labelClass = generalInputValues.inputClass;
        this.constructInputHolder();
        this.addInput();
    },

    constructInputHolder : function(){
        this.inputTemplate.setAttribute("class",    this.labelClass);
        this.inputTemplate.setAttribute("inputType",this.inputType);
        this.inputTemplate.setAttribute("labelText",this.labelText);
        this.inputTemplate.setAttribute("onClick",  this.labelClickAction);
        this.inputTemplate.innerHTML = this.defaultText;
    },

    addInput : function()
    {
        document.getElementById("selectedInputPanel").appendChild(this.inputTemplate);
    }

};


function addInputAction()
{

    var selectInputHolderValue = jQuery("#selectInputHolder").val();

    switch (selectInputHolderValue)
    {
        case "L"    :
        {
            LabelObject.create();
        }
    }

}


