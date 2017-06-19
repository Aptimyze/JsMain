var parentEl, ink, d, x, y;
$(document).ready(function () {
  var nua = navigator.userAgent;
  if (nua.indexOf("UCBrowser") == -1 && nua.indexOf("Native") == -1) {
    $(document).on('click', '.blueRipple', function (e) {
      parentEl = $(this).parent();
      //create .ink element if it doesn't exist
      if (parentEl.find(".blueInk").length == 0)
        parentEl.prepend("<span class='blueInk'></span>");
      ink = parentEl.find(".blueInk");
      //incase of quick double clicks stop the previous animation
      ink.removeClass("animate");
      //set size of .ink
      if (!ink.height() && !ink.width())

      {
        //use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
        d = Math.max(parentEl.outerWidth(), parentEl.outerHeight());
        ink.css({height: d, width: d});
      }
      //get click coordinates
      //logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
      x = e.pageX - parentEl.offset().left - ink.width() / 2;
      y = e.pageY - parentEl.offset().top - ink.height() / 2;
      //set the position and add class .animate
      ink.css({top: y + 'px', left: x + 'px'}).addClass("animate");
      setTimeout(function(){ink.removeClass("animate"); }, 650)

     
    });
    //Grey Ripple
    $(document).on('click', '.greyRipple', function (e) {
      parentEl = $(this).parent();
      if (parentEl.find(".greyInk").length == 0)
        parentEl.prepend("<span class='greyInk'></span>");
      ink = parentEl.find(".greyInk");
      ink.removeClass("animate");

      if (!ink.height() && !ink.width())
      {
        d = Math.max(parentEl.outerWidth(), parentEl.outerHeight());
        ink.css({height: d, width: d});
      }

      x = e.pageX - parentEl.offset().left - ink.width() / 2;
      y = e.pageY - parentEl.offset().top - ink.height() / 2;

      ink.css({top: y + 'px', left: x + 'px'}).addClass("animate");
    });
    //Pink Ripple
    $(document).on('click', '.pinkRipple', function (e) {
      parentEl = $(this).parent();
      //create .ink element if it doesn't exist
      if (parentEl.find(".ink").length == 0)
        parentEl.prepend("<span class='ink'></span>");

      ink = parentEl.find(".ink");
      //incase of quick double clicks stop the previous animation
      ink.removeClass("animate");

      //set size of .ink
      if (!ink.height() && !ink.width())
      {
        //use parent's width or height whichever is larger for the diameter to make a circle which can cover the entire element.
        d = Math.max(parentEl.outerWidth(), parentEl.outerHeight());
        ink.css({height: d, width: d});
      }

      //get click coordinates
      //logic = click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center;
      x = e.pageX - parentEl.offset().left - ink.width() / 2;
      y = e.pageY - parentEl.offset().top - ink.height() / 2;

      //set the position and add class .animate
      ink.css({top: y + 'px', left: x + 'px', 'z-index': '9999'}).addClass("animate");
    });
  }
});