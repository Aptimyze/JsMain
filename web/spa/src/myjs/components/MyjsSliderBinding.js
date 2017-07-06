import { commonApiCall } from "../../common/components/ApiResponseHandler";

export default class MyjsSliderBinding  {
  constructor(parent,apiObject,props,url)
  {
    this.parent = parent;
    this.apiObject = apiObject;
    this.props = props;
    this.el = parent;
    this.tuple_ratio = 80;
    this.slider = {"threshold": 80, "working": false, "movement": true, "transform": 0, "index": 0, "maxindex": 0};
    this.windowWidth = window.innerWidth;
    this.transformX = (this.tuple_ratio * this.windowWidth) / 100 + 10;
    this.elementWidth = this.transformX - 10;
    this.transformX_corr = ((this.tuple_ratio * 3 - 100) * this.windowWidth) / 200 + 10+this.el.getBoundingClientRect().left;
    this._index = 0;
    var _this=this;
    this.page = 1;

// dynamic variables
window.addEventListener("resize",function()
{
    _this.windowWidth = window.innerWidth;
    _this.transformX = (_this.tuple_ratio * _this.windowWidth) / 100 + 10;
    _this.elementWidth = _this.transformX - 10;
    _this.transformX_corr = ((_this.tuple_ratio * 3 - 100) * _this.windowWidth)/200 + 10+_this.el.getBoundingClientRect().left;
});
  }


            initTouch()
            {
                this.touch = {
                    start: {x: 0, y: 0},
                    end: {x: 0, y: 0}
                };
                var _this = this;
                this.parent.addEventListener('touchstart', _this.onTouchStart.bind(_this),{passive:false});
            // bind a "touchmove" event to tMyjsSliderBindinghe viewport
                this.parent.addEventListener('touchmove', _this.onTouchMove.bind(_this),{passive:false});
                // bind a "touchend" event to the viewport
                this.parent.addEventListener('touchend', _this.onTouchEnd.bind(_this),{passive:false});
            }
            onTouchStart(e)
            {
                    this.touch.originalPos = this.el.getBoundingClientRect();
                    this.timeStart = (new Date()).getTime();
                    var orig = e.originalEvent;
                    this.touch.start.x = e.changedTouches[0].pageX;
                    this.touch.start.y = e.changedTouches[0].pageY;
            }
            onTouchMove(e)
            {

                var orig = e;//.originalEvent;
                var xMovement = Math.abs(orig.changedTouches[0].pageX - this.touch.start.x);
                var yMovement = Math.abs(orig.changedTouches[0].pageY - this.touch.start.y);
                var change = orig.changedTouches[0].pageX - this.touch.start.x;
                if (yMovement>xMovement) {
                    return ;
                }
                if (!yMovement)
                    yMovement = 1;
                if (xMovement > yMovement * 3)
                {
                    change = this.touch.originalPos.left + change;
                    this.transitionDuration = 0;
                    this.alterCssStyle(change,this._index);
                }
                e.preventDefault();

            }
            alterCssStyle(transform,index){
              this.parent.style[this.props.cssPrefix + 'TransitionDuration'] = this.transitionDuration + 'ms';
              var propValue = 'translate3d(' + transform + 'px, 0, 0)';
              this.parent.style[this.props.animProp] =  propValue;

              this._index = index;
            }
            onTouchEnd(e)
            {
              var orig = e;//.originalEvent;
                this.timeEnd = (new Date()).getTime();
                // record end x, y positions
                this.touch.end.x = orig.changedTouches[0].pageX;
                this.touch.end.y = orig.changedTouches[0].pageY;
                var distance = 0;
                distance = this.touch.end.x - this.touch.start.x;

                if (!distance) return;
                var timeDiff = this.timeEnd - this.timeStart;
                var absD = Math.abs(distance);
                if (timeDiff <= 500 && absD>this.transformX/3 )
                    this.transitionDuration = (this.transformX / absD - 1) * (timeDiff);
                else
                    this.transitionDuration = 500;
                if (absD >= this.slider.threshold) {
                    distance < 0 ? this.NextSlide() : this.PrevSlide();
                }
                else
                    this.gotoSlide(this._index);
                   var tupleLength = this.apiObject.tuples.length;
                 if (this._index >=  tupleLength/ 2) if (tupleLength<100)
                  this.callApi(++this.page);
                e.preventDefault();
            }
            NextSlide()
            {
                var index = this._index + 1;
                if ((index+1) > this.apiObject.tuples.length)
                {
                    index = this.apiObject.tuples.length-1;
                    this.transitionDuration = 500;
                }
                var transform;
                if (index == 0)
                    var transform = 0;
                else
                    var transform = this.transformX * (index - 1) + this.transformX_corr;
                this.alterCssStyle('-'+transform,index);
            }

            PrevSlide()
            {

                var index = this._index - 1;
                if (index < 0){
                this.transitionDuration=500;
                index = 0;
                }
                if (index != 0)
                    var transform = this.transformX * (index - 1) + this.transformX_corr;
                else
                    var transform = 0;
                this.alterCssStyle('-'+transform,index);
            }
            gotoSlide(index)
            {

                if (index < 0 || index > this.apiObject.tuples.length)
                {

                    if (index < 0)
                        this._index = 0;
                    else this._index = this.apiObject.tuples.length;
                    index=this._index;

                }

                var transform;
                if (index != 0)
                    transform = this.transformX * (index - 1) + this.transformX_corr;
                else
                    transform = 0;
                this.alterCssStyle('-'+transform,index);
            }

            callApi(){

            }
        }
