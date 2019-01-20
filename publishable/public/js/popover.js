window.initializeButtonPopover = function(jqueryElement) {
    jqueryElement.popover({
        html: true,
        trigger: "focus",
        content: function(){
            var element = this;
            var $element = $(element);
            var popoverContentElement = $element.next('.popover-html-content')
            var popoverContent = popoverContentElement.html();
            return popoverContent;
        },
        placement: function(element, triggerElement){
            var placement = $(triggerElement).data("placement");
            if (placement)
                return placement;
            else
                return "bottom"; // Defaults to bottom
        },
        // We add the extra class "btn-stack" to the default popover markup
        template: '<div class="popover btn-stack" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    });

    // Adjust the style correctly once the element is unfocused
    jqueryElement.on('hide.bs.popover', function(){
        $(this).removeClass('active');
    });
}

$(document).ready(function(){
    initializeButtonPopover($('[data-toggle="button-popover"]'));
});