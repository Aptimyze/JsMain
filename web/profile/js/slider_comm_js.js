Fx.MorphList = new Class({   
	
	Implements: [Events, Options],
	
	initialize: function(element, options){
		var self = this;
		this.setOptions(options);
		this.element = _(element);		
		this.items = this.element.getChildren().addEvents({
			mouseover: function(ev){ self.onMouseover(ev, this); },
			mouseout: function(ev){ self.onMouseout(ev, this); },
			click: function(ev){ self.onClick(ev, this); }
		});       
	},

        initializeImage: function(picture){
	var self = this;
	this.picture = _(picture);
	this.picture.getChildren().addEvents({
                        mouseover: function(ev){ self.onMouseover(ev, this); },
                        mouseout: function(ev){ self.onMouseout(ev, this); }
                });
	},

	onClick: function(ev, item){
		this.fireEvent('click', [ev, item]);
	},

	onMouseout: function(ev, item){
		this.fireEvent('mouseout', [ev, item]);
	},

	onMouseover: function(ev, item){
		this.fireEvent('mouseover', [ev, item]);
	},

	setCurrent: function(el, effect){  
		if (this.current) this.current.removeClass('current');
		if (el) this.current = el.addClass('current');    
		return this;
	}
});

var Slider = new Class({
  
  Extends: Fx.MorphList,
  
  options: {
    auto: true,
    autointerval: 20000,
    tween: { duration: 1000}
  },
  
  initialize: function(menu, images, options, randomImages){
    this.parent(menu, options);
    this.initializeImage(images);
    this.images = _(images);
    this.imagesitems = this.images.getChildren().fade('hide');
    this.randomImages = randomImages;
    new Asset.images(this.images.getElements('img').map(function(el) { return el.setStyle('display', 'none').get('src'); }), { onComplete: function() {
      this.loaded = true;      
      this.progress();
    }.bind(this) });
  },
  
  auto: function(){
    _clear(this.autotimer);
    this.autotimer = this.progress.delay(this.options.autointerval, this);
  },

  onClick: function(event, item){
    this.parent(event, item);
    event.stop();
    this.show(this.items.indexOf(item));
    //_clear(this.autotimer);
    this.options.auto = false;
  },

  onMouseover: function(event, item){
    this.parent(event, item);
    //if((this.imagesitems.indexOf(this.curimage) == this.items.indexOf(item))|| (this.items.indexOf(item) == -1))
    this.options.auto = false;
  },

  onMouseout: function(event, item){
    this.parent(event, item);
    event.stop();
    this.options.auto = true;
    //this.progress();
  },
  
  show: function(index) {
    if (!this.loaded) return;
    var image = this.imagesitems[index];    
    if (image == this.curimage){
	this.auto();
	return;
    }
    image.set('tween', this.options.tween).dispose().inject(this.curimage || this.images.getFirst(), this.curimage ? 'after' : 'before').fade('hide');
    image.getElement('img').setStyle('display', 'block');
    if(typeof(this.current)=="undefined"){
	image.fade('show');
        this.auto();
        this.fireEvent('show', image);
    }else{
	if(index == 0)
		image.getElement('img').src = this.getSuccessStories(this.randomImages);
	/*var prop = 'top';
	image.fade('show').setStyle(prop, image['offsetHeight'] * (-1)).tween(prop, 0); */
	image.fade('in');
	image.get('tween').chain(function(){ 
	this.auto();
	this.fireEvent('show', image); 
	}.bind(this));
    }
    this.curimage = image;
    this.setCurrent(this.items[index])
    return this;
    
  },
  
  progress: function(){
    var curindex = this.imagesitems.indexOf(this.curimage);
    if (this.options.auto)
       this.show((this.curimage && (curindex + 1 < this.imagesitems.length)) ? curindex + 1 : 0);
    else
	this.show((this.curimage && (curindex < this.imagesitems.length)) ? curindex : 0);
  },

  getSuccessStories: function(randomImages){
	var randNo = Math.floor(Math.random() * 10);
        var url = randomImages.split(",");
        for (var i=0;i<url.length;i++)
        {
		if(i==randNo)
			return url[i];
        }
	return url[0];
  }
  
});
