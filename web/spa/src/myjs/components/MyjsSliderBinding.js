(function ($) {
    $.fn.Slider = function (parent, no_of_tuples,mapString,next) {
 // first get the original window dimens (thanks alot IE)
        //all constants first... but variables in objects.... BY PALASH
        var el = parent;
        var tuple_ratio = 80;
        var slider = {"threshold": 80, "working": false, "movement": true, "transform": 0, "index": 0, "maxindex": 0};
        var timeStart, timeEnd, transitionDuration = 0;

        var windowWidth = window.innerWidth;
        var transformX = (tuple_ratio * windowWidth) / 100 + 10;
        var elementWidth = transformX - 10;
        var transformX_corr = ((tuple_ratio * 3 - 100) * windowWidth) / 200 + 10+el.offsetLeft;



// dynamic variables
    window.addEventListener("resize",function()
    {
        windowWidth = window.innerWidth;
        transformX = (tuple_ratio * windowWidth) / 100 + 10;
        elementWidth = transformX - 10;
        transformX_corr = ((tuple_ratio * 3 - 100) * windowWidth) / 200 + 10+el.offsetLeft;
    };

        var init = function () {
            if (el.length == 0)
                return;
            if (next){
    } else ob.page=-1;
            WrapParent();
            AddCssToSelf();
            AlterChildrenCss();
            initTouch();

        }

        var initOnNewTuples = function (length) {
            childElement = el.children();

            ob._maxindex=ob._tupleIndex-ob._lmHidden;
            el.css("width", (transformX * (ob._tupleIndex+1) + 10) + "px");
            addIndex();
            bindSlider();
        }


        var addIndex = function () {
            childElement = el.children();
            $.each(childElement, function (index, element) {
                $(element).attr("index", index);
            });
            ob._maxindex=ob._tupleIndex-ob._lmHidden;
        }


        var WrapParent = function ()
        {
            var wrapbox = el.parent();
            slider.parent = wrapbox;
            slider.parent.parent = wrapbox.parent();

        }


        var AddCssToSelf = function ()
        {

            el.css("width", (transformX * (ob._tupleIndex+1) + 10) + "px");

        }



        var AlterChildrenCss = function ()
        {

            $.each(childElement, function (index, element) {
                $(element).css('width', transformX - 10);
                $(element).attr("index", index);
                if (index==ob._tupleIndex) return;
            });
            ob._maxindex = ob._tupleIndex-ob._lmHidden;
                    }

        var initTouch = function ()
        {
            setPositionProperty(ob._index);

            slider.touch = {
                start: {x: 0, y: 0},
                end: {x: 0, y: 0}
            };
            slider.parent.bind('touchstart', onTouchStart);
            // bind a "touchmove" event to the viewport
                slider.parent.bind('touchmove', onTouchMove);
                // bind a "touchend" event to the viewport
                slider.parent.bind('touchend', onTouchEnd);


        }

        var onTouchStart = function (e)
        {
              if (slider.working) {
                e.preventDefault();
            } else {

                // record the original position when touch starts
                slider.touch.originalPos = el.position();
                timeStart = (new Date()).getTime();
                var orig = e.originalEvent;
                // record the starting touch x, y coordinates
                slider.touch.start.x = orig.changedTouches[0].pageX;
                slider.touch.start.y = orig.changedTouches[0].pageY;
                }
        }
        var onTouchMove = function (e)
        {

            var orig = e.originalEvent;

            var xMovement = Math.abs(orig.changedTouches[0].pageX - slider.touch.start.x);
            var yMovement = Math.abs(orig.changedTouches[0].pageY - slider.touch.start.y);
            var change = orig.changedTouches[0].pageX - slider.touch.start.x;
            if (yMovement>xMovement) {
                return ;
            }
            if (!yMovement)
                yMovement = 1;
            //console.log(xMovement+" "+yMovement);
            if (slider.movement && xMovement > yMovement * 3)
            {
                //slider.touch.
                change = slider.touch.originalPos.left + change;
                //console.log(slider.touch.originalPos.left);
                setPositionProperty(change);
            }

	e.preventDefault();
        }
        var setPositionProperty = function (value)
        {
            var propValue = 'translate3d(' + value + 'px, 0, 0)';
            //console.log("translate3d("+change+"px,0,0)");
            el.css('-' + slider.cssPrefix + '-transition-duration', 0 + 's');
            el.css(slider.animProp, propValue);
        }

        var onTouchEnd = function (e)
        {
          var orig = e.originalEvent;
            timeEnd = (new Date()).getTime();
            // record end x, y positions
            slider.touch.end.x = orig.changedTouches[0].pageX;
            slider.touch.end.y = orig.changedTouches[0].pageY;
            var distance = 0;
            distance = slider.touch.end.x - slider.touch.start.x;

            if (!distance) return;
            var timeDiff = timeEnd - timeStart;
            //value = slider.touch.originalPos.left;
            var absD = Math.abs(distance);
            if (timeDiff <= 500)
                transitionDuration = (transformX / absD - 1) * (timeDiff);
            else
                transitionDuration = 500;
            if (absD >= slider.threshold) {


                distance < 0 ? NextSlide() : PrevSlide();
            }
            else
                gotoSlide(ob._index);
            if (ob._index >= ob._maxindex / 2) if (ob._tupleIndex<100)
                onnewtuples(ob);
            e.preventDefault();
        }
        var NextSlide = function ()
        {
            var index = ob._index + 1;
            if (index > ob._maxindex)
            {
                index = ob._maxindex;
                transitionDuration = 500;
            }
            var transform;
            if (index == 0)
                transform = 0;
            else
                transform = transformX * (index - 1) + transformX_corr;
            el.css('-' + slider.cssPrefix + '-transition-duration', transitionDuration + 'ms');
            var propValue = 'translate3d(-' + transform + 'px, 0, 0)';
            el.css(slider.animProp, propValue);
            ob._index = index;
            //setSliderLocation(ob._index);
        }

        var PrevSlide = function ()
        {

            var index = ob._index - 1;
            if (index < 0){
            transitionDuration=500;
                index = 0;
            }
            if (index != 0)
                transform = transformX * (index - 1) + transformX_corr;
            else
                transform = 0;
            el.css('-' + slider.cssPrefix + '-transition-duration', transitionDuration + 'ms');
            var propValue = 'translate3d(-' + transform + 'px, 0, 0)';
            el.css(slider.animProp, propValue);
            ob._index = index;
            //setSliderLocation(ob._index);
        }
        var gotoSlide = function (index)
        {

            if (index < 0 || index > ob._maxindex)
            {

                if (index < 0)
                    ob._index = 0;
                else ob._index = ob._maxindex;
                index=ob._index;

            }

            var transform;
            if (index != 0)
                transform = transformX * (index - 1) + transformX_corr;
            else
                transform = 0;
            el.css('-' + slider.cssPrefix + '-transition-duration', .5 + 's');
            var propValue = 'translate3d(-' + transform + 'px, 0, 0)';
            el.css(slider.animProp, propValue);
            ob._index = index;
            if (ob._index >= ob._maxindex / 2) if (ob._tupleIndex<100)
                onnewtuples(ob);
            //setSliderLocation(index);
        }


        var ob = {_isRequested:0, _tupleIndex:no_of_tuples, page: 1, _performFix: initOnNewTuples, _defaultInit: init, indexFix: addIndex, _parent: el,_goTo:gotoSlide,_index:0,_maxindex:0,_lmHidden:1,_mapString:mapString};
        var childElement = el.children();


        return ob;
    }
})(jQuery);
////////////////////////////////////////////////
function loadnew(page_no, eleObj) {

    var xmlhttp;
    if (window.XMLHttpRequest) { // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xmlhttp.onreadystatechange = function () {

        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

            var parentElement = eleObj._parent;
            var  loadingMore=parentElement.find("#loadingMorePic").clone();
            parentElement.find("#loadingMorePic").remove();
            eleObj._lmHidden=1;
            var index = eleObj._tupleIndex;
            var rsp = xmlhttp.responseText;
            //document.getElementById("myDiv").innerHTML=xmlhttp.responseText;

            var child = parentElement.children();
            var total_len = child.length;
            var maxlength = 10;
            var length;
            if (child.length == 0) {
                eleObj.page = -1;
                return;
            }



            rsp = JSON.parse(rsp);
          if (rsp[eleObj._mapString]['tuples']) {

                    var x, width, newdiv;


                    length = rsp[eleObj._mapString]['tuples'].length;
                    //remove first five or ten children

//                    x = child.eq(0).children().eq(0).clone();
        x=child.eq(0).html();
                    width = parseInt(child.eq(0).css("width"));



                    for (i = 0; i < length; i++) {
                        newdiv = child.eq(i + index);
                        newdiv.css("width", width + "px");
                        newdiv.html(x);

                    }


                    for (i = 0; i < length; i++) {
                        var y = child.eq(i + eleObj._tupleIndex);

                        if (eleObj._objId == 7) {
                            y.attr("id", "eoituple_" + (i + eleObj._tupleCount));

                            y.find(".eoiAcceptBtn").attr("index", (i + eleObj._tupleCount)).children("input").val(rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]);
                            y.find(".eoiDeclineBtn").attr("index", (i + eleObj._tupleCount)).children("input").val(rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]);
                        }
                        y.find(".username").html(rsp[eleObj._mapString]['tuples'][i]["username"]);
                        y.find(".tuple_image").attr("src", rsp[eleObj._mapString]['tuples'][i]["photo"]["url"]);
                        y.find(".tuple_title").html(rsp[eleObj._mapString]['tuples'][i]["tuple_title_field"]);
                        y.find(".tuple_age").html(rsp[eleObj._mapString]['tuples'][i]["age"]);
                        y.find(".tuple_height").html(rsp[eleObj._mapString]['tuples'][i]["height"]);
                        y.find(".tuple_caste").html(rsp[eleObj._mapString]['tuples'][i]["caste"]);
                        y.find(".tuple_mtongue").html(rsp[eleObj._mapString]['tuples'][i]["mtongue"]);
                        y.find(".tuple_education").html(rsp[eleObj._mapString]['tuples'][i]["education"]);
                        y.find(".tuple_income").html(rsp[eleObj._mapString]['tuples'][i]["income"]);
                        y.find(".proChecksum").val(rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]);
                        y.find("#detailedProfileRedirect").attr('href','/profile/viewprofile.php?profilechecksum='+rsp[eleObj._mapString]['tuples'][i]["profilechecksum"]+'&'+rsp[eleObj._mapString]['tracking']+"&total_rec="+rsp[eleObj._mapString]['view_all_count']+"&actual_offset="+(i+1)+"&contact_id="+rsp[eleObj._mapString]['contact_id']);

                       //
                    }



            }
            else
                length = 0;
            eleObj._tupleCount += length;

            eleObj._tupleIndex += length;


            if (!rsp[eleObj._mapString]['show_next']) {
                eleObj.page = -1;
                eleObj._parent.find("#loadingMorePic").hide();
                eleObj._lmHidden=1;
                eleObj._performFix();
                eleObj._isRequested=0;
                return;
            }

            eleObj._performFix();
            parentElement.append(loadingMore);
            loadingMore.hide();
            eleObj._lmHidden=1;
            for (i = 0; i < maxlength; i++)
                parentElement.append('<div style="margin-right:10px; display:inline-block; margin-left:0px;" ></div>');
            eleObj._isRequested=0;


        }

    };

    var proChecksumString= "profileList=";
    var prochecks=eleObj._parent.find(".proChecksum");

    proChecksumString+=prochecks.eq(0).val();
    for(i=1;i<prochecks.length;i++)
        proChecksumString+=(","+prochecks.eq(i).val());
    var str = "/api/v1/myjs/perform?infoTypeId=" + eleObj._objId + "&pageNo=" + page_no+"&"+proChecksumString;
eleObj._parent.find("#loadingMorePic").css("display","inline-block");
eleObj._lmHidden=0;
eleObj._maxindex=eleObj._tupleIndex;

    xmlhttp.open("POST", str, true);
    eleObj._isRequested=1;
    xmlhttp.send();
};
