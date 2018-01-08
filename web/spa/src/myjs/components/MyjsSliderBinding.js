
export default class MyjsSliderBinding  {
  constructor(parent,tupleObject,funObj,notMyjs,indexElevate,nextPageHit,pagesrc)
  {
    this.parent = parent;
    this.tupleObject = tupleObject.filter((tuple)=>{return (tuple.dontShow ? false : true)});
    this.styleFunction = funObj.styleFunction;
    this.el = parent;
    this.parent = this.el.parentNode;
    this.threshold = !notMyjs ? 40 :100;
    this.windowWidth = window.innerWidth;
    this.nextPageHit = nextPageHit;
    this.tuple_ratio = !notMyjs ? 80 :100;
    this.transformX = (this.tuple_ratio * this.windowWidth) / 100 + (!notMyjs?10:0);
    this.elementWidth = this.transformX - 10;
    this.transformX_corr = !notMyjs ? (((this.tuple_ratio * 3 - 100) * this.windowWidth) / 200 + 10+this.el.getBoundingClientRect().left) :this.windowWidth;
    this._index = 0;
    var _this=this;
    this.page = 1;
    this.indexElevate = indexElevate ? indexElevate : 0 ;
    this.pagesrc = pagesrc;
    this.nxtSlideFun = typeof funObj.nxtSlideFun == 'function' ? funObj.nxtSlideFun : ()=>{} ;
    this.prvSlideFun = typeof funObj.prvSlideFun == 'function' ? funObj.prvSlideFun : ()=>{} ;
  }


            initTouch()
            {
                this.touch = {
                    start: {x: 0, y: 0},
                    end: {x: 0, y: 0}
                };
                var _this = this;
                this.binderfun1 = _this.onTouchStart.bind(_this)
                this.binderfun2 = _this.onTouchMove.bind(_this)
                this.binderfun3 = _this.onTouchEnd.bind(_this)
                this.parent.addEventListener('touchstart',_this.binderfun1 ,{passive:false});
            // bind a "touchmove" event to tMyjsSliderBindinghe viewport
                this.parent.addEventListener('touchmove', _this.binderfun2,{passive:false});
                // bind a "touchend" event to the viewport
                this.parent.addEventListener('touchend', _this.binderfun3,{passive:false});
            }
            onTouchStart(e)
            {
                    this.touch.originalPos = {left: this.el.getBoundingClientRect().left - this.parent.getBoundingClientRect().left - this.el.offsetLeft};
                    this.timeStart = (new Date()).getTime();
                    var orig = e.originalEvent;
                    this.touch.start.x = e.changedTouches[0].pageX;
                    this.touch.start.y = e.changedTouches[0].pageY;
            }

            resetSlider(newListing){
              this.tupleObject = newListing.filter((tuple)=>{return (tuple.dontShow ? false : true)});
              if(this._index==this.tupleObject.length)
                this.PrevSlide();
              return this;
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
              this.styleFunction(this.transitionDuration,transform);
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
                if (absD >= this.threshold) {
                    distance < 0 ? this.NextSlide() : this.PrevSlide();
                }
                else
                    this.gotoSlide(this._index);
                e.preventDefault();
            }
            NextSlide()
            {
                var index = this._index + 1;


                if ((index+1) > (this.tupleObject.length+this.indexElevate))
                {
                    index = (this.tupleObject.length+this.indexElevate)-1;
                    this.transitionDuration = 500;
                }
                if(index==(this.tupleObject.length-3))
                  if(typeof this.nextPageHit=='function')
                    this.nextPageHit();
                var transform;
                if (index == 0)
                    var transform = 0;
                else
                    var transform = this.transformX * (index - 1) + this.transformX_corr;
                this.alterCssStyle('-'+transform,index);
                this.nxtSlideFun();
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
                this.prvSlideFun();
            }
            gotoSlide(index)
            {

                if (index < 0 || index > (this.tupleObject.length +this.indexElevate))
                {

                    if (index < 0)
                        this._index = 0;
                    else this._index = this.tupleObject.length + this.indexElevate;
                    index=this._index;

                }

                var transform;
                if (index != 0)
                    transform = this.transformX * (index - 1) + this.transformX_corr;
                else
                    transform = 0;
                this.alterCssStyle('-'+transform,index);
            }

            setIndexElevate(newElevate){
              this.indexElevate = newElevate;
            }
        }
