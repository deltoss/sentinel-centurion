/* 
    Most of the tristate magic is done via CSS.
    However, we need JS to handle submitting multiple
    tristate checkboxes under a common field name.
*/
function createTristateHiddenIfNotExists(tristateElement, inputName)
{
    var $tristateElement = $(tristateElement);
    var $hiddenInput = $tristateElement.find("[name='" + inputName + "']");
    if (!$hiddenInput || $hiddenInput.length < 1)
    {
        $hiddenInput = $( "<input type='hidden' class='tristate-hidden' />" ).attr("name", inputName);
        $tristateElement.append($hiddenInput);
    }
    return $hiddenInput[0];
}

function updateTristateInputs(tristateElement, radioButtonToCheck){
    var $radioButtonToCheck = $(radioButtonToCheck);
    var $tristateElement = $(tristateElement);
    var value = $radioButtonToCheck.val();
    
    // Get the name to submit the tristate buttons as
    var name = $radioButtonToCheck.data("tristate-name");
    if (!name || name.length < 0) // Defaults when the tristate name is not specified
    {
        if ($radioButtonToCheck.hasClass("deny-checkbox"))
            name = "denied[]";
        else if ($radioButtonToCheck.hasClass("accept-checkbox"))
            name = "accepted[]";
        else if ($radioButtonToCheck.hasClass("null-checkbox"))
            name = "null[]";
        else
            throw "Could not get the tristate input name";
    }
    
    var existingHiddenInputs = $tristateElement.find(".tristate-hidden");
    existingHiddenInputs.prop('disabled', true); // As to not submit the inactive hidden inputs
    var hiddenInput = createTristateHiddenIfNotExists(tristateElement, name);;
    $(hiddenInput).prop('disabled', false); // To allow only the active input to be submitted with form
    
    // Manually switch other radios of when a radio is checked
    // This is so that radios would work, even if they're not 
    // grouped by using the "name" attribute, which would 
    // cause the radios to be submitted
    $tristateElement.find(":radio").not(radioButtonToCheck).prop("checked", false);
    $(hiddenInput).val(value);
};