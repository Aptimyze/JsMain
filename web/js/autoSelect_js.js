(function() {
  var CheckAbbr;

  CheckAbbr = (function() {

    function CheckAbbr() {
      this.defaultAbbr={'COUNTRY':{'USA':'United States',"US":"United States","UK":"United Kingdom","UAE":"United Arab Emirates"},'CITY':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"},'CASTE':{"AGRAW":"Aggarwal"},'STATE':{'UP':"Uttar Pradesh",'MP':"Madhya Pradesh"}};
    }

    CheckAbbr.prototype.checkNode = function(type,value) {
			temp=type.toUpperCase();
			value=value.toUpperCase();
			if(this.defaultAbbr.hasOwnProperty(temp))
      if(this.defaultAbbr[temp].hasOwnProperty(value))
				return this.defaultAbbr[temp][value];
				
			return value;
    };
    return CheckAbbr;

  })();
  this.CheckAbbr = CheckAbbr;

}).call(this);

(function() {
  
var CacheResults;
  CacheResults = (function() {

    function CacheResults() {
      this.cacheResults={};
    }

    CacheResults.prototype.addData = function(key,value) {
			key=escape(key);
			
			this.cacheResults[key]=value;
    };
    CacheResults.prototype.getData=function(key){
			key=escape(key);
			
			if(this.cacheResults.hasOwnProperty(key))
				return this.cacheResults[key];
			else
				return false;
		}
    return CacheResults;

  })();
  this.CacheResults = CacheResults;

}).call(this);
var cacheResulted=new CacheResults();
(function() {
  var DoParse;

  DoParse = (function() {

    function DoParse() {
      this.options_index = 0;
      this.parsed = [];
    }

    DoParse.prototype.add_node = function(child) {
      if (child.nodeName.toUpperCase() === "OPTGROUP") {
        return this.add_group(child);
      } else {
        return this.add_option(child);
      }
    };

    DoParse.prototype.add_group = function(group) {
      var group_position, option, _i, _len, _ref, _results;
      group_position = this.parsed.length;
      this.parsed.push({
        array_index: group_position,
        group: true,
        label: group.label,
        children: 0,
        disabled: group.disabled
      });
      _ref = group.childNodes;
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        option = _ref[_i];
        _results.push(this.add_option(option, group_position, group.disabled));
      }
      return _results;
    };

    DoParse.prototype.add_option = function(option, group_position, group_disabled) {
      if (option.nodeName.toUpperCase() === "OPTION") {
        if (option.text !== "" && (option.text).indexOf("Please")==-1 && (option.text).indexOf("Select")==-1) {
          if (group_position != null) {
            this.parsed[group_position].children += 1;
          }
          this.parsed.push({
            array_index: this.parsed.length,
            options_index: this.options_index,
            value: option.value,
            text: option.text,
            html: option.innerHTML,
            selected: option.selected,
            disabled: group_disabled === true ? group_disabled : option.disabled,
            group_array_index: group_position,
            classes: option.className,
            style: option.style.cssText
          });
        } else {
					
          this.parsed.push({
            array_index: this.parsed.length,
            options_index: this.options_index,
            empty: true
          });
        }
        return this.options_index += 1;
      }
    };

    return DoParse;

  })();

  DoParse.select_to_array = function(select) 
  {
		
    var child, parser, _i, _len, _ref;
    parser = new DoParse();
    _ref = select.childNodes;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      child = _ref[_i];
      parser.add_node(child);
    }
    return parser.parsed;
  };

  this.DoParse = DoParse;

}).call(this);



var _configOption={url:'',dependant:'',onlyAjax:'',matchAlgo:'',dependant:false,autofill:true,dependantOption:null,stay_open:false,customFunction:null,preCheckCall:null,type:null,search_contains:false};


(function() {
  var ExtendAutoSug, root;

  root = this;

  ExtendAutoSug = (function() {
		

		
    function ExtendAutoSug(form_field, options) {
      
       this.form_field = form_field;
       
      this.options = $.extend({},_configOption,options);
      
      if (!ExtendAutoSug.browser_is_supported()) {
        return;
      }
      this.stay_open=this.options.stay_open;
      this.is_multiple = this.form_field.multiple;
      this.checkAbbr=new CheckAbbr();
      
      this.set_default_text();
      this.set_default_values();
      this.setup();
      this.set_up_html();
      this.register_observers();
      this.finish_setup();
      
      if(this.options.dependantOption && this.options.defaultValue && !this.options.url)
			{
				this.value_change();
			}
			
      var url=options.url+"&d="+options.defaultValue;
      var ele=this;
      //alertalert(configOption.autofill+" "+options.autofill+" "+options.dependant);
      
      if(this.options.autofill && !this.options.dependant)
      {
				$.get(url, {dataType: "json"},function (data){
			
				$(form_field).html(ele.getOptions(data));
				$(form_field).trigger("liszt:updated");
				
				if(ele.options.dependantOption)
				{
					ele.value_change();
				}
				});
      }
      
    }
		ExtendAutoSug.prototype.getOptions=function(jsons) {
			//return jsons;
			var str="";
			var prev=0;
			$.each(jsons, function(index, itemData) {
				var selected="";
				if(itemData[2])
						selected="selected";
				if(itemData[3] && prev==1)
							str+="</optgroup>";
				if(itemData[3])
				{
								prev=1;
								str+="<optgroup label='"+itemData[1]+"'>";
				}
				else
							str+="<option value='"+itemData[0]+"' "+selected+">"+itemData[1]+"</option>";
			});
			if(prev==1)
				str+="</optgroup>";
			return str;
		}
    ExtendAutoSug.prototype.set_default_values = function() {
      var _this = this;
      this.click_test_action = function(evt) {
        return _this.test_active_click(evt);
      };
      this.activate_action = function(evt) {
        return _this.activate_field(evt);
      };
      this.active_field = false;
      this.mouse_on_container = false;
      this.results_showing = false;
      this.result_highlighted = null;
      this.result_single_selected = null;
      this.allow_single_deselect = (this.options.allow_single_deselect != null) && (this.form_field.options[0] != null) && this.form_field.options[0].text === "" ? this.options.allow_single_deselect : false;
      this.disable_search_threshold = this.options.disable_search_threshold || 0;
      this.disable_search = this.options.disable_search || false;
      this.enable_split_word_search = this.options.enable_split_word_search != null ? this.options.enable_split_word_search : true;
      this.search_contains = this.options.search_contains || false;
      this.single_backstroke_delete = this.options.single_backstroke_delete || false;
      this.max_selected_options = this.options.max_selected_options || Infinity;
      return this.inherit_select_classes = this.options.inherit_select_classes || false;
    };

    ExtendAutoSug.prototype.set_default_text = function() {
      if (this.form_field.getAttribute("data-placeholder")) {
        this.default_text = this.form_field.getAttribute("data-placeholder");
      } else if (this.is_multiple) {
        this.default_text = this.options.placeholder_text_multiple || this.options.placeholder_text || ExtendAutoSug.default_multiple_text;
      } else {
        this.default_text = this.options.placeholder_text_single || this.options.placeholder_text || ExtendAutoSug.default_single_text;
      }
      return this.results_none_found = this.form_field.getAttribute("data-no_results_text") || this.options.no_results_text || ExtendAutoSug.default_no_result_text;
    };

    ExtendAutoSug.prototype.mouse_enter = function() {
      return this.mouse_on_container = true;
    };

    ExtendAutoSug.prototype.mouse_leave = function() {
      return this.mouse_on_container = false;
    };

    ExtendAutoSug.prototype.input_focus = function(evt) {
      var _this = this;
      if (this.is_multiple) {
        if (!this.active_field) {
          return setTimeout((function() {
            return _this.container_mousedown();
          }), 50);
        }
      } else {
        if (!this.active_field) {
          return this.activate_field();
        }
      }
    };

    ExtendAutoSug.prototype.input_blur = function(evt) {
      var _this = this;
      if (!this.mouse_on_container) {
        this.active_field = false;
        return setTimeout((function() {
          return _this.blur_test();
        }), 100);
      }
    };

    ExtendAutoSug.prototype.result_add_option = function(option) {
      var classes, style;
      if (!option.disabled) {
        option.dom_id = this.container_id + "_o_" + option.array_index;
        classes = option.selected && this.is_multiple ? [] : ["active-result"];
        if (option.selected) {
          classes.push("result-selected");
        }
        if (option.group_array_index != null) {
          classes.push("group-option");
        }
        if (option.classes !== "") {
          classes.push(option.classes);
        }
        style = option.style.cssText !== "" ? " style=\"" + option.style + "\"" : "";
        return '<li id="' + option.dom_id + '" class="' + classes.join(' ') + '"' + style + '>' + option.html + '</li>';
      } else {
        return "";
      }
    };

    ExtendAutoSug.prototype.results_update_field = function() {
      this.set_default_text();
      if (!this.is_multiple) {
        this.results_reset_cleanup();
      }
      this.result_clear_highlight();
      this.result_single_selected = null;
      return this.results_build();
    };

    ExtendAutoSug.prototype.results_toggle = function() {
      if (this.results_showing) {
        return this.results_hide();
      } else {
        return this.results_show();
      }
    };

		ExtendAutoSug.prototype.UpdateList= function(evt){
			//val=this.
			this.alreadyCalled=false;
			var val=this.search_field.val();
			var url=this.options.url+"&q="+val;
			var ele=this;
			$.ajax({
           type: "get",
           url: url,
						async:false,
                success: function(msg){
									var val=ele.search_field.val();
									$("#"+$(ele.form_field).attr("id")).html(ele.getOptions(msg));
									$("#"+$(ele.form_field).attr("id")).trigger("liszt:updated");
									ele.search_field.val(val);
									ele.results_search();
									ele.search_field_scale();
                      }//success
           });
			
			
		};
    ExtendAutoSug.prototype.results_search = function(evt) {
      if (this.results_showing) {
        return this.winnow_results();
      } else {
        return this.results_show();
      }
    };

    ExtendAutoSug.prototype.choices_count = function() {
      var option, _i, _len, _ref;
      if (this.selected_option_count != null) {
        return this.selected_option_count;
      }
      this.selected_option_count = 0;
      _ref = this.form_field.options;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        option = _ref[_i];
        if (option.selected) {
          this.selected_option_count += 1;
        }
      }
      return this.selected_option_count;
    };

    ExtendAutoSug.prototype.choices_click = function(evt) {
      evt.preventDefault();
      if (!this.results_showing) {
        return this.results_show();
      }
    };

    ExtendAutoSug.prototype.keyup_checker = function(evt) {
			
			
      var stroke, _ref;
      stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
      this.search_field_scale();
      switch (stroke) {
        case 8:
          if (this.is_multiple && this.backstroke_length < 1 && this.choices_count() > 0) {
            return this.keydown_backstroke();
          } else if (!this.pending_backstroke) {
            this.result_clear_highlight();
            return this.results_search();
          }
          break;
        case 13:
          evt.preventDefault();
          if (this.results_showing) {
            return this.result_select(evt);
          }
          break;
        case 27:
          if (this.results_showing) {
            this.results_hide();
          }
          return true;
        case 9:
        case 38:
        case 40:
        case 16:
        case 91:
        case 17:
          break;
        default:
        {
						//var k=$(this.form_field);
						if(this.options.onlyAlax && !this.alreadyCalled)
						{
							this.alreadyCalled=true;
							var id=$(this.search_field).attr("id");
							var formid=$(this.form_field).attr("id");
							var ele=this;
							
							setTimeout(function(){ele.UpdateList();},200);
						}
									
						
					  return this.results_search();
				}	  
      }
    };

    ExtendAutoSug.prototype.generate_field_id = function() {
      var new_id;
      new_id = this.generate_random_id();
      this.form_field.id = new_id;
      return new_id;
    };

    ExtendAutoSug.prototype.generate_random_char = function() {
      var chars, newchar, rand;
      chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
      rand = Math.floor(Math.random() * chars.length);
      return newchar = chars.substring(rand, rand + 1);
    };

    ExtendAutoSug.prototype.container_width = function() {
      if (this.options.width != null) {
        return this.options.width;
      } else {
        return "" + this.form_field.offsetWidth + "px";
      }
    };

    ExtendAutoSug.browser_is_supported = function() {
      var _ref;
      if (window.navigator.appName === "Microsoft Internet Explorer") {
        return (null !== (_ref = document.documentMode) && _ref >= 8);
      }
      return true;
    };

    ExtendAutoSug.default_multiple_text = "Select Some Options";

    ExtendAutoSug.default_single_text = "Select an Option";

    ExtendAutoSug.default_no_result_text = "No results match";

    return ExtendAutoSug;

  })();

  root.ExtendAutoSug = ExtendAutoSug;

}).call(this);



