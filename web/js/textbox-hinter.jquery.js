(function($){

 $.fn.tbHinter = function(options) { // name of function. options specify the text and style

 var defaults = {
    text: 'Enter a text ...',
    styleClass: ''
};

var options = $.extend(defaults, options);

return this.each(function(){
    $(this).focus(function(){
        if($(this).val() == options.text){
        $(this).val('');
        $(this).removeClass(options.styleClass);
        }
    });

    $(this).blur(function(){
        if($(this).val() == ''){
        $(this).val(options.text);
        $(this).addClass(options.styleClass);
        }
    });

    $(this).blur();

});

};

})(jQuery);
