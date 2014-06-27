/**
 * Created by Razvan on 6/20/14.
 */

function openFieldEditor(input)
{

    var inputType = input.getAttribute("inputType");

    jQuery("fieldset[type]").hide();
    jQuery("fieldset[type='"+inputType+"']").show();

}

////aici o sa mai vina functiile de completare dupa ce au fost create inputurile

