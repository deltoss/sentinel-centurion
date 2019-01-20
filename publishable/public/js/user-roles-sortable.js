function toggleEmptyClass(sortableInstance, emptyClass)
{
    var listEl = sortableInstance.el;
    var visibleChildren =  new Array();
    // Add visible child to visibleChildren variable
    for (var i = 0; i < listEl.children.length; i++)
    {
        var child = listEl.children[i];
        if (child.style.display != "none" && child.style.visible != "hidden")
            visibleChildren.push(child); // add item
    }
    // Add/Remove the empty class
    var emptyClasses = emptyClass.split(" ");
    for (var i = 0; i < emptyClasses.length; i++)
    {
        var item = emptyClasses[i];
        if (visibleChildren.length < 1 && !listEl.classList.contains(item))
            listEl.classList.add(item);
        else
            listEl.classList.remove(item);
    }
}
function changeAddButtonsToRemoveButtons(itemMoved)
{
    $itemMoved = $(itemMoved);
    var addButtonClass = "add-button";
    var removeButtonClass = "remove-button";
    var addButtons = $itemMoved.find("." + addButtonClass);
    if (addButtons.length > 0)
    {
        addButtons.removeClass(addButtonClass);
        addButtons.removeClass("btn-success");
        addButtons.addClass(removeButtonClass);
        addButtons.addClass("btn-danger");
        addButtons.attr("title", "Remove");
        addButtons.html('<i class="fa-fw fas fa-minus"></i>');
    }
    return addButtons;
}
function changeRemoveButtonsToAddButtons(itemMoved)
{
    $itemMoved = $(itemMoved);
    var addButtonClass = "add-button";
    var removeButtonClass = "remove-button";
    var removeButtons = $itemMoved.find("." + removeButtonClass);
    if (removeButtons.length > 0)
    {
        removeButtons.removeClass(removeButtonClass);
        removeButtons.removeClass("btn-danger");
        removeButtons.addClass(addButtonClass);
        removeButtons.addClass("btn-success");
        removeButtons.attr("title", "Add");
        removeButtons.html('<i class="fa-fw fas fa-plus"></i>');
    }
    return removeButtons;
}

function updateInputs(sortableInstance, inputName)
{
    inputName = inputName.trim();
    if (inputName.endsWith("[]"))
        inputName = inputName.substring(0, inputName.length - 2); // Chops off the "[]"
    
    var inputContainerId = inputName + "HiddenInputsContainer";
    var inputContainer = document.getElementById(inputContainerId);
    
    // If it doesn't exist, create one
    if (inputContainer == null)
    {
        inputContainer = document.createElement("div");
        inputContainer.setAttribute("id", inputContainerId);
        inputContainer.style.display = "none";
        sortableInstance.el.parentElement.insertBefore(inputContainer, sortableInstance.el);
    }
    
    // Clear all items in input container
    while (inputContainer.firstChild) {
        inputContainer.removeChild(inputContainer.firstChild);
    }
    
    var values = sortableInstance.toArray();
    for (var i = 0; i < values.length; i++)
    {
        var value = values[i];
        input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", inputName + "[]");
        input.value = value;
        inputContainer.appendChild(input);
    }
}