/**
 * Created by Razvan on 6/13/14.
 */

var generalInputValues = {

    inputClass : "inputHolderDesign",
    clickAction: "inputClickHandler",
    labelClickAction : "openFieldEditor(this);"

};

var LabelObject = {

    labelClass : "",
    labelClickAction : generalInputValues.labelClickAction,
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
        this.inputTemplate.setAttribute("clickAction",generalInputValues.clickAction);
        this.inputTemplate.setAttribute("labelText",this.labelText);
        this.inputTemplate.setAttribute("onClick",  this.labelClickAction);
        this.inputTemplate.innerHTML = this.defaultText;
    },

    addInput : function()
    {
        document.getElementById("selectedInputPanel").appendChild(this.inputTemplate);
    }

};

var TextObject = {

    textClass : "",
    labelClickAction : generalInputValues.labelClickAction,
    sessionEntry : "",
    inputType : "text",
    defaultText : "Text",
    inputTemplate : null,

    create : function(){
        this.inputTemplate = document.createElement("div");
        this.textClass = generalInputValues.inputClass;
        this.constructInputHolder();
        this.addInput();
    },

    constructInputHolder : function(){
        this.inputTemplate.setAttribute("class",    this.textClass);
        this.inputTemplate.setAttribute("inputType",this.inputType);
        this.inputTemplate.setAttribute("clickAction",generalInputValues.clickAction);
        this.inputTemplate.setAttribute("sessionEntry",this.sessionEntry);
        this.inputTemplate.setAttribute("onClick",  this.labelClickAction);
        this.inputTemplate.innerHTML = this.defaultText;
    },

    addInput : function()
    {
        document.getElementById("selectedInputPanel").appendChild(this.inputTemplate);
    }

};

var RadioObject = {

    radioClass : "",
    labelClickAction : generalInputValues.labelClickAction,
    sessionEntry : "",
    choiceName : "",
    labelValue : "",
    inputType : "radio",
    defaultText : "Radio",
    inputTemplate : null,

    create : function(){
        this.inputTemplate = document.createElement("div");
        this.radioClass = generalInputValues.inputClass;
        this.constructInputHolder();
        this.addInput();
    },

    constructInputHolder : function(){
        this.inputTemplate.setAttribute("class",       this.radioClass);
        this.inputTemplate.setAttribute("inputType",   this.inputType);
        this.inputTemplate.setAttribute("clickAction",generalInputValues.clickAction);
        this.inputTemplate.setAttribute("sessionEntry",this.sessionEntry);
        this.inputTemplate.setAttribute("choiceName"  ,this.choiceName);
        this.inputTemplate.setAttribute("labelValue"  ,this.labelValue);
        this.inputTemplate.setAttribute("onClick",     this.labelClickAction);
        this.inputTemplate.innerHTML = this.defaultText;
    },

    addInput : function()
    {
        document.getElementById("selectedInputPanel").appendChild(this.inputTemplate);
    }

};

var CheckboxObject = {

    checkboxClass : "",
    labelClickAction : generalInputValues.labelClickAction,
    sessionEntry : "",
    choiceName : "",
    labelValue : "",
    inputType : "checkbox",
    defaultText : "Checkbox",
    inputTemplate : null,

    create : function(){
        this.inputTemplate = document.createElement("div");
        this.checkboxClass = generalInputValues.inputClass;
        this.constructInputHolder();
        this.addInput();
    },

    constructInputHolder : function(){
        this.inputTemplate.setAttribute("class",       this.checkboxClass);
        this.inputTemplate.setAttribute("inputType",   this.inputType);
        this.inputTemplate.setAttribute("sessionEntry",this.sessionEntry);
        this.inputTemplate.setAttribute("clickAction",generalInputValues.clickAction);
        this.inputTemplate.setAttribute("choiceName"  ,this.choiceName);
        this.inputTemplate.setAttribute("labelValue"  ,this.labelValue);
        this.inputTemplate.setAttribute("onClick",     this.labelClickAction);
        this.inputTemplate.innerHTML = this.defaultText;
    },

    addInput : function()
    {
        document.getElementById("selectedInputPanel").appendChild(this.inputTemplate);
    }

};

var SelectObject = {

    selectClass : "",
    labelClickAction : generalInputValues.labelClickAction,
    sessionEntry : "",
    choiceName : "",
    labelValue : "",
    inputType : "select",
    defaultText : "Select",
    selectOptions : "{}",
    inputTemplate : null,

    create : function(){
        this.inputTemplate = document.createElement("div");
        this.selectClass = generalInputValues.inputClass;
        this.constructInputHolder();
        this.addInput();
    },

    constructInputHolder : function(){
        this.inputTemplate.setAttribute("class",          this.selectClass);
        this.inputTemplate.setAttribute("clickAction",generalInputValues.clickAction);
        this.inputTemplate.setAttribute("inputType",      this.inputType);
        this.inputTemplate.setAttribute("sessionEntry"   ,this.sessionEntry);
        this.inputTemplate.setAttribute("selectOptions"  ,this.selectOptions);
        this.inputTemplate.setAttribute("onClick",        this.labelClickAction);
        this.inputTemplate.innerHTML = this.defaultText;
    },

    addInput : function()
    {
        document.getElementById("selectedInputPanel").appendChild(this.inputTemplate);
    }

};

var ButtonObject = {

    buttonClass : "",
    labelClickAction : generalInputValues.labelClickAction,
    sessionEntry : "",
    description : "",
    name : "",
    inputType : "button",
    defaultText : "Button",
    inputTemplate : null,

    create : function(){
        this.inputTemplate = document.createElement("div");
        this.buttonClass = generalInputValues.inputClass;
        this.constructInputHolder();
        this.addInput();
    },

    constructInputHolder : function(){
        this.inputTemplate.setAttribute("class",          this.buttonClass);
        this.inputTemplate.setAttribute("inputType",      this.inputType);
        this.inputTemplate.setAttribute("clickAction",generalInputValues.clickAction);
        this.inputTemplate.setAttribute("description",      this.description);
        this.inputTemplate.setAttribute("name",      this.name);
        this.inputTemplate.setAttribute("sessionEntry"   ,this.sessionEntry);
        this.inputTemplate.setAttribute("onClick",        this.labelClickAction);
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
            LabelObject.create();
            break;
        case "T"    :
            TextObject.create();
            break;
        case "R"    :
            RadioObject.create();
            break;
        case "C"    :
            CheckboxObject.create();
            break;
        case "S"    :
            SelectObject.create();
            break;
        case "B"    :
            ButtonObject.create();
            break;
    }

}