(function() {
  var $, AutoSug, root,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  root = this;

  $ = jQuery;

  $.fn.extend({
    AutoSug: function(options) {
      if (!ExtendAutoSug.browser_is_supported()) {
	this.each(function(input_field){
});
	var $this;
	$this=$(this);
	//this.noselect(options);
	return $this.data('AutoSugs', new noAutoSelect(this, options));
        
      }
      return this.each(function(input_field) {
        var $this;
        $this = $(this);
        if (!$this.hasClass("chzn-done")) {
          return $this.data('AutoSug', new AutoSug(this, options));
        }
      });
    }
  });

  AutoSug = (function(_super) {

    __extends(AutoSug, _super);

    function AutoSug() {
      return AutoSug.__super__.constructor.apply(this, arguments);
    }

    AutoSug.prototype.setup = function() {
      this.form_field_jq = $(this.form_field);
      this.current_selectedIndex = this.form_field.selectedIndex;
      return this.is_rtl = this.form_field_jq.hasClass("chzn-rtl");
    };

    AutoSug.prototype.finish_setup = function() {
      return this.form_field_jq.addClass("chzn-done");
    };

    AutoSug.prototype.set_up_html = function() {
      var container_classes, container_props;
      this.container_id = this.form_field.id.length ? this.form_field.id.replace(/[^\w]/g, '_') : this.generate_field_id();
      this.container_id += "_chzn";
     // this.input_id=this.container_id+"_inp";
      container_classes = ["chzn-container"];
      container_classes.push("chzn-container-" + (this.is_multiple ? "multi" : "single"));
      if (this.inherit_select_classes && this.form_field.className) {
        container_classes.push(this.form_field.className);
      }
      if (this.is_rtl) {
        container_classes.push("chzn-rtl");
      }
      container_props = {
        'id': this.container_id,
        'class': container_classes.join(' '),
        'style': "width: " + (this.container_width()) + ";",
        'title': this.form_field.title
      };
      this.container = $("<div />", container_props);
      if (this.is_multiple) {
        this.container.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="' + this.default_text + '" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop"><ul class="chzn-results"></ul></div>');
      } else {
        this.container.html('<a href="javascript:void(0)" class="chzn-single chzn-default" tabindex="-1"><span>' + this.default_text + '</span><div><b></b></div></a><div class="chzn-drop"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>');
      }
      this.form_field_jq.hide().after(this.container);
      this.dropdown = this.container.find('div.chzn-drop').first();
      this.search_field = this.container.find('input').first();
      this.search_results = this.container.find('ul.chzn-results').first();
      this.search_field_scale();
      this.search_no_results = this.container.find('li.no-results').first();
      if (this.is_multiple) {
        this.search_choices = this.container.find('ul.chzn-choices').first();
        this.search_container = this.container.find('li.search-field').first();
      } else {
        this.search_container = this.container.find('div.chzn-search').first();
        this.selected_item = this.container.find('.chzn-single').first();
      }
      this.results_build();
      this.set_tab_index();
      this.set_label_behavior();
      return this.form_field_jq.trigger("liszt:ready", {
        AutoSug: this
      });
    };

    AutoSug.prototype.register_observers = function() {
      var _this = this;
			
			$(this.form_field).change(function(evt) {
				_this.value_change(evt);
			});
		
      this.container.mousedown(function(evt) {
        _this.container_mousedown(evt);
      });
      this.container.mouseup(function(evt) {
        _this.container_mouseup(evt);
      });
      this.container.mouseenter(function(evt) {
        _this.mouse_enter(evt);
      });
      this.container.mouseleave(function(evt) {
        _this.mouse_leave(evt);
      });
      this.search_results.mouseup(function(evt) {
        _this.search_results_mouseup(evt);
      });
      this.search_results.mouseover(function(evt) {
        _this.search_results_mouseover(evt);
      });
      this.search_results.mouseout(function(evt) {
        _this.search_results_mouseout(evt);
      });
      this.search_results.bind('mousewheel DOMMouseScroll', function(evt) {
        _this.search_results_mousewheel(evt);
      });
      this.form_field_jq.bind("liszt:updated", function(evt) {
				
				if(_this.options.customFunction)
				{
					fn=_this.options.customFunction;
					fn.apply(_this,arguments);
				}

        _this.results_update_field(evt);
      });
      this.form_field_jq.bind("liszt:activate", function(evt) {
        _this.activate_field(evt);
      });
      this.form_field_jq.bind("liszt:open", function(evt) {
        _this.container_mousedown(evt);
      });
      this.search_field.blur(function(evt) {
        _this.input_blur(evt);
      });
      this.search_field.keyup(function(evt) {
        _this.keyup_checker(evt);
      });
      this.search_field.keydown(function(evt) {
        _this.keydown_checker(evt);
      });
      this.search_field.focus(function(evt) {
				
        _this.input_focus(evt);
      });
      if (this.is_multiple) {
        return this.search_choices.click(function(evt) {
          _this.choices_click(evt);
        });
      } else {
        return this.container.click(function(evt) {
          evt.preventDefault();
        });
      }
    };

    AutoSug.prototype.search_field_disabled = function() {
      this.is_disabled = this.form_field_jq[0].disabled;
      if (this.is_disabled) {
        this.container.addClass('chzn-disabled');
        this.search_field[0].disabled = true;
        if (!this.is_multiple) {
          this.selected_item.unbind("focus", this.activate_action);
        }
        return this.close_field();
      } else {
        this.container.removeClass('chzn-disabled');
        this.search_field[0].disabled = false;
        if (!this.is_multiple) {
          return this.selected_item.bind("focus", this.activate_action);
        }
      }
    };
		AutoSug.prototype.value_change=function(evt) {
			
			
			
			
			var val=$(this.form_field).val();
			var isAllowed=0;
			
			if(this.options.customFunction)
			{
				fn=this.options.customFunction;
				fn.apply(this,[val]);
			}
			if(this.options.preCheckCall && val)
				 isAllowed=eval(""+this.options.preCheckCall+"('"+val+"')");
			
			
			if(val && isAllowed && this.options.dependantOption)
			{
				var dependantOptions=this.options.dependantOption;
				var depID="#"+dependantOptions.id;
				var ele=this;
				var dval=$(depID).val();
				
				if(!$(depID).val())
					dval=null;
				if(dval==null && dependantOptions.defaultValue)
						dval=dependantOptions.defaultValue;
				var url=dependantOptions.url+"&l="+val+"&d="+dval;

				if(cacheResulted.getData(url))
				{
						ele.UpdateDepID(depID,cacheResulted.getData(url));
				}
				else
				{
					$.get(url, {dataType: "json"},function (data){
					cacheResulted.addData(url,data);
					ele.UpdateDepID(depID,data);
					});
				}
			}
			else
			{
				$(depID).siblings().find("li.search-choice").remove();
				$(depID).html("");
				$(depID).trigger("liszt:updated");
			}
		};
		
		AutoSug.prototype.UpdateDepID=function(depID,data,ele){
			$(depID).siblings().find("li.search-choice").remove();
				if(data)
					$(depID).html(this.getOptions(data));
				else
					$(depID).html("");
					
				$(depID).trigger("liszt:updated");
		};
		
    AutoSug.prototype.container_mousedown = function(evt) {
      if (!this.is_disabled) {
        if (evt && evt.type === "mousedown" && !this.results_showing) {
          evt.preventDefault();
        }
        if (!((evt != null) && ($(evt.target)).hasClass("search-choice-close"))) {
          if (!this.active_field) {
            if (this.is_multiple) {
              this.search_field.val("");
            }
            $(document).click(this.click_test_action);
            this.results_show();
          } else if (!this.is_multiple && evt && (($(evt.target)[0] === this.selected_item[0]) || $(evt.target).parents("a.chzn-single").length)) {
            evt.preventDefault();
            this.results_toggle();
          }
          return this.activate_field();
        }
      }
    };

    AutoSug.prototype.container_mouseup = function(evt) {
      if (evt.target.nodeName === "ABBR" && !this.is_disabled) {
        return this.results_reset(evt);
      }
    };

    AutoSug.prototype.search_results_mousewheel = function(evt) {
      var delta, _ref, _ref1;
      delta = -((_ref = evt.originalEvent) != null ? _ref.wheelDelta : void 0) || ((_ref1 = evt.originialEvent) != null ? _ref1.detail : void 0);
      if (delta != null) {
        evt.preventDefault();
        if (evt.type === 'DOMMouseScroll') {
          delta = delta * 40;
        }
        return this.search_results.scrollTop(delta + this.search_results.scrollTop());
      }
    };

    AutoSug.prototype.blur_test = function(evt) {
      if (!this.active_field && this.container.hasClass("chzn-container-active")) {
        return this.close_field();
      }
    };

    AutoSug.prototype.close_field = function() {
      $(document).unbind("click", this.click_test_action);
      this.active_field = false;
      this.results_hide();
      this.container.removeClass("chzn-container-active");
      
      try{
      SelectDropDownErrors(0,this.form_field);
		}
		catch(e)
		{
			//Do nothing, just type checking.
		}
      this.winnow_results_clear();
      this.clear_backstroke();
      this.show_search_field_default();
      return this.search_field_scale();
    };

    AutoSug.prototype.activate_field = function() {
			
      this.container.addClass("chzn-container-active");
			try{
				SelectDropDownErrors(1,this.form_field);
			}
			catch(e)
			{
			//Do nothing, just type checking.
			}
      this.active_field = true;
      this.search_field.val(this.search_field.val());
      return this.search_field.focus();
    };

    AutoSug.prototype.test_active_click = function(evt) {
      if ($(evt.target).parents('#' + this.container_id).length) {
        return this.active_field = true;
      } else {
        return this.close_field();
      }
    };

    AutoSug.prototype.results_build = function() {
			
      var content, data, _i, _len, _ref;
      this.parsing = true;
      this.selected_option_count = null;
      this.results_data = root.DoParse.select_to_array(this.form_field);
      if (this.is_multiple && this.choices_count() > 0) {
        this.search_choices.find("li.search-choice").remove();
      } else if (!this.is_multiple) {
        this.selected_item.addClass("chzn-default").find("span").text(this.default_text);
        if (this.disable_search || this.form_field.options.length <= this.disable_search_threshold) {
          this.container.addClass("chzn-container-single-nosearch");
        } else {
          this.container.removeClass("chzn-container-single-nosearch");
        }
      }
      content = '';
      _ref = this.results_data;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        data = _ref[_i];
        if (data.group) {
          content += this.result_add_group(data);
        } else if (!data.empty) {
          content += this.result_add_option(data);
          if (data.selected && this.is_multiple) {
            this.choice_build(data);
          } else if (data.selected && !this.is_multiple) {
            this.selected_item.removeClass("chzn-default").find("span").text(data.text);
            if (this.allow_single_deselect) {
              this.single_deselect_control_build();
            }
          }
        }
      }
      this.search_field_disabled();
      this.show_search_field_default();
      this.search_field_scale();
      this.search_results.html(content);
      return this.parsing = false;
    };

    AutoSug.prototype.result_add_group = function(group) {
      if (!group.disabled) {
        group.dom_id = this.container_id + "_g_" + group.array_index;
        return '<li id="' + group.dom_id + '" class="group-result">' + $("<div />").text(group.label).html() + '</li>';
      } else {
        return "";
      }
    };

    AutoSug.prototype.result_do_highlight = function(el) {
      var high_bottom, high_top, maxHeight, visible_bottom, visible_top;
      if (el.length) {
        this.result_clear_highlight();
        this.result_highlight = el;
        this.result_highlight.addClass("highlighted");
        maxHeight = parseInt(this.search_results.css("maxHeight"), 10);
        visible_top = this.search_results.scrollTop();
        visible_bottom = maxHeight + visible_top;
        high_top = this.result_highlight.position().top + this.search_results.scrollTop();
        high_bottom = high_top + this.result_highlight.outerHeight();
        if (high_bottom >= visible_bottom) {
          return this.search_results.scrollTop((high_bottom - maxHeight) > 0 ? high_bottom - maxHeight : 0);
        } else if (high_top < visible_top) {
          return this.search_results.scrollTop(high_top);
        }
      }
    };

    AutoSug.prototype.result_clear_highlight = function() {
      if (this.result_highlight) {
        this.result_highlight.removeClass("highlighted");
      }
      return this.result_highlight = null;
    };

    AutoSug.prototype.results_show = function() {
			
      if (this.result_single_selected != null) {
        this.result_do_highlight(this.result_single_selected);
      } else if (this.is_multiple && this.max_selected_options <= this.choices_count()) {
        this.form_field_jq.trigger("liszt:maxselected", {
          AutoSug: this
        });
        return false;
      }
      this.container.addClass("chzn-with-drop");
      this.form_field_jq.trigger("liszt:showing_dropdown", {
        AutoSug: this
      });
      this.results_showing = true;
      this.search_field.focus();
      this.search_field.val(this.search_field.val());
      return this.winnow_results();
    };

    AutoSug.prototype.results_hide = function() {
      this.result_clear_highlight();
      this.container.removeClass("chzn-with-drop");
      this.form_field_jq.trigger("liszt:hiding_dropdown", {
        AutoSug: this
      });
      return this.results_showing = false;
    };

    AutoSug.prototype.set_tab_index = function(el) {
      var ti;
      if (this.form_field_jq.attr("tabindex")) {
        ti = this.form_field_jq.attr("tabindex");
        this.form_field_jq.attr("tabindex", -1);
        return this.search_field.attr("tabindex", ti);
      }
    };

    AutoSug.prototype.set_label_behavior = function() {
      var _this = this;
      this.form_field_label = this.form_field_jq.parents("label");
      if (!this.form_field_label.length && this.form_field.id.length) {
        this.form_field_label = $("label[for=" + this.form_field.id + "]");
      }
      if (this.form_field_label.length > 0) {
        return this.form_field_label.click(function(evt) {
          if (_this.is_multiple) {
            return _this.container_mousedown(evt);
          } else {
            return _this.activate_field();
          }
        });
      }
    };

    AutoSug.prototype.show_search_field_default = function() {
      if (this.is_multiple && this.choices_count() < 1 && !this.active_field) {
        this.search_field.val(this.default_text);
        return this.search_field.addClass("default");
      } else {
        this.search_field.val("");
        return this.search_field.removeClass("default");
      }
    };

    AutoSug.prototype.search_results_mouseup = function(evt) {
      var target;
var ele=this;	
setTimeout(function(){
      target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
      if (target.length) {
        ele.result_highlight = target;
        ele.result_select(evt);
        return ele.search_field.focus();
      }
	},150);
    };

    AutoSug.prototype.search_results_mouseover = function(evt) {
      var target;
      target = $(evt.target).hasClass("active-result") ? $(evt.target) : $(evt.target).parents(".active-result").first();
      if (target) {
        return this.result_do_highlight(target);
      }
    };

    AutoSug.prototype.search_results_mouseout = function(evt) {
      if ($(evt.target).hasClass("active-result" || $(evt.target).parents('.active-result').first())) {
        return this.result_clear_highlight();
      }
    };

    AutoSug.prototype.choice_build = function(item) {
      var choice, close_link,
        _this = this;
      choice = $('<li />', {
        "class": "search-choice"
      }).html("<span>" + item.html + "</span>");
      if (item.disabled) {
        choice.addClass('search-choice-disabled');
      } else {
        close_link = $('<a />', {
          href: '#',
          "class": 'search-choice-close',
          rel: item.array_index
        });
        close_link.click(function(evt) {
          return _this.choice_destroy_link_click(evt);
        });
        choice.append(close_link);
      }
      return this.search_container.before(choice);
    };

    AutoSug.prototype.choice_destroy_link_click = function(evt) {
			
      evt.preventDefault();
      evt.stopPropagation();
      if (!this.is_disabled) {
        return this.choice_destroy($(evt.target));
      }
    };

    AutoSug.prototype.choice_destroy = function(link) {
      if (this.result_deselect(link.attr("rel"))) {
        this.show_search_field_default();
        if (this.is_multiple && this.choices_count() > 0 && this.search_field.val().length < 1) {
          this.results_hide();
        }
        link.parents('li').first().remove();
        
        if(this.options.dependantOption)
					this.value_change();
					
        return this.search_field_scale();
      }
    };

    AutoSug.prototype.results_reset = function() {
      this.form_field.options[0].selected = true;
      this.selected_option_count = null;
      this.selected_item.find("span").text(this.default_text);
      if (!this.is_multiple) {
        this.selected_item.addClass("chzn-default");
      }
      this.show_search_field_default();
      this.results_reset_cleanup();
      this.form_field_jq.trigger("change");
      if (this.active_field) {
        return this.results_hide();
      }
    };

    AutoSug.prototype.results_reset_cleanup = function() {
      this.current_selectedIndex = this.form_field.selectedIndex;
      return this.selected_item.find("abbr").remove();
    };

    AutoSug.prototype.result_select = function(evt) {
      var high, high_id, item, position;
      if (this.result_highlight) {
        high = this.result_highlight;
        high_id = high.attr("id");
        this.result_clear_highlight();
        if (this.is_multiple && this.max_selected_options <= this.choices_count()) {
          this.form_field_jq.trigger("liszt:maxselected", {
            AutoSug: this
          });
          return false;
        }
        if (this.is_multiple) {
          this.result_deactivate(high);
        } else {
          this.search_results.find(".result-selected").removeClass("result-selected");
          this.result_single_selected = high;
          this.selected_item.removeClass("chzn-default");
        }
        high.addClass("result-selected");
        position = high_id.substr(high_id.lastIndexOf("_") + 1);
        item = this.results_data[position];
        item.selected = true;
        this.form_field.options[item.options_index].selected = true;
        this.selected_option_count = null;
        if (this.is_multiple) {
          this.choice_build(item);
        } else {
          this.selected_item.find("span").first().text(item.text);
          if (this.allow_single_deselect) {
            this.single_deselect_control_build();
          }
        }
        if (!((evt.metaKey || evt.ctrlKey) && this.is_multiple)) {
					//alert(evt.metaKey+" "+evt.ctrlKey);
          if(!this.stay_open)
							this.results_hide();
					else
					{
							this.results_showing = false;
							
					}
        }
       // if(!this.stay_open)
					this.search_field.val("");
					
        if (this.is_multiple || this.form_field.selectedIndex !== this.current_selectedIndex) {
          this.form_field_jq.trigger("change", {
            'selected': this.form_field.options[item.options_index].value
          });
        }
        this.current_selectedIndex = this.form_field.selectedIndex;
        
        return true;//this.search_field_scale();
      }
    };

    AutoSug.prototype.result_activate = function(el) {
      return el.addClass("active-result");
    };

    AutoSug.prototype.result_deactivate = function(el) {
      return el.removeClass("active-result");
    };

    AutoSug.prototype.result_deselect = function(pos) {
      var result, result_data;
      result_data = this.results_data[pos];
      if (!this.form_field.options[result_data.options_index].disabled) {
        result_data.selected = false;
        this.form_field.options[result_data.options_index].selected = false;
        this.selected_option_count = null;
        result = $("#" + this.container_id + "_o_" + pos);
        result.removeClass("result-selected").addClass("active-result").show();
        this.result_clear_highlight();
        this.winnow_results();
        this.form_field_jq.trigger("change", {
          deselected: this.form_field.options[result_data.options_index].value
        });
        this.search_field_scale();
        return true;
      } else {
        return false;
      }
    };

    AutoSug.prototype.single_deselect_control_build = function() {
      if (this.allow_single_deselect && this.selected_item.find("abbr").length < 1) {
        return this.selected_item.find("span").first().after("<abbr class=\"search-choice-close\"></abbr>");
      }
    };

    AutoSug.prototype.winnow_results = function() {
			
      var found, option, part, parts, regex, regexAnchor, result, result_id, results, searchText, startpos, text, zregex, _i, _j, _len, _len1, _ref;
      this.no_results_clear();
      results = 0;
      searchText = this.search_field.val() === this.default_text ? "" : $('<div/>').text($.trim(this.search_field.val())).html();
      searchText=this.checkAbbr.checkNode(this.options.type,searchText);
      searchText=CleanData(searchText);
      regexAnchor = this.search_contains ? "" : "^";
      regex = new RegExp(regexAnchor + searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
      zregex = new RegExp(searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
      _ref = this.results_data;
      alreadyFound={};
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        option = _ref[_i];
        
        if (!option.disabled && !option.empty) {
          if (option.group) {
            $('#' + option.dom_id).css('display', 'none');
          } else if (!(this.is_multiple && option.selected)) {
            found = false;
            result_id = option.dom_id;
            result = $("#" + result_id);
            var needleStr=CleanData(option.html);
            if(alreadyFound.hasOwnProperty(option.value))
            {
								
	    }
            else if (regex.test(needleStr)) {
              found = true;
              results += 1;
            } else if (this.enable_split_word_search && (needleStr.indexOf(" ") >= 0 || needleStr.indexOf("[") === 0)) {
              parts = needleStr.replace(/\[|\]/g, "").split(" ");
              if (parts.length) {
                for (_j = 0, _len1 = parts.length; _j < _len1; _j++) {
                  part = parts[_j];
                  if (regex.test(part)) {
                    found = true;
                    results += 1;
                  }
                }
              }
            }
            
            if (found) {
							
              if (searchText.length) {
								
		alreadyFound[option.value]=1;
								
                startpos = needleStr.search(zregex);
                text = option.html.substr(0, startpos + searchText.length) + '</b>' + option.html.substr(startpos + searchText.length);
                text = text.substr(0, startpos) + '<b>' + text.substr(startpos);
              } else {
                text = option.html;
              }
              result.html(text);
              this.result_activate(result);
              if (option.group_array_index != null) {
                $("#" + this.results_data[option.group_array_index].dom_id).css('display', 'list-item');
              }
            } else {
              if (this.result_highlight && result_id === this.result_highlight.attr('id')) {
                this.result_clear_highlight();
              }
              this.result_deactivate(result);
            }
          }
        }
      }
      
      
      
      
      ////////////////////////////////////////////
      ///////////////////////////////////////////
      /////////////////////////////////////////
      
      if(results<1 && this.options.matchAlgo)
      {
				var realText=searchText;
				for (_i = 0, _len = _ref.length; _i < _len; _i++) {
					searchText=realText;
					option = _ref[_i];
					if (!option.disabled && !option.empty) {
						if (option.group) {
							$('#' + option.dom_id).css('display', 'none');
						} else if (!(this.is_multiple && option.selected)) {
							found = false;
							result_id = option.dom_id;
							result = $("#" + result_id);
							var needleStr=CleanData(option.html);
							
							k=SearchInBoth(needleStr.split(" "),realText.split(" "));
							if(k<=2 && k>0)
							{
									found=true;
									searchText=option.html;
									zregex = new RegExp(searchText.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&"), 'i');
									results++;
							}	
							if (found) {
								
								if (searchText.length) {
									
									startpos = option.html.search(zregex);
									text = option.html.substr(0, startpos + searchText.length) + '</em>' + option.html.substr(startpos + searchText.length);
									text = text.substr(0, startpos) + '<em>' + text.substr(startpos);
								} else {
									text = option.html;
								}
								result.html(text);
								this.result_activate(result);
								if (option.group_array_index != null) {
									$("#" + this.results_data[option.group_array_index].dom_id).css('display', 'list-item');
								}
							} else {
								if (this.result_highlight && result_id === this.result_highlight.attr('id')) {
									this.result_clear_highlight();
								}
								this.result_deactivate(result);
							}
						}
					}
				}
			}
      /////////////////////////////////////////////////
      //////////////////////////////////////////////
      //////////////////////////////////////////////
      
      
      if (results < 1 && searchText.length) {
        return this.no_results(searchText);
      } else {
        return this.winnow_results_set_highlight();
      }
    };

    AutoSug.prototype.winnow_results_clear = function() {
      var li, lis, _i, _len, _results;
      this.search_field.val("");
      lis = this.search_results.find("li");
      _results = [];
      for (_i = 0, _len = lis.length; _i < _len; _i++) {
        li = lis[_i];
        li = $(li);
        if (li.hasClass("group-result")) {
          _results.push(li.css('display', 'auto'));
        } else if (!this.is_multiple || !li.hasClass("result-selected")) {
          _results.push(this.result_activate(li));
        } else {
          _results.push(void 0);
        }
      }
      return _results;
    };

    AutoSug.prototype.winnow_results_set_highlight = function() {
      var do_high, selected_results;
      if (!this.result_highlight) {
        selected_results = !this.is_multiple ? this.search_results.find(".result-selected.active-result") : [];
        do_high = selected_results.length ? selected_results.first() : this.search_results.find(".active-result").first();
        if (do_high != null) {
          return this.result_do_highlight(do_high);
        }
      }
    };

    AutoSug.prototype.no_results = function(terms) {
      var no_results_html;
      no_results_html = $('<li class="no-results">' + this.results_none_found + ' "<span></span>"</li>');
      no_results_html.find("span").first().html(terms);
      return this.search_results.append(no_results_html);
    };

    AutoSug.prototype.no_results_clear = function() {
      return this.search_results.find(".no-results").remove();
    };

    AutoSug.prototype.keydown_arrow = function() {
      var first_active, next_sib;
      if (!this.result_highlight) {
        first_active = this.search_results.find("li.active-result").first();
        if (first_active) {
          this.result_do_highlight($(first_active));
        }
      } else if (this.results_showing) {
        next_sib = this.result_highlight.nextAll("li.active-result").first();
        if (next_sib) {
          this.result_do_highlight(next_sib);
        }
      }
      if (!this.results_showing) {
        return this.results_show();
      }
    };

    AutoSug.prototype.keyup_arrow = function() {
      var prev_sibs;
      if (!this.results_showing && !this.is_multiple) {
        return this.results_show();
      } else if (this.result_highlight) {
        prev_sibs = this.result_highlight.prevAll("li.active-result");
        if (prev_sibs.length) {
          return this.result_do_highlight(prev_sibs.first());
        } else {
          if (this.choices_count() > 0) {
            this.results_hide();
          }
          return this.result_clear_highlight();
        }
      }
    };

    AutoSug.prototype.keydown_backstroke = function() {
      var next_available_destroy;
      if (this.pending_backstroke) {
        this.choice_destroy(this.pending_backstroke.find("a").first());
        return this.clear_backstroke();
      } else {
        next_available_destroy = this.search_container.siblings("li.search-choice").last();
        if (next_available_destroy.length && !next_available_destroy.hasClass("search-choice-disabled")) {
          this.pending_backstroke = next_available_destroy;
          if (this.single_backstroke_delete) {
            return this.keydown_backstroke();
          } else {
            return this.pending_backstroke.addClass("search-choice-focus");
          }
        }
      }
    };

    AutoSug.prototype.clear_backstroke = function() {
      if (this.pending_backstroke) {
        this.pending_backstroke.removeClass("search-choice-focus");
      }
      return this.pending_backstroke = null;
    };

    AutoSug.prototype.keydown_checker = function(evt) {
      var stroke, _ref;
      stroke = (_ref = evt.which) != null ? _ref : evt.keyCode;
      this.search_field_scale();
      if (stroke !== 8 && this.pending_backstroke) {
        this.clear_backstroke();
      }
      switch (stroke) {
        case 8:
          this.backstroke_length = this.search_field.val().length;
          break;
        case 9:
          if (this.results_showing && !this.is_multiple) {
            this.result_select(evt);
          }
          this.mouse_on_container = false;
          break;
        case 13:
          evt.preventDefault();
          break;
        case 38:
          evt.preventDefault();
          this.keyup_arrow();
          break;
        case 40:
          this.keydown_arrow();
          break;
      }
    };

    AutoSug.prototype.search_field_scale = function() {
      var div, h, style, style_block, styles, w, _i, _len;
      if (this.is_multiple) {
        h = 0;
        w = 0;
        style_block = "position:absolute; left: -1000px; top: -1000px; display:none;";
        styles = ['font-size', 'font-style', 'font-weight', 'font-family', 'line-height', 'text-transform', 'letter-spacing'];
        for (_i = 0, _len = styles.length; _i < _len; _i++) {
          style = styles[_i];
          style_block += style + ":" + this.search_field.css(style) + ";";
        }
        div = $('<div />', {
          'style': style_block
        });
        div.text(this.search_field.val());
        $('body').append(div);
        w = div.width() + 25;
        div.remove();
        if (!this.f_width) {
          this.f_width = this.container.outerWidth();
        }
        if (w > this.f_width - 10) {
          w = this.f_width - 10;
        }
        return this.search_field.css({
          'width': w + 'px'
        });
      }
    };

    AutoSug.prototype.generate_random_id = function() {
      var string;
      string = "sel" + this.generate_random_char() + this.generate_random_char() + this.generate_random_char();
      while ($("#" + string).length > 0) {
        string += this.generate_random_char();
      }
      return string;
    };

    return AutoSug;

  })(ExtendAutoSug);

  root.AutoSug = AutoSug;

}).call(this);

function SearchInBoth(needleArr,searchArr)
{
	pass=0;
	var arr=[];
	var len=0;
	for(i=0;i<searchArr.length;i++)
	{
		
		for(j=0;j<needleArr.length;j++)
		{
			needleArr[j]=CleanData(needleArr[j]);
			if(searchArr[i]!="" && needleArr[j].length>2 && searchArr[i].length>2)
			{
				
				pass=levenshtein_distance_a (searchArr[i],needleArr[j]);
				
				if(pass<=2)
					break;
				else
					pass=0;
			}
			
		}
		
	}
	return pass;
}
   function levenshtein_distance_a (a, b) {
		
			a=a.toLowerCase();
			b=b.toLowerCase();
      if(a.length == 0) return b.length; 
      if(b.length == 0) return a.length; 
    
				
      var matrix = [];
    
      // increment along the first column of each row
      var i;
      for(i = 0; i <= b.length; i++){
        matrix[i] = [i];
      }
    
      // increment each column in the first row
      var j;
      for(j = 0; j <= a.length; j++){
        matrix[0][j] = j;
      }
    
      // Fill in the rest of the matrix
      for(i = 1; i <= b.length; i++){
        for(j = 1; j <= a.length; j++){
          if(b.charAt(i-1) == a.charAt(j-1)){
            matrix[i][j] = matrix[i-1][j-1];
          } else {
            matrix[i][j] = Math.min(matrix[i-1][j-1] + 1, // substitution
                                    Math.min(matrix[i][j-1] + 1, // insertion
                                             matrix[i-1][j] + 1)); // deletion
          }
        }
      }
     
    
      return matrix[b.length][a.length];
    }		
 /*
  * Remove any special character from string.
  */
 function CleanData(str)
 {
	 
	 //str=str.replace("-"," ");
	 str=str.replace(/[\/|\-|:|(|)|\.]/g," ");
	 
	 
	 str=str.toLowerCase();
	 
	 return str;
 }
