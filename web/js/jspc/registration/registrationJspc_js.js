var result;
var aboutmePlaceholder = " You may consider answering these questions:\n 1. How would you describe yourself?\n 2. What kind of food/movies/books/music you like? \n 3. Do you enjoy activities like traveling, music, sports etc? \n 4. Where have you lived most of your life till now?\n 5. Where do you wish to settle down in future?";
var aboutfamilyPlaceholder = "Write about your parents and brothers or sisters. Where do they live? What are they doing?";
var sections = {
  "brother": {1: "brother(s)", 2: "married"},
  "sister": {1: "sister(s)", 2: "married"}
};
var emailCorrections = {
  "gamil.com": "gmail.com",
  "gmai.com": "gmail.com",
  "gmil.com": "gmail.com",
  "gmal.com": "gmail.com",
  "gmaill.com": "gmail.com",
  "gmail.co": "gmail.com",
  "gail.com": "gmail.com",
  "gmail.om": "gmail.com",
  "gmali.com": "gmail.com",
  "gmail.con": "gmail.com",
  "gmail.co.in": "gmail.com",
  "gmail.cm": "gmail.com",
  "gmail.in": "gmail.com",
  "gimal.com": "gmail.com",
  "gnail.com": "gmail.com",
  "gimail.com": "gmail.com",
  "g.mail.com": "gmail.com",
  "gmailil.com": "gmail.com",
  "gmail.cim": "gmail.com",
  "gemail.com": "gmail.com",
  "gmall.com": "gmail.com",
  "gmail.com.com": "gmail.com",
  "gmeil.com": "gmail.com",
  "gmsil.com": "gmail.com",
  "gmail.comn": "gmail.com",
  "gmail.cpm": "gmail.com",
  "gimel.com": "gmail.com",
  "gmailo.com": "gmail.com",
  "gmile.com": "gmail.com",
  "fmail.com": "gmail.com",
  "yhoo.com": "yahoo.com",
  "yaho.com": "yahoo.com",
  "yahool.com": "yahoo.com",
  "yhaoo.com": "yahoo.com",
  "yahoo.co": "yahoo.com",
  "yaoo.com": "yahoo.com",
  "yhaoo.co.in": "yahoo.com",
  "yahoo.com.in": "yahoo.co.in",
  "yamil.com": "ymail.com",
  "yhoo.in": "yahoo.in",
  "yahho.com": "yahoo.com",
  "yahoo.com.com": "yahoo.com",
  "redifmail.com": "rediffmail.com",
  "reddifmail.com": "rediffmail.com",
  "reddffmail.com": "rediffmail.com",
  "rediffmaill.com": "rediffmail.com",
  "rediffmai.com": "rediffmail.com",
  "rediffmal.com": "rediffmail.com",
  "reddiffmail.com": "rediffmail.com",
  "redifffmail.com": "rediffmail.com",
  "rediffimail.com": "rediffmail.com",
  "rediiffmail.com": "rediffmail.com",
  "rediifmail.com": "rediffmail.com",
  "rediffmil.com": "rediffmail.com",
  "rediffmail.co": "rediffmail.com",
  "rediffmail.con": "rediffmail.com",
  "rediffmail.cm": "rediffmail.com",
  "rediffmial.com": "rediffmail.com",
  "redffimail.com": "rediffmail.com",
  "rdiffmail.com": "rediffmail.com",
  "radiffmail.com": "rediffmail.com"
};
var inputData = {};
var hiddenTypeArr = {};
var smallCase_regex = /^[a-z]+$/;
var upperCase_regex = /^[A-Z]+$/;
var specialChars_regex = /^.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]+$/;
var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
var digit_regex = /^[0-9]+$/;
var regField = {};
var passwordK = '';
var clicked = "";
var padding = 31;
        //var prefilledData = {"mtongue":"19","religion":"1","caste":"100","subcaste":"asdadfasdf","mstatus":"N","haveChildren":"N","height":"13","city":"MH31","manglik":"M","hdegree":"4","occupation":"36","income":"3","aboutme":"kasjndjkfasfiasuiduiasdajh sdbjbjdsbc basdjhcbjhasduc asdbc jhbasjh absdjhcb jhasbdcjbasd bcjhbasjhcbjhsdbcjh basdjhcbjhabsdjhcbcbjhawbbawebdjhbajwe awefawf"};
// base class 

(function () {
  var inputField = (function () {

    //this function is for basic common properties for all fields on a page
    inputField.prototype.changeFieldCss = function () {
      var ele = this;
      this.fieldElement.parent().bind("mousedown focus", function (ev) {
        //by default every field has this scrolling label
        //console.log('inputField mousedown');
        scrolLabel_1(ele.name);

        $("#" + ele.name + "_label").mousedown(function () {
          $('body').focus();//console.log('label mousedown');
          return false;
        });
        //console.log("click");
        var error = $("#" + ele.fieldType + "_error").html("");
        if (ele.dataType == "autoSuggest") {
          $("#" + ele.name + "-inputBox_set").focus();
        }
        $("#" + ele.fieldType + "_box").removeClass("err-border");
        //adding aditional functionality for IE browser for calling blur of a grid Dropdown field on tab or focus on another field
        if (isBrowserIE()) {
          //do not do anything on click of not firm india link
          if ($(ev.target).attr("id") == "NfiLink")
            return;
          var arrAllowedFields = [];

          var arrFields = $('[data-attr]');

          for (var j = 0; j < arrFields.length; j++) {

            var fieldName = typeof ($(arrFields[j]).attr('id')) != "undefined"
              ? $(arrFields[j]).attr('id').split('_')[0]
              : "";
            if (fieldName.length > 0 && arrAllowedFields.indexOf(fieldName) === -1) {
              arrAllowedFields.push(fieldName);
            }
          }

          for (var i = 0; i < arrAllowedFields.length; i++) {
            var id = '#' + arrAllowedFields[i] + '-multipleUls';
            var dummyID2 = "#" + arrAllowedFields[i] + '_box';
            var valueId = "#" + arrAllowedFields[i] + '_value';

            if (isDomElementVisible(id) && $(valueId).attr('data-type') == "gridDropdown")
            {
              //do blur on fields having _box in their id name
              $(dummyID2).trigger("customBlur");
            }
          }
        }
      });
      //this.fieldElement.on("blur submit", function(){
      this.fieldElement.blur(function () {
        ele.fieldElement.parent().blur(); //console.log('blur 1');
      });
      if (isBrowserIE() == true) {
        this.fieldElement.parent().parent().find(".toValidate").on("customBlur", {ele: this, debugInfo: "IE CustomBlur"}, ele.onBlur);
      }
      //put validation on the divs which have this class toValidate
      if (ele.dataType != "gridDropdown") {
        this.fieldElement.parent().parent().find(".toValidate").on("blur", {ele: this, debugInfo: "grid parent blur"}, ele.onBlur);
      }
    }
    inputField.prototype.fieldValidator = function (ele, actionType) {
      ele.validator.error = '';
      //function to check validation errors
      if (ele.validate == true || ele.validate == "true")
      {
        ele.validator.validate();
        if (ele.validator.error)
          this.showError(ele);
        else
          this.hideError(ele);
      }
    }
    //this function shows errors on validation
    inputField.prototype.showError = function (ele) {
      var fieldType = ele.fieldElement.attr("data-fieldType");
      var error = $("#" + fieldType + "_error").html(ele.validator.error);
      $("#" + fieldType + "_error").css("visibility", "visible");
      $("#" + fieldType + "_box").removeClass("err-border");
    }
    //this function hides errors on validation 
    inputField.prototype.hideError = function (ele) {
      var fieldType = ele.fieldElement.attr("data-fieldType");
      var error = $("#" + fieldType + "_error").html("");
      $("#" + fieldType + "_error").css("visibility", "hidden");
      $("#" + fieldType + "_box").removeClass("err-border");
    }
    //On My Blur
    inputField.prototype.onBlur = function (event, ele) {
      //console.log('inputfield blur');
      if (typeof ele == "undefined") {
        ele = event.data.ele;
        //console.log(event.data.debugInfo);
      }
      if ($(clicked).parents("#" + $(this).attr("id")).length && clicked != "body") {
        return;
      }
      ele.fieldValidator(ele, "blur");
    }
    //Function to handle Keyboard navigation
    inputField.prototype.handleKeyboardNavigation = function (event) {
      var ele = event.data.ele;
      if ($("#" + ele.name + "-multipleUls").length == 0 || !isDomElementVisible("#" + ele.name + "-multipleUls"))
        return;

      var arrAllowedKeyCode = [13, 37, 38, 39, 40];

      //To handle left and right arrow key
      if (typeof (event.data.lRArrow) != "undefined" && event.data.lRArrow === false) {
        arrAllowedKeyCode = [13, 38, 40];
      }
      if (arrAllowedKeyCode.indexOf(event.keyCode) === -1)
      {
        return;
      }

      //Stop propagation of this event
      stopEventPropagation(event, 1);
      event.preventDefault()

      var yDir = 0, xDir = 0;
      if (event.keyCode === 38) {//Up
        yDir = -1;
      }

      if (event.keyCode === 40) {//Down
        yDir = 1;
      }

      if (event.keyCode === 37) {//Left
        xDir = -1;
      }

      if (event.keyCode === 39) {//Right
        xDir = 1;
      }

      var currentID = -1;
      var dir = 0;
      var multiUls = "#" + ele.name + "-multipleUls";
      var selectedTab = $(multiUls + ' li.activeopt');
      var listLength = $(multiUls + ' li').last()[0].id.split('_')[1];
      if (selectedTab.length)
      {
        selectedTab = (typeof selectedTab.id == ("undefined")) ? selectedTab[0] : selectedTab;
        currentID = selectedTab.id.split('_')[1];
      }

      //If last selected is out of focus then find in view element as per keyCode
      var currTop = 0;
      if (currentID != -1)
        currTop = $('#' + ele.name + "_" + currentID).position().top;

      if (currTop < 0 || currTop > 180) {
        var checkScrollId = -1;
        var arr = $(multiUls + ' ul li');
        for (i = 0; i < arr.length; i++) {
          var id = arr[i].id;
          var top1 = $('#' + id).position().top;

          if (top1 > 0 && checkScrollId == -1 && yDir === 1/*Down Key*/) {
            checkScrollId = arr[i].id.split('_')[1];
            --checkScrollId;
            break;
          }

          if (top1 > 170 && checkScrollId == -1 && yDir === -1/*Up Key*/) {
            checkScrollId = arr[i].id.split('_')[1];
            ++checkScrollId;
            break;
          }
        }
        $('#' + ele.name + "_" + currentID).removeClass('activeopt');
        currentID = checkScrollId;
      }

      ///////////////////////////////////////////
      var numCol = ele.fieldElement.attr("data-alpha");
      if (numCol == "" || typeof numCol == "undefined") {
        numCol = ele.fieldElement.attr("data-columns");
      }

      if (typeof (ele.searchAndType) != "undefined" && ele.searchAndType)
        numCol = 1;

      if (typeof (numCol) == "undefined" && ele.dataType == "autoSuggest")
        numCol = 1;

      numCol = parseInt(numCol);
      currentID = parseInt(currentID);

      var oldCurrent = currentID;
      if (yDir && currentID >= 0) {
        currentID += numCol * yDir;
        dir = numCol * yDir;
      }

      if (xDir && currentID >= 0) {
        currentID += xDir;
        dir = xDir;
      }
      if (currentID < 0) {
        currentID = 0;
      }


      var newID = "";//"#" + ele.name + "_" + currentID;
      var oldID = "#" + ele.name + "_" + oldCurrent;

      do {
        newID = "#" + ele.name + "_" + currentID;
        currentID += dir;
      } while (currentID >= 0 && currentID <= listLength && isDomElementVisible(newID) === false);

      if ($(newID).length == 0 || false === isDomElementVisible(newID)) {
        return;
      }
      $(oldID).removeClass('activeopt');
      $(newID).addClass('activeopt');

      //If First Selection then scroll to top
      if (currentID === 0) {
        $(multiUls).scrollTop(0);
      }

      if (($(newID).position().top) > 179) {
        $(multiUls).scrollTop($(multiUls).scrollTop() + 180);
      }

      if (($(newID).position().top) < 0) {
        $(multiUls).scrollTop($(multiUls).scrollTop() - 180);
      }

      if (event.keyCode === 13) {//handle Enter
        $(newID).children().trigger('mousedown');
        return;
      }
    }
    //constructor to initialize common variables
    function inputField (fieldElement, arr)
    {
      this.fieldElement = fieldElement;
      this.elementId = arr.elementId;
      this.name = arr.name;
      this.formKey = this.fieldElement.attr("data-toSave");
      this.dataType = this.fieldElement.attr("data-type");
      this.required = this.fieldElement.attr("data-required");
      this.validate = this.fieldElement.attr("data-validate");
      this.fieldType = this.fieldElement.attr("data-fieldType");
      this.id = this.fieldElement.attr("id");
      if (this.validate == true || this.validate == "true")
        eval('this.validator=new ' + this.fieldType + 'Validator($(this))');
      else
        this.validator = '';
      inputData[this.formKey] = "";
    }
    return inputField;
  })();
  this.inputField = inputField;
}).call(this);

// for radio buttons type fields that is dividing a box into many other fields

(function () {
  var radioButtonType = (function () {
    //inheriting form base class
    inheritsFrom(radioButtonType, inputField);

    radioButtonType.prototype.changeFieldCss = function () {
      //parent function call
      radioButtonType.prototype.parent.changeFieldCss.call(this);
      var ele = this;
      this.fieldElement.parent().bind("mousedown focus", function (ev) {
        //this condition is when once the divided list has been created it just has to be shown and hidden
        if (!ele.shown) {
          $("#" + ele.name + "-inputBox_set").hide();
          $("#" + ele.name + "-list_set").show();
          //removing the previous active option and putting the new option
          if ($("#" + ele.name + "-list_set").find(".activeopt").attr("id")) {
            if ($("#" + ele.name + "-list_set").find(".activeopt").attr("id").substr(-1) == ele.maxNo - 1) {
              $("#" + ele.name + "-other-list_set").parent().show();
              $("#" + ele.name + "-other-list_set").show();
            }
          }
          if ($("#" + ele.name + "-other-list_set") && (ele.name == "sister" || ele.name == "brother")) {
            $("#" + ele.name + "-other-list_set").parent().show();
            $("#" + ele.name + "-other-list_set").show();
          }

          if (ele.chosenValueSub)
            $("#" + ele.name + "-other-list_set").show();
        }
        else {
          //creating new multiple field by fetching a single html and populating it
          //calculate total width with padding being subtracted
          totalWidth = ele.fieldElement.parent().width() - ele.maxNo + 1;
          //get the default dummy radio list
          listHtml = $("#radio-list").html();
          listHtml = listHtml.replace(/{{customId}}/g, "li_" + ele.name + 0);
          listHtml = listHtml.replace(/{{cusDbVal}}/g, Object.keys(dataArray[ele.name][0])[0]);
          listHtml = listHtml.replace(/{{customValue}}/g, dataArray[ele.name][0][Object.keys(dataArray[ele.name][0])[0]]);
          firstLi = $("#radio-list").html();
          //loop till max no of li's which are in the main list
          for (var a = 1; a <= ele.maxNo - 1; a++) {
            tempLi = firstLi.replace(/{{customId}}/g, "li_" + ele.name + a);
            tempLi = tempLi.replace(/{{cusDbVal}}/g, Object.keys(dataArray[ele.name][a])[0]);
            //filling data with array values
            tempLi = tempLi.replace(/{{customValue}}/g, dataArray[ele.name][a][Object.keys(dataArray[ele.name][a])[0]]);
            listHtml = listHtml.replace(/{{newLi}}/g, tempLi);
          }
          listHtml = listHtml.replace(/{{newLi}}/g, "");
          $("#" + ele.name + "-list_set").html("");
          //set the string into the html content
          $("#" + ele.name + "-list_set").html(listHtml);
          ele.shown = 0;
          //divide the total width by no. of terms
          $("ul." + ele.name + "opt li").width(totalWidth / ele.maxNo);
          var ele1 = ele;
          for (var b = 0; b < ele.maxNo; b++) {
            //click on any of these custom radio buttons
            $("#li_" + ele.name + b).mousedown(function (event) {
              //stop propagation to parent div
              event.stopPropagation();
              var ln = this.id.substr(-1);
              //if sub-list is open close it
              $("#" + ele1.name + "-other-list_set").hide();
              //toggle active option
              activeOption = $("#" + ele1.name + "-list_set").find(".activeopt");
              activeOption.removeClass("activeopt");
              $("#li_" + ele1.name + ln).addClass("activeopt");
              if ($("#" + ele1.name + "_other-arrow").length != 0)
                $("#" + ele1.name + "_other-arrow").remove();
              //put value selected into a variable of this object
              ele1.chosenValue = $("#li_" + ele1.name + ln).html();
              chosenValDb = $("#li_" + ele1.name + ln).attr("data-dbVal");
              inputData[ele1.formKey] = chosenValDb;
              if (ele.name == "cpf")
                handleGenderBox(ln);
              //put this value into value attribute of this fieldelement
              ele1.fieldElement.val(chosenValDb);
              if (ln == ele1.maxNo - 1 && ele1.fieldElement.attr("data-max-no-element")) {
                //creating sub lists for which it is required
                ele1.chosenValue = "";
                inputData[ele1.formKey] = "";
                $("#" + ele1.name + "_value").val("");
                radioButtonType.prototype.createRadioSubList(ele1);
                if ($("#" + ele1.name + "_other-arrow").length == 0)
                  $(this).append("<i id=\"" + ele1.name + "_other-arrow" + "\" class=\"reg-sprtie reg-droparrow pos_abs reg-pos13 reg-zi100\"></i>");
                $("#" + ele1.name + "-other-list_set").show();
              }
              //if there's a sublist in the radio type field
              if (ele1.subList)
              {
                ele1.chosenValueSub = "0";
                if (ele1.sublistCreated != 1 || ele1.chosenValue != inputData[ele1.name])
                {
                  inputData["m_" + ele1.name] = '';
                  radioButtonType.prototype.createSubList(ele1, ele1.chosenValue);
                  if ($("#" + ele1.name + "_other-arrow").length == 0)
                    $(this).append("<i id=\"" + ele1.name + "_other-arrow" + "\" class=\"reg-sprtie reg-droparrow pos_abs reg-pos13 reg-zi100\"></i>");
                }
                if (ele1.chosenValue != "None")
                  $("#" + ele1.name + "-other-list_set").show();
                else
                  $("#" + ele1.name + "_other-arrow").hide();
              }
            });
            $("#li_" + ele.name + b).blur(function () {
              if ($(this).hasClass("activeopt"))
                $(this).removeClass("activeopt");
            });
          }
        }
      });
      //logic specific to fourth page brother and sister list
      this.fieldElement.parent().bind("blur focusout", function () {
        if (ele.name == "brother" || ele.name == "sister")
        {
          $("#" + ele.name + "-other-list_set").parent().hide();
          if (ele.chosenValue && (ele.chosenValue == "None" || inputData['m_' + ele.name] == ''))
          {
            radioButtonType.prototype.showSelectedVal(ele);
          }
          if (ele.chosenValue != "None" && inputData['m_' + ele.name])
          {
            ele.showSection(ele);
          }
        }
        if (ele.chosenValue != "" && (!ele.showSections || ele.chosenValueSub == '')) {
          //if this inputbox, for displaying selected value, has already been made
          if (!ele.showInput) {
            $("#" + ele.name + "-inputBox_set").show();
            $("#" + ele.name + "-inputBox_set").children().html(ele.chosenValue);
            $("#" + ele.name + "-list_set").hide();
            $("#" + ele.name + "-other-list_set").parent().hide();
          }

          else {
            radioButtonType.prototype.showSelectedVal(ele);
            ele.showInput = 0;
          }
        }
      });
    }
    radioButtonType.prototype.showSection = function (ele) {
      var tempCoverDiv = $("#selected-list").html();
      tempCoverDiv = tempCoverDiv.replace(/{{cusId}}/g, ele.name);
      $("#" + ele.name + "-inputBox_set").html(tempCoverDiv);
      totalWidth = ele.fieldElement.parent().width() - ele.noOfSections + 1;
      listHtml1 = $("#radio-list").html();
      listHtml1 = listHtml1.replace(/{{customId}}/g, "li_" + ele.name + "_section1");
      listHtml1 = listHtml1.replace(/{{customValue}}/g, ele.chosenValue + " " + sections[ele.name][1]);
      firstLi = $("#radio-list").html();
      for (var a = 2; a <= ele.noOfSections; a++)
      {
        tempLi = firstLi.replace(/{{customId}}/g, "li_" + ele.name + "_section" + a);
        tempLi = tempLi.replace(/{{customValue}}/g, ele.chosenValueSub + " " + sections[ele.name][a]);
        listHtml1 = listHtml1.replace(/{{newLi}}/g, tempLi);
      }
      listHtml1 = listHtml1.replace(/{{newLi}}/g, "");
      $("#" + ele.name + "-selected-list").html("");
      $("#" + ele.name + "-selected-list").html(listHtml1);
      for (var a = 1; a <= ele.noOfSections; a++)
        $("#li_" + ele.name + "_section" + a).width(totalWidth / ele.noOfSections);
      $("#" + ele.name + "-list_set").hide();
      $("#" + ele.name + "-other-list_set").parent().hide();
      $("#" + ele.name + "-inputBox_set").show();
      $("#" + ele.name + "-selected-list").show();
      ele.showInput = 0;
    }
    radioButtonType.prototype.createSubList = function (element, selectedValue) {
      element.sublistCreated = 1;
      var firstHtml = $("#other-list").html();
      var a = 1;
      var subListPosition;
      var loopArray = {};
      for (var x in dataArray[element.name])
      {
        loopArray[x] = dataArray[element.name][x];
        if (dataArray[element.name][x][x] == selectedValue)
        {
          subListPosition = x;
          break;
        }
      }
      $("#" + element.name + "-other-list_set").html("");
      if (selectedValue != 0 && selectedValue != "None")
      {
        if (selectedValue != inputData[element.name])
        {
          inputData["m_" + element.name] = '';
          element.fieldElement.val('');
        }
        var noOfElements = parseInt(subListPosition) + 1;
        var listHtm = firstHtml;
        listHtm = listHtm.replace(/{{customId}}/g, "li_other_" + element.name + "_tag");
        listHtm = listHtm.replace(/{{customValue}}/g, "How many married?");
        for (var key in loopArray)
        {
          var value = loopArray[key];
          tempLi = firstHtml.replace(/{{customId}}/g, "li_other_" + element.name + key);
          tempLi = tempLi.replace(/{{customValue}}/g, value[Object.keys(loopArray[key])[0]]);
          tempLi = tempLi.replace(/{{cusDbVal}}/g, Object.keys(loopArray[key])[0]);
          listHtm = listHtm.replace(/{{newLi}}/g, tempLi);
        }
        ;
        listHtm = listHtm.replace(/{{newLi}}/g, "");
        $("#" + element.name + "-other-list_set").html(listHtm);
        $("#" + element.name + "-other-list_set").parent().css('display', 'block');
        $("#li_other_" + element.name + "_tag").css('cursor', 'default').css('background-color', '#f8f8f8');
        //$("ul."+element.name+"-otheropt li").width(totalWidth/(element.listNo - element.maxNo));
        var ele2 = element;
        for (var key in loopArray)
        {
          $("#li_other_" + element.name + key).width((totalWidth - 150) / noOfElements);
          $("#li_other_" + ele2.name + key).mousedown(function (event) {
            event.stopPropagation();
            var ln = this.id.substr(-1);
            activeOption = $("#" + ele2.name + "-other-list_set").find(".activeopt");
            activeOption.removeClass("activeopt");
            $("#li_other_" + ele2.name + ln).addClass("activeopt");
            ele2.chosenValueSub = $("#li_other_" + ele2.name + ln).html();
            chosenDbValSub = $("#li_other_" + ele2.name + ln).attr("data-dbVal");
            inputData["m_" + ele2.name] = chosenDbValSub;
            ele2.fieldElement.val(chosenDbValSub);
            ele2.showSection(ele2);
          });
        }
      }
      else
      {
        radioButtonType.prototype.showSelectedVal(element);
      }
    }
    radioButtonType.prototype.showSelectedVal = function (element) {
      element.sublistCreated = 0;
      inputBoxHtml = $("#inputBoxDummy").html();
      inputBoxHtml = inputBoxHtml.replace(/spanDummy/g, element.name + "_span-text");
      //put value in input box
      inputBoxHtml = inputBoxHtml.replace(/{{customValue}}/g, element.chosenValue);
      $("#" + element.name + "-inputBox_set").html(inputBoxHtml);
      $("#" + element.name + "-list_set").hide();
      $("#" + element.name + "-other-list_set").hide();
      $("#" + element.name + "-inputBox_set").show();
    }
    //this function creates sublist of radio type using a similar logic to radio type
    radioButtonType.prototype.createRadioSubList = function (element) {
      //totalWidth=$("#"+element.name+"-other-list-set").width();
      listHtml = $("#other-list").html();
      listHtml = listHtml.replace(/{{customId}}/g, "li_other_" + element.name + (element.maxNo));
      listHtml = listHtml.replace(/{{cusDbVal}}/g, Object.keys(dataArray[element.name][element.maxNo])[0]);
      listHtml = listHtml.replace(/{{customValue}}/g, dataArray[element.name][element.maxNo][Object.keys(dataArray[element.name][element.maxNo])[0]]);
      firstLi = $("#other-list").html();
      for (var a = element.maxNo + 1; a < element.listNo; a++) {
        tempLi = firstLi.replace(/{{customId}}/g, "li_other_" + element.name + a);
        tempLi = tempLi.replace(/{{cusDbVal}}/g, Object.keys(dataArray[element.name][a])[0]);
        tempLi = tempLi.replace(/{{customValue}}/g, dataArray[element.name][a][Object.keys(dataArray[element.name][a])[0]]);
        listHtml = listHtml.replace(/{{newLi}}/g, tempLi);
      }
      listHtml = listHtml.replace(/{{newLi}}/g, "");
      $("#" + element.name + "-other-list_set").html("");
      $("#" + element.name + "-other-list_set").html(listHtml);
      $("#" + element.name + "-other-list_set").parent().css('display', 'block');
      //$("ul."+element.name+"-otheropt li").width(totalWidth/(element.listNo - element.maxNo));
      var ele2 = element;
      //handling click on li's of the sublist
      for (var b = ele2.maxNo; b < ele2.listNo; b++) {
        $("#li_other_" + ele2.name + b).mousedown(function (event) {
          event.stopPropagation();
          var ln = this.id.substr(-1);
          //clicked = event.target;
          activeOption = $("#" + ele2.name + "-other-list_set").find(".activeopt");
          activeOption.removeClass("activeopt");
          $("#li_other_" + ele2.name + ln).addClass("activeopt");
          ele2.chosenValue = $("#li_other_" + ele2.name + ln).html();
          chosenValDb = $("#li_other_" + ele2.name + ln).attr("data-dbVal");
          inputData[ele2.formKey] = chosenValDb;
          ele2.fieldElement.val(ele2.chosenValue);
          setTimeout(function () {
            ele2.fieldElement.parent().focus();
            $("#" + ele2.name + "-list_set").show();
            $("#" + ele2.name + "-inputBox_set").hide();
            $("#" + ele2.name + "-other-list_set").parent().css('display', 'block');
          }, 0);
          if (ele2.name == "cpf")
            handleGenderBox(ln);
        });
      }
    }
    function handleGenderBox (chosenVal) {
      //show gender box only on the click of certain fields
      if (chosenVal == "0" || chosenVal == "5" || chosenVal == "6" || chosenVal == "7" || chosenVal == "8") {
        $("#genderBox").removeClass("disp-none");
        inputData["gender"] = regField["gender"].chosenValue.slice(0, 1);
      }
      else
        $("#genderBox").addClass("disp-none");
      if (chosenVal == "2" || chosenVal == "4") {
        $("#gender_value").val("F");
        inputData["gender"] = "F";
      }
      else if (chosenVal == "1" || chosenVal == "3") {
        $("#gender_value").val("M");
        inputData["gender"] = "M";
      }

    }
    //constructor for radio button type
    function radioButtonType (fieldElement, arr) {
      //parent constructor call
      radioButtonType.prototype.parent.constructor.call(this, fieldElement, arr);
      this.listNo = arr.choiceNumber;
      this.maxNo = arr.maxNo;
      this.chosenValue = "";
      this.shown = 1;
      this.showInput = 1;
      this.subList = arr.subList;
      this.showSections = arr.showSections;
      this.noOfSections = arr.noOfSections;
      this.clickedSub = "";
    }
    return radioButtonType;
  })();
  this.radioButtonType = radioButtonType;

}).call(this);

//for Date of birth field. This field is a separate type
//as it has a different structure

(function () {
  var dobType = (function () {

    inheritsFrom(dobType, inputField);
    //this function creates drop down for each date, month and year
    dobType.prototype.changeFieldCss = function () {
      var ele = this;
      dobType.prototype.parent.changeFieldCss.call(this);
      this.fieldElement.parent().bind("mousedown focus", function (ev) {
        totalWidth = ele.fieldElement.parent().width() - 2;
        $("ul.dobopt li").width(totalWidth / 3);
        $("#dob-list_set").show();
        //all the three lists are created when the main div is clicked
        dobType.prototype.createDateList();
        dobType.prototype.createMonthList();
        dobType.prototype.createYearList();
        //by default open date list
        $("#datesub").parent().show();
        $(this).unbind("mousedown focus");
        //if gender has been selected as male remove first 3 years
        if ($("#gender_value").val() == "M")
          $("#yearsub li:lt(3)").hide();
        else
          $("#yearsub li:lt(3)").show();
        for (j = 1; j <= 3; j++) {
          //click event handler for click on any of the three buttons
          $("#li_dob" + j).mousedown(function (ev) {
            stopEventPropagation(ev, 1);
            var ln = this.id.substr(-1);
            hideShowList();
            //if gender has been selected as male remove first 3 years
            if ($("#gender_value").val() == "M")
              $("#yearsub li:lt(3)").hide();
            else
              $("#yearsub li:lt(3)").show();
            $("#" + dataArray["dob"][ln] + "sub").parent().show();
            $("#" + dataArray["dob"][ln] + "Arrow1").show();
            $("#" + dataArray["dob"][ln] + "Arrow2").hide();
          });
          //a callback function is used so as to pass loop variable as an argument
          $("#" + dataArray["dob"][j] + "sub").mousedown(clickCallBack(j));
        }
        $(document).bind("mousedown", function (ev) {
          if (!$(ev.target).parents("#dob_selector").length) {
            clicked = "body";
            ele.fieldElement.parent().blur();
          }
        });
      });
      this.fieldElement.parent().blur(function () {
        if ($("#yearsub").parent().css("display") != "block")
          hideShowList("blur");
        inputData[ele.formKey]["day"] = parseInt($("#date_value").val());
        inputData[ele.formKey]["month"] = parseInt($("#month_value").val());
        inputData[ele.formKey]["year"] = parseInt($("#year_value").val());
      });
    }
    //to hide all other lists
    function hideShowList (con) {
      $("#datesub").parent().hide();
      $("#dateArrow1").hide();
      $("#monthsub").parent().hide();
      $("#monthArrow1").hide();
      $("#yearsub").parent().hide();
      $("#yearArrow1").hide();
      if (con == "blur") {
        $("#dateArrow2").show();
        $("#monthArrow2").show();
        $("#yearArrow2").show();
      }
    }
    //this funciton is used as a callback basicall to assign a click event
    //handler with an argument supplied to it
    function clickCallBack (a) {
      return function (eve) {
        eve.stopPropagation();
        var target = $(eve.target);
        //check if the target of click in whole of the sublist is a li
        if (target.is("li")) {
          $("#" + dataArray["dob"][a] + "_value").html(target.html());
          //for month values are replaced by month numbers
          if (dataArray["dob"][a] == "month")
            $("#" + dataArray["dob"][a] + "_value").val(eve.target.id.substr(7, eve.target.id.length));
          else
            $("#" + dataArray["dob"][a] + "_value").val(target.html());
          $("#" + dataArray["dob"][a] + "sub").find(".activeopt").removeClass("activeopt");
          target.addClass("activeopt");
          $(this).parent().hide();
          hideShowList();
          //if date is clicked open month by default
          if (a == 1) {
            setTimeout(function () {
              $("#monthsub").parent().show();
            }, 0);
            $("#monthArrow1").show();
            $("#monthArrow2").hide();
            $("#dateArrow2").show();
            clicked = eve.target;
          }
          //open year sublist on month list click
          if (a == 2) {
            setTimeout(function () {
              $("#yearsub").parent().show();
            }, 0);
            $("#yearArrow1").show();
            $("#yearArrow2").hide();
            $("#monthArrow2").show();
            clicked = eve.target;
          }
          if (a == 3) {
            $("#yearArrow2").show();
            clicked = "";
          }
          $("#dob_value").parent().focus();
        }
      }
    }
    //different function to create different sublists
    dobType.prototype.createDateList = function () {
      dateHtml = dobType.prototype.generalList(2, 31, "date", 1);
      $("#datesub").html(dateHtml);
    }
    dobType.prototype.createMonthList = function () {
      monthHtml = dobType.prototype.generalList(2, 12, "month", "Jan");
      $("#monthsub").html(monthHtml);
    }
    dobType.prototype.createYearList = function () {
      var d = new Date();
      var n = d.getFullYear();
      yearHtml = dobType.prototype.generalList(n - 19, n - 70, "year", n - 18);
      $("#yearsub").html(yearHtml);
    }
    //general list creator 
    dobType.prototype.generalList = function (l, h, c, d) {
      //get dob dummy list to populate this
      var dropHtml = $("#dobDummy").html();
      dropHtml = dropHtml.replace(/{{customValue}}/g, d);
      if (c == "month")
        dropHtml = dropHtml.replace(/{{cusId}}/g, c + "li1");
      else
        dropHtml = dropHtml.replace(/{{cusId}}/g, c + "li" + d);
      var firstLi = $("#dobDummy").html();
      //for year the list is in reverse order
      if (c == "year") {
        for (i = l; i >= h; i--) {
          tempHtml = firstLi.replace(/{{customValue}}/g, i);
          tempHtml = tempHtml.replace(/{{cusId}}/g, c + "li" + i);
          dropHtml = dropHtml.replace(/{{newLi}}/g, tempHtml);
        }
      }
      else {
        for (i = l; i <= h; i++) {
          if (c == "month")
            tempHtml = firstLi.replace(/{{customValue}}/g, dataArray["month"][i]);
          else
            tempHtml = firstLi.replace(/{{customValue}}/g, i);
          tempHtml = tempHtml.replace(/{{cusId}}/g, c + "li" + i);
          dropHtml = dropHtml.replace(/{{newLi}}/g, tempHtml);
        }
      }
      dropHtml = dropHtml.replace(/{{newLi}}/g, "");
      return(dropHtml);
    }
    //constructor
    function dobType (fieldElement, arr) {
      dobType.prototype.parent.constructor.call(this, fieldElement, arr);
      this.fieldElement = fieldElement;
      this.name = arr.name;
      inputData[this.formKey] = {"day": "", "month": "", "year": ""};
    }
    return dobType;
  })();
  this.dobType = dobType;

}).call(this);


// for textbox type fields

(function () {
  var textFieldType = (function () {

    inheritsFrom(textFieldType, inputField);

    textFieldType.prototype.changeFieldCss = function () {
      //parent function call
      textFieldType.prototype.parent.changeFieldCss.call(this);
      //function calls for different types of text fields
      if (this.name == "email")
        textFieldType.prototype.emailType(this.fieldElement);
      if (this.name == "password")
        textFieldType.prototype.passwordType(this.fieldElement);
      if (this.name == "phone")
        textFieldType.prototype.mobilePhoneType(this.fieldElement);
      if (this.name == "aboutme")
        textFieldType.prototype.aboutMeType(this.fieldElement);
      if (this.name == "aboutfamily")
        textFieldType.prototype.aboutFamilyType(this.fieldElement);
      var el = this;
      this.fieldElement.parent().bind("click focus", function () {
        //to shift focus on child div if it is a textbox
        el.fieldElement.focus();
      });
      //common keydown functionality for all text fields
      this.fieldElement.on("change", function () {
        if (el.name != "phone") {
          inputData[el.formKey] = this.value;
        }

      })
      if(this.name == "pin"){
        this.fieldElement.on("input", function () {
              inputData[el.formKey] = this.value;
        });
      }
      this.fieldElement.keydown(function (e) {
        //Shift Tab work
        if (e.keyCode == 9 && e.shiftKey) {
          var bhandle = false;
          var id = this.id;
          if (id == "password_value") {
            $("#email_value").focus();
            bhandle = true;
          }

          if (id == "phone_value") {
            id = document.activeElement.id;
          }

          if (id == "mobile_value") {
            $("#isd_value").focus();
            bhandle = true;
          }
          if (id == "isd_value") {
            $("#dob_box").focus();
            bhandle = true;
          }

          if (bhandle) {
            stopEventPropagation(e, 1);
            e.preventDefault();
            return;
          }
        }
        ///////////////////
        if (el.name != "phone" && $("#" + el.name + "_value").prop("tagName") != "TEXTAREA") {
          if (e.which == 13) {
            e.preventDefault();
            $(".buttonSub").click();
          }
        }
      });
      if (this.fieldElement.attr("data-characters-only")) {
        this.fieldElement.keydown(function (e) {

          /*Shift tab*/
          if (e.keyCode == 9 && e.shiftKey) {
            var bHandle = false;
            if (this.id == "name_value" && isDomElementVisible('#gender_box')) {
              $("#gender_box").focus();
              bHandle = true;
            }
            else if (this.id == "name_value") {
              $("#cpf_box").focus();
              bHandle = true;
            }

            if (bHandle) {
              stopEventPropagation(e, 1);
              e.preventDefault();
              return;
            }

          }
          ///////
          var a = [];
          var k = e.which;

          for (i = 65; i <= 90; i++)
            a.push(i);
          a.push(32);//space
          a.push(46);//delete
          a.push(8);//backspace
          a.push(9);//tab
          a.push(16);//shift
          a.push(37);//left arrow
          a.push(39);//right arrow
          a.push(35);//end
          a.push(36);//home
          a.push(45);//insert
          a.push(116);//F5
          a.push(186);//semi-colon
          if (e.shiftKey === false) {
            a.push(222);//quote
            a.push(190);//dot
            a.push(110);//dot(numpad)
            a.push(188);//comma
          }
          if (e.shiftKey === true) {
            a.push(57);// (
            a.push(48);// )
          }
          if (k && !($.inArray(k, a) != -1)) {
            e.preventDefault();
          }
        });
      }
    }
    //functions for respective text fields
    textFieldType.prototype.emailType = function (element) {
      notOnce = 1;
      element.blur(function () {
        emailValue = $("#email_value").val();
        if (emailValue)
          //function call for autocorrection of email
          var autoC = emailAutoCorrect(emailValue);
        //not once is used for autocorrection to run only once
        if (autoC && notOnce) {
          userEmailVal = $("#email_value").val();
          $("#email_value").val(autoC);
          $("#email_value").attr("value", autoC);
          $("#email_autoC").removeClass("colr5").addClass("colrblack");
          $("#email_autoC").html("We have auto-corrected your email to " + autoC + "<a id='undoEmail' style='cursor: pointer;color: #117daa;' class='colrblue'>&nbsp Undo</a>");
          $("#email_autoC").css("visibility", "visible");
          $("#email_autoC").show();
          notOnce = 0;
          //when undo autocorrect is clicked
          $("#undoEmail").click(function () {
            $("#email_value").val(userEmailVal);
            $("#email_value").attr("value", userEmailVal);
            $("#email_autoC").hide();
            $("#email_autoC").css("visibility", "hidden");
          });
        }
        else {
          $("#email_autoC").html("");
          $("#email_autoC").css("visibility", "hidden");
          $("#email_autoC").hide();
        }
      });
    }
    textFieldType.prototype.mobilePhoneType = function (element) {
      element.parent().bind("click focus", function (ev) {
        $("#isd_value").parent().show();
        $("#mobile_value").parent().show();
        $("#phone_help").show();
        $("#dropHelp").show();
        if (ev.target.id != "isd_value")
          $("#mobile_value").focus();
        inputData.phone_mob = {isd: "+91", mobile: ""};
      });
      $("#isd_value").keyup(function () {
        if (this.value == "+91")
          $("#mobile_value").attr('maxlength', '10');
        else
          $("#mobile_value").attr('maxlength', '15');
      });
      $("#isd_value").bind("blur", function () {
        inputData["phone_mob"]["isd"] = $(this).val();
      });
      $("#mobile_value").bind("blur", function () {
        inputData["phone_mob"]["mobile"] = $(this).val();
      });
      $("#mobile_value").bind("keyup", function (ev) {
        if (ev.which == 13) {
          ev.preventDefault();
          inputData["phone_mob"]["mobile"] = $(this).val();
          $(".buttonSub").click();
        }
      });
      $("#isd_value,#mobile_value").keydown(function (e) {
        //Shit tab work handle in common keydown funcationlity
        var a = [];
        var k = e.which;
        for (i = 48; i < 58; i++)
          a.push(i);
        for (i = 96; i <= 105; i++)
          a.push(i);
        a.push(43);
        a.push(8);
        a.push(9);
        a.push(16);
        a.push(37);//left arrow
        a.push(39);//right arrow
        a.push(35);//end
        a.push(36);//home
        a.push(45);//insert
        a.push(46);//delete
        a.push(107);//add
        a.push(187);//add
        a.push(61);
        a.push(116);//F5
        if (k && !($.inArray(k, a) >= 0))
          e.preventDefault();
      });
    }
    textFieldType.prototype.aboutMeType = function (element) {
      element.parent().bind("click focus", function (ev) {
        $("#aboutme_value").show();
        if (ev.target != this)
          return;
      });
      //for counting characters
      element.keyup(function () {
        var countChar = element.val().replace(/\s\s+/g, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '').length;
        if (countChar == 0)
          $("#cCount").html("Minimum Character: 100");
        else
        {
          if (countChar < 100)
            $("#cCount").html("Character Count: <span class='colr5'>" + countChar + "</span>");
          else
            $("#cCount").html("Character Count: <span style=\"color:#00FF00;\">" + countChar + "</span>");
        }
      });
    }
    textFieldType.prototype.aboutFamilyType = function (element) {
      element.parent().bind("click focus", function (ev) {
        $("#aboutfamily_value").show();
        if (ev.target != this)
          return;
      });
    }
    textFieldType.prototype.passwordType = function (element) {
      element.focus(function () {
        $("#strengthBar").show();
        $("#strength-text").show();
      });
      element.blur(function () {
        $("#strengthBar").hide();
        $("#strength-text").hide();
      });
      element.keyup(function () {
        var passVal = this.value;
        passwordStrength(passVal);
      });
      //function to show/hide password
      showHidePass("passShow", "password_value");
    }
    //constructor
    function textFieldType (fieldElement, arr) {
      //parent constructor call
      textFieldType.prototype.parent.constructor.call(this, fieldElement, arr);
    }
    return textFieldType;
  })();
  this.textFieldType = textFieldType;

}).call(this);

//for grid drop down with specified columns.The list which drops down on main div click

(function () {
  var gridDropdownType = (function () {

    inheritsFrom(gridDropdownType, inputField);
    gridDropdownType.prototype.changeFieldCss = function () {

      //parent function call
      gridDropdownType.prototype.parent.changeFieldCss.call(this);
      var ele = this;
      //handling click on main div click
      this.fieldElement.parent().bind("mousedown focus", function (ev) {
        //console.log('grid main click');
        $("#" + ele.fieldType + "_error").html("");
        ////Start of FireFox Special Check Thanks to Reshu
        if (isBrowserFirefox() && ele.doOncelabelLogic === false && ev.target.id.indexOf('_label')) {
          //console.log('label logic');
          setTimeout(function () {
            ele.doOncelabelLogic = true;
            var newEvent = jQuery.Event("mousedown");
            if (ele.searchAndType) { //console.log('label-inputBox logic');
              $("#" + ele.name + "-inputBox_set").trigger(newEvent);
            }
            else {//console.log('label-box logic');//console.log("#"+ele.name+"_box");
              $("#" + ele.name + "_box").mousedown().focus();
            }
          }, 1);
        }
        //End of FireFox Special Check Thanks to Reshu
        if (ele.searchAndType) {
          $("#" + ele.name + "-inputBox_set").focus();
          if ($("#" + ele.name + "-multipleUls").length) {
            searchDropdown($("#" + ele.name + "-multipleUls"), $("#" + ele.name + "-inputBox_set"), ele.name);
          }
        }

        if (ele.fromSubList && ele.searchAndType) {
          ele.fromSubList = 0;
          return;
        }
        ev.stopPropagation();
        //if the dropdown has already been created just show it
        if (!ele.showDrop) {

          if (ele.fieldElement.attr("data-hidden-Drop") != "true") {
            $('#' + ele.name + '-multipleUls').find('.activeopt').removeClass('activeopt');
            if (ele.selected != "")
              $("#" + ele.selectedId).addClass("activeopt");
            $("#" + ele.name + "-gridDropdown_set").show();
          }
          if ($("#" + ele.name + "-inputBox_set").prop("tagName") != "INPUT")
            $("#" + ele.name + "-inputBox_set").hide();

          var selectedLi = $('#' + ele.name + '-multipleUls').children().find("li[data-dbVal=" + inputData[ele.formKey] + "]");
          if (selectedLi.length > 0) {

            var position = selectedLi.position().top;
            var posY = $('#' + ele.name + '-multipleUls').scrollTop();

            if (position && (position < posY || position > posY + 200)) {
              $('#' + ele.name + '-multipleUls').scrollTop(position);
              selectedLi.addClass("activeopt");
            }
          }

        }
        else {
          //creating new multiple field by fetching a single html and populating it
          //call a function for this
          //if directly Not from india is clicked open country list by default
          if (ev.target.id == "NfiLink") {
            ele.fieldElement.attr("data-alpha", "4");
            arrayToCreate = dataArray["country"][0];
            if (ele.name == "city")
              ele.formKey = "country_res";
            else
              ele.formKey = "native_country";
          }
          else if (ele.name == "mstatus" && inputData["religion"] == "2" && $("#gender_value").val() == "M")
            arrayToCreate = dataArray["mstatus_muslim"][0];
          else
            arrayToCreate = dataArray[ele.name][0];
          if (ele.name == "familyState" && !ev.target.id == "NfiLink")
            ele.fieldElement.attr("data-alpha", "");
          ele.putValuesInList(ele, arrayToCreate);
          ele.showDrop = 0;
          if (ele.searchAndType) {
            searchDropdown($("#" + ele.name + "-multipleUls"), $("#" + ele.name + "-inputBox_set"), ele.name);
          }
        }
        //stopEventPropagation(ev);
        //ev.preventDefault();
      });
      //Grid Input Blur Call
      if ($("#" + ele.name + "-inputBox_set").prop("tagName") == "INPUT") {
        $("#" + ele.name + "-inputBox_set").on("customBlur", function (event) {
          //console.log('grid inputBox-set blur');
          ele.gridHide(ele);
          ele.onBlur(event, ele);
        });
      }
      //switching between city and country lists
      if (this.name == "city" || this.name == "familyState") {
        $("#NfiLink").mousedown(function () {
          //if it shows not from india
          if (!ele.fromIndia) {
            $(this).html("From India?");
            var toSave, notToSave;
            if (ele.name == "city")
            {
              toSave = "country_res";
              notToSave = "city_res";
            }
            else
            {
              toSave = "native_country";
              notToSave = "native_state";
              inputData['native_city'] = '';
            }
            ele.fieldElement.attr("data-toSave", toSave);
            inputData[notToSave] = "";
            inputData[toSave] = "";
            ele.formKey = toSave;
            if (ele.name == "city")
              $("#" + ele.name + "_label").html("Country");
            //change the values held bu the city list to country list
            ele.fieldElement.attr("data-alpha", "4");
            ele.putValuesInList(ele, dataArray["country"][0]);
            $("#" + ele.name + "-gridDropdown_set").show();
            ele.fromIndia = 1;
          }
          //if from india is shown
          else {
//              this.name = "city";
            $(this).html("Not from India?");
            if (ele.name == "city")
            {
              notToSave = "country_res";
              toSave = "city_res";
            }
            else
            {
              notToSave = "native_country";
              toSave = "native_state";
            }
            ele.fieldElement.attr("data-toSave", toSave);
            inputData[notToSave] = "51";
            ele.formKey = toSave;
            if (ele.name == "city")
              $("#" + ele.name + "_label").html("City living in");
            //change the values held by the country list to city list
            if (ele.name == "familyState")
              ele.fieldElement.attr("data-alpha", "");
            ele.putValuesInList(ele, dataArray[ele.name][0]);
            $("#" + ele.name + "-gridDropdown_set").show();
            ele.fromIndia = 0;
          }
          setTimeout(function () {
            $("#" + ele.name + "-inputBox_set").focus();
            $("#city_error").html("");
            $("#familyState_error").html("");
          }, 0);
          ele.selected = "";
          $("#city_value").val("");
          $("#familyCity_value").val("");
          $("#pincode_selector").addClass("disp-none");
          $("#familyCity_selector").addClass("disp-none");
          $("#familyCityOther_selector").addClass("disp-none");
          if (ele.name == "city")
            inputData["pincode"] = "";
          if (ele.name == "familyCity")
          {
            inputData['native_city'] = '';
            inputData["ancestral_origin"] = "";
          }
          searchDropdown($("#" + ele.name + "-multipleUls"), $("#" + ele.name + "-inputBox_set"), ele.name);
        });
      }
    }

    //Hide my MultipleUls
    //
    gridDropdownType.prototype.gridHide = function (ele) {
      $("#" + ele.name + "-gridDropdown_set").hide();
      $("#" + ele.name + "-inputBox_set").show();
      $("#" + ele.name + "-inputBox_set").val(ele.selected);
    }
    //ele.fieldElement.parent().blur(); 

    //this function binds click event handlers on list values
    gridDropdownType.prototype.clickBindValuesInList = function (ele) {
      var me = this;
      var ele1 = ele;
      $("#" + ele.name + "-multipleUls").mousedown(function (event1) {
        stopEventPropagation(event1, 1);
        event1.preventDefault()
        var target = $(event1.target);
        //if clicked element is a li 
        if (target.parent().is("li")) {
          ele1.fromSubList = 1;
          target.parent().parent().parent().find(".activeopt").removeClass("activeopt");
          target.parent().addClass("activeopt");
          if (ele1.name == "city" || ele1.name == "cityReg") {
            if (target.html() == "New Delhi" || target.html() == "Mumbai" || target.html() == "Pune/ Chinchwad")
              $("#pincode_selector").removeClass("disp-none");
            else
              $("#pincode_selector").addClass("disp-none");
            $("#pin_value").val("");
            inputData["pincode"] = "";
          }
          if(ele1.name == "countryReg" && target.html() != "India"){
            $("#pincode_selector").addClass("disp-none");
            $("#residentialStatus_selector").removeClass("disp-none");
            $("#pin_value").val("");
            inputData["pincode"] = "";
            inputData["city_res"] = "";
          }
	if(ele1.name == "countryReg" && target.html() == "India"){
	            $("#residentialStatus_selector").addClass("disp-none");
	}
          ele1.selected = target.text();
          ele1.selectedId = target.parent().attr('id');
          chosenValDb = target.parent().attr("data-dbVal");
          var stateVal;
          if (ele1.name == "familyState")
            stateVal = inputData[ele1.formKey];
          inputData[ele1.formKey] = chosenValDb;
          $("#" + ele1.name + "-gridDropdown_set").hide();
          //if the input display box has already been created just show that
          if (!ele1.showInp) {
            $("#" + ele1.name + "-inputBox_set").show();
            if ($("#" + ele1.name + "-inputBox_set").prop("tagName") == "INPUT")
              $("#" + ele1.name + "-inputBox_set").val(ele1.selected);
            else
              $("#" + ele1.name + "-inputBox_set").children().html(ele1.selected);
            ele1.fieldElement.val(chosenValDb);
            $("#" + ele1.name + "-gridDropdown_set").hide();
          }
          //create input box to diplay the selected value
          else {
            inputBoxHtml = $("#inputBoxDummy").html();
            inputBoxHtml = inputBoxHtml.replace(/spanDummy/g, ele1.name + "_span-text");
            inputBoxHtml = inputBoxHtml.replace(/{{customValue}}/g, ele1.selected);
            ele1.fieldElement.val(chosenValDb);
            if ($("#" + ele1.name + "-inputBox_set").prop("tagName") == "INPUT")
              $("#" + ele1.name + "-inputBox_set").val(ele1.selected);
            else
              $("#" + ele1.name + "-inputBox_set").html(inputBoxHtml);
            $("#" + ele1.name + "-gridDropdown_set").hide();
            $("#" + ele1.name + "-inputBox_set").show();
            ele1.showInp = 0;
          }
          if (ele1.name == "familyState")
          {
            if (stateVal != inputData['native_state'])
            {
              $("#familyCity-inputBox_set").val("");
              $("#familyCity").val("");
              $("#familyCityOther").val("");
              inputData["native_city"] = "";
              inputData["ancestral_origin"] = "";
              regField['familyCity'].showDrop = 0;
              regField['familyCity'].selected = "";
              if (ele1.fromIndia == 1)
              {
                $("#familyCity_selector").addClass("disp-none");
                $("#familyCityOther_selector").addClass("disp-none");
              }
              else
              {
                $("#familyCity_selector").removeClass("disp-none");
                arrayfamilyCity = dataArray['familyCity'][inputData['native_state']][0];
                ele1.putValuesInList(regField['familyCity'], arrayfamilyCity);
                $("#familyCity-gridDropdown_set").hide();
                $("#familyCityOther_selector").addClass("disp-none");
              }
            }
          }
          if(ele1.name == "stateReg"){
                regField["cityReg"].fieldElement.removeAttr("data-alpha");
                $("#cityReg-inputBox_set").val("");
                $("#cityReg").val("");
                $("#cityRegOther").val("");
                inputData["city_res"] = "";
                regField["cityReg"].showDrop = 0;
                regField["cityReg"].selected = "";
                $("#cityReg_selector").removeClass("disp-none");
                arrayfamilyCity = dataArray['cityReg'][inputData['state_res']][0];
                ele1.putValuesInList(regField['cityReg'], arrayfamilyCity);
                $("#cityReg-gridDropdown_set").hide();
          }
          if(ele1.name == "countryReg"){
            if(inputData[ele1.formKey] == '128'){
              regField["cityReg"].fieldElement.attr("data-alpha", "4");
              $("#cityReg-inputBox_set").val("");
                $("#cityReg").val("");
                $("#cityReg_value").val("");
                $("#cityRegOther").val("");
                inputData["city_res"] = "";
                regField["cityReg"].showDrop = 0;
                regField["cityReg"].selected = "";
                $("#cityReg_selector").removeClass("disp-none");
                arrayfamilyCity = dataArray['cityReg'][inputData['country_res']];
                ele1.putValuesInList(regField['cityReg'], arrayfamilyCity);
                $("#cityReg-gridDropdown_set").hide();
            }
            else
              ele1.fieldElement.removeAttr("data-alpha");
                $("#stateReg-inputBox_set").val("");
                $("#stateReg").val("");
                $("#stateRegOther").val("");
                $("#stateReg_value").val("");
                inputData["state_res"] = "";
                regField["stateReg"].selected = "";
                $("#stateReg-gridUl").find(".activeopt").removeClass("activeopt");

            $("#residentialStatus_value").val("");
            $("#residentialStatus-inputBox_set").html("");
                $("#stateReg").val("");
                inputData["res_status"] = "";
                regField["residentialStatus"].selected = "";
                $("#residentialStatus-list_set").find(".activeopt").removeClass("activeopt");
          }
          if (ele1.name == "familyCity")
          {
            if (inputData['native_city'] != "0")
            {
              inputData["ancestral_origin"] = "";
              $("#familyCityOther").val("");
              $("#familyCityOther_selector").addClass("disp-none");
            }
            else
              $("#familyCityOther_selector").removeClass("disp-none");
          }
          if (ele1.name == "mstatus") {
            if (ele1.selected == "Never Married") {
              $("#haveChildren_selector").addClass("disp-none");
            }
            else {
              $("#haveChildren_selector").removeClass("disp-none");
              $("#haveChildren-list_set").show();
            }
            $("#haveChildren_value").val("");
            $("#haveChildren-inputBox_set").html("");
            inputData["havechild"] = "";
            $("#haveChildren-list_set").find(".activeopt").removeClass("activeopt");
            regField["haveChildren"].showInput = 1;
            regField["haveChildren"].shown = 1;
            regField["haveChildren"].chosenValue = "";
          }
          if (ele1.name == "hdegree") {
              showDegreeFields();
          }
          //in case of muslim reset mstatus value
          if (ele1.fieldType == "religion" && ele1.selected != "Muslim" && inputData['mstatus'] == "M")
          {
            inputData['mstatus'] = '';
            $("#mstatus_value").val('');
            $("#mstatus_span-text").html('');
          }
          //change the array in mstatus values if muslim is selected
          if (ele1.fieldType == "religion")
          {
            if (ele1.selected == "Muslim" && $("#gender_value").val() == "M") {
              ele1.putValuesInList(regField['mstatus'], dataArray['mstatus_muslim'][0]);
            }
            else {
              ele1.putValuesInList(regField['mstatus'], dataArray['mstatus'][0]);
            }
          }
          $("#mstatus-gridDropdown_set").hide();
          //if this field has a dependent field which is hidden create a new object of that field
          //like in case of castes
          if (ele1.fieldElement.attr("data-has-dependent")) {
            dep = ele1.fieldElement.attr("data-has-dependent");
            //if the object has already been created show that object with changed values
            if (!ele1.depShown) {
              if (dataArray[dep][0].hasOwnProperty(ele1.selected) || (ele1.name == "religion" && ele1.selected == "Hindu")) {
                ele1.putValuesInList(ele1.dependentObj, dataArray[dep][0]);
                ele1.dependentObj.fieldElement.parent().parent().parent().removeClass("disp-none");
                $("#" + dep + "-gridDropdown_set").hide();
                if (ele.searchAndType)
                  $("#" + dep + "-inputBox_set").val("");
                else
                  $("#" + dep + "-inputBox_set").children().html("");
                ele1.dependentObj.fieldElement.val("");
              }
              else {
                ele1.dependentObj.fieldElement.parent().parent().parent().addClass("disp-none");
                if (ele.searchAndType)
                  $("#" + dep + "-inputBox_set").val("");
                else
                  $("#" + dep + "-inputBox_set").children().html("");
                ele1.dependentObj.fieldElement.val("");
              }
              inputData[ele1.dependentObj.formKey] = "";
              ele1.dependentObj.selected = "";
            }
            //create a new object of that field with it dropdown with values
            else {
              if (dataArray[dep][0].hasOwnProperty(ele1.selected) || (ele1.name == "religion" && ele1.selected == "Hindu") || (ele1.name == "countryReg" && (ele1.selected == "India" || ele1.selected == "United States"))) {
                var arr = {elementId: $("#" + dep + "_value").attr('id'), name: $("#" + dep + "_value").attr('id').split("_")[0], columnNo: $("#" + dep + "_value").attr("data-columns")};

                r1 = new gridDropdownType($("#" + dep + "_value"), arr);
                regField[r1.fieldType] = r1;
                regField[r1.fieldType].changeFieldCss();
                ele1.depShown = 0;
                ele1.dependentObj = regField[r1.fieldType];
                ele1.dependentObj.fieldElement.parent().parent().parent().removeClass("disp-none");
                ele1.dependentObj.fieldElement.addClass("js-tBox");
              }
            }
          }
          if (ele1.name == "religion") {
		$("#caste_no_bar").attr('checked', false);
		inputData["casteNoBar"] = $("#caste_no_bar").is(':checked');  
            if (ele1.selected == "Muslim" || ele1.selected == "Christian") {
              $("#caste_label").html("Sect");
              $("#caste_error").html("Please provide a Sect");
		$("#casteNoBarDiv").addClass("disp-none");
            }
            else {
              $("#caste_label").html("Caste");
              $("#caste_error").html("Please provide a Caste");
		$("#casteNoBarDiv").removeClass("disp-none");
            }
            $("caste-inputBox_set").val("");
            $("#jamaat-inputBox_set").val("");
            $("#casteMuslim-inputBox_set").val("");
            $("#jamaat_value").val("");
            $("#casteMuslim_value").val("");
            $("#jamaat-gridUl").find(".activeopt").removeClass("activeopt");
            $("#casteMuslim-gridUl").find(".activeopt").removeClass("activeopt");
            inputData["jamaat"] = "";
            inputData["castemuslim"] = "";
            if(typeof ele1.muslimDependentObj!="undefined" && typeof ele1.muslimDependentObj.selected !="undefined")
                ele1.muslimDependentObj.selected='';
            if(typeof regField["caste"] !="undefined" && typeof regField["caste"].sunniDependentObj !="undefined" && typeof regField["caste"].sunniDependentObj.selected != "undefined"){
                regField["caste"].sunniDependentObj.selected='';
                regField["caste"].sunniDependentObj.fieldElement.parent().parent().parent().addClass("disp-none");
            }
            
            if (ele1.selected == "Hindu" || ele1.selected == "Jain" || ele1.selected == "Sikh" || ele1.selected == "Buddhist") {
              if (ele1.selected == "Hindu") {
                $("#subcaste_selector").removeClass("disp-none");
                $("#subcaste-inputBox_set").val("");
                inputData["subcaste"] = "";
              }
              else {
                $("#subcaste_selector").addClass("disp-none");
                $("#subcaste-inputBox_set").val("");
                inputData["subcaste"] = "";
              }
              $("#manglik_selector").removeClass("disp-none");
              $("#manglik-list_set").show();
              $("#horoscopeMatch_selector").removeClass("disp-none");
              $("#horoscopeMatch-list_set").show();
            }
            else {
              $("#subcaste_selector").addClass("disp-none");
              $("#manglik_selector").addClass("disp-none");
              $("#horoscopeMatch_selector").addClass("disp-none");
              $("#subcaste-inputBox_set").val("");
              inputData["subcaste"] = "";
            }
            if(ele1.selected == "Muslim"){
                if (ele1.depMusShown) {
                        ele1.muslimDependentObj.fieldElement.parent().parent().parent().removeClass("disp-none");
                        $("#casteMuslim-gridDropdown_set").hide();
                }
                else{
                    var arr = {elementId: $("#casteMuslim_value").attr('id'), name: $("#casteMuslim_value").attr('id').split("_")[0], columnNo: $("#casteMuslim_value").attr("data-columns")};

                    r1 = new gridDropdownType($("#casteMuslim_value"), arr);
                    regField[r1.fieldType] = r1;
                    regField[r1.fieldType].changeFieldCss();
                    ele1.depMusShown = 1;
                    ele1.muslimDependentObj = regField[r1.fieldType];
                    ele1.muslimDependentObj.fieldElement.parent().parent().parent().removeClass("disp-none");
                    ele1.muslimDependentObj.fieldElement.addClass("js-tBox");
                }
            }
            else if(ele1.depMusShown){
                    ele1.muslimDependentObj.fieldElement.parent().parent().parent().addClass("disp-none");
                    $("#casteMuslim-inputBox_set").val("");
                    ele1.muslimDependentObj.fieldElement.val("");
                    inputData["castemuslim"] = "";
                    $("#casteMuslim-gridUl").find(".activeopt").removeClass("activeopt");
                    ele1.muslimDependentObj.selected='';
                    
                    if(regField["caste"].depSunniShown){
                        regField["caste"].sunniDependentObj.fieldElement.parent().parent().parent().addClass("disp-none");
                        $("#jamaat-inputBox_set").val("");
                        regField["caste"].sunniDependentObj.fieldElement.val("");
                        inputData["jamaat"] = "";
                        $("#jamaat-gridUl").find(".activeopt").removeClass("activeopt");
                        regField["caste"].sunniDependentObj.selected='';
                    }
            }
            $("#manglik_value").val("");
            $("#manglik-inputBox_set").html("");
            $("#manglik-list_set").find(".activeopt").removeClass("activeopt");
            inputData["manglik"] = "";
            regField["manglik"].shown = 1;
            regField["manglik"].showInput = 1;
            regField["manglik"].chosenValue = "";
            $("#horoscopeMatch_value").val("");
            $("#horoscopeMatch-inputBox_set").html("");
            $("#horoscopeMatch-list_set").find(".activeopt").removeClass("activeopt");
            inputData["horoscopeMatch"] = "";
            regField["horoscopeMatch"].shown = 1;
            regField["horoscopeMatch"].showInput = 1;
            regField["horoscopeMatch"].chosenValue = "";
          }
          
          if (ele1.name == "caste") {
            if(ele1.selected == "Sunni"){
                if (ele1.depSunniShown) {
                        ele1.sunniDependentObj.fieldElement.parent().parent().parent().removeClass("disp-none");
                        $("jamaat-gridDropdown_set").hide();
                }
                else{
                    var arr = {elementId: $("#jamaat_value").attr('id'), name: $("#jamaat_value").attr('id').split("_")[0], columnNo: $("#jamaat_value").attr("data-columns")};

                    r1 = new gridDropdownType($("#jamaat_value"), arr);
                    regField[r1.fieldType] = r1;
                    regField[r1.fieldType].changeFieldCss();
                    ele1.depSunniShown = 1;
                    ele1.sunniDependentObj = regField[r1.fieldType];
                    ele1.sunniDependentObj.fieldElement.parent().parent().parent().removeClass("disp-none");
                    ele1.sunniDependentObj.fieldElement.addClass("js-tBox");
                }
            }
            else if(ele1.depSunniShown){
                    ele1.sunniDependentObj.fieldElement.parent().parent().parent().addClass("disp-none");
                    $("#jamaat-inputBox_set").val("");
                    ele1.sunniDependentObj.fieldElement.val("");
                    inputData["jamaat"] = "";
                    $("#jamaat-gridUl").find(".activeopt").removeClass("activeopt");
                    ele1.sunniDependentObj.selected='';
            }
          }
          if (ele1.name == "countryReg" && regField["countryReg"]) {
              if(regField["countryReg"].selected == "India"){
                $("#stateReg_selector").removeClass("disp-none");
                $("#stateReg-list_set").show();
              }
              else {
                  $("#stateReg_selector").addClass("disp-none");
                  $("stateReg_value").val("");
                  $("stateReg-inputBox_set").html("");
                  $("stateReg-list_set").find(".activeopt").removeClass("activeopt");
                  inputData["state_res"] = "";
                  regField["stateReg"].shown = 1;
                  regField["stateReg"].showInput = 1;
                  regField["stateReg"].chosenValue = "";
              }
              if(regField["countryReg"].selected == "United States"){
                $("#cityReg_selector").removeClass("disp-none");
                $("#cityReg-list_set").show(); 
              }
              else{
                  $("#cityReg_selector").addClass("disp-none");
                  $("#cityReg_value").val("");
                  $("#cityReg-inputBox_set").html("");
                  $("#cityReg-list_set").find(".activeopt").removeClass("activeopt");
                  inputData["city_res"] = "";
                  regField["cityReg"].shown = 1;
                  regField["cityReg"].showInput = 1;
                  regField["cityReg"].chosenValue = "";
              }
          }
          if (ele1.name == "mtongue" && regField["caste"] && regField["religion"].selected == "Hindu") {
            regField["caste"].putValuesInList(regField["caste"], dataArray["caste"][0]);
            $("#caste-gridDropdown_set").hide();
          }
        }
      });

      if ($("#" + ele.name + "-multipleUls").length && ele.searchAndType) {
        //On Key Down bind handle keyboard navigation
        $("#" + ele.name + "-multipleUls").on("keydown", {ele: ele}, me.handleKeyboardNavigation);
      }
      else {
        //On Key Down bind handle keyboard navigation
        $("#" + ele.name + "_box").on("keydown", {ele: ele}, me.handleKeyboardNavigation);
      }
    }
    //puts all the values in the list from dataArray
    gridDropdownType.prototype.putValuesInList = function (ele, arrayToSet) {
      //fetch dummy grid html
      tempGridDummy = $("#gridDummy").html();
      manyUls = "";
      dummyGrid = $("#gridUlDummy").html();
      //take a single ul list
      gridLiHtml = $("#gridUlDummy").html();
      tempUlDummy = $("#gridUlDummy").html();
      tempGridDummy = $("#gridDummy").html();
      k = 0;
      //if this list does not have a  heading-values type format
      if (!ele.fieldElement.attr("data-alpha")) {
        //set the width of a single li by dividing it equally between the no. of columns specified in the html
        widthLi = (ele.fieldElement.parent().width() - padding) / ele.columnsGrid;
        for (x in arrayToSet) {
          tempHtml = $("#gridUlDummy").html();
          tempHtml = tempHtml.replace(/{{cusDbVal}}/g, Object.keys(arrayToSet[x])[0]);
          tempHtml = tempHtml.replace(/{{customValue}}/g, arrayToSet[x][Object.keys(arrayToSet[x])[0]]);
          tempHtml = tempHtml.replace(/{{customId}}/g, ele.name + "_" + k++);
          tempHtml = tempHtml.replace(/data-style=\"\"/g, "style=width:" + widthLi + "px");
          gridLiHtml = gridLiHtml.replace(/{{newLi}}/g, tempHtml);
        }
        //replace the default list html with blank value
        dummyLiRegEx = new RegExp(dummyGrid.substring(0, dummyGrid.length - 10), 'g');
        gridLiHtml = gridLiHtml.replace(dummyLiRegEx, "");
        gridLiHtml = gridLiHtml.replace(/{{newLi}}/g, "");
        //set the populated li's into the ul dummy
        $("#gridUlDummy").html(gridLiHtml);
      }
      //if it has that format with headings
      else {
        //if this field is a dependent field which depends in another fields selected value
        if (ele.fieldElement.attr("data-dependent")) {
          dependentField = ele.fieldElement.attr("data-dependent");
          //set its dataArray according to the value of field on which it depends
          if ($("#" + dependentField + "_value").val() == "1" && inputData["mtongue"])
            loopArray = arrayToSet[$("#" + dependentField + "-inputBox_set").val() + "_" + inputData["mtongue"]];
          else
            loopArray = arrayToSet[$("#" + dependentField + "-inputBox_set").val()];
        }
        else
          loopArray = arrayToSet;

        //To calculate next valid id
        var numCol = ele.fieldElement.attr("data-alpha");
        for (x in loopArray) {
          //if the heading is blank, set the width with respective data attributes
          //columnsGrid contains no of coulumns under no-heading section
          //data-alpha contains no of coulumns under heading section
          if (x == "blank" || x == "Others")
            widthLi = (ele.fieldElement.parent().width() - padding) / ele.columnsGrid;
          else
            widthLi = (ele.fieldElement.parent().width() - padding) / ele.fieldElement.attr("data-alpha");

          //For keyboard Navigation, set the id to next valid id
          if (k % numCol !== 0) {
            k += numCol - (k % numCol);
          }

          dummyGrid = $("#gridUlDummy").html();
          gridLiHtml = $("#gridUlDummy").html();
          for (g in loopArray[x]) {
            tempHtml = $("#gridUlDummy").html();
            tempHtml = tempHtml.replace(/{{cusDbVal}}/g, Object.keys(loopArray[x][g])[0]);
            tempHtml = tempHtml.replace(/{{customValue}}/g, loopArray[x][g][Object.keys(loopArray[x][g])[0]]);
            tempHtml = tempHtml.replace(/{{customId}}/g, ele.name + "_" + k++);
            tempHtml = tempHtml.replace(/data-style=\"\"/g, "style=width:" + widthLi + "px");
            gridLiHtml = gridLiHtml.replace(/{{newLi}}/g, tempHtml);
          }
          //remove default li which was present in the dummy
          dummyLiRegEx = new RegExp(dummyGrid.substring(0, dummyGrid.length - 10), 'g');
          gridLiHtml = gridLiHtml.replace(dummyLiRegEx, "");
          gridLiHtml = gridLiHtml.replace(/{{newLi}}/g, "");
          $("#gridUlDummy").html(gridLiHtml);
          finalGridHtml = $("#multipleUls").html();
          //put the heading as blank if it is a no heading list
          heading = "";
          if (x != "blank" && (x != "Others" || ele.name == "mtongue")) {
            heading = $("#alphaDiv").html();
            heading = heading.replace(/{{cusHeading}}/g, x);
          }
          manyUls = manyUls + heading;
          manyUls = manyUls + finalGridHtml;
          $("#gridUlDummy").html(tempUlDummy);
        }
        //set different uls here
        $("#multipleUls").html(manyUls);
      }
      gridDropdownHtml = $("#gridDummy").html();
      gridDropdownHtml = gridDropdownHtml.replace(/gridUlDummy/g, ele.name + "-gridUl");
      gridDropdownHtml = gridDropdownHtml.replace(/multipleUls/g, ele.name + "-multipleUls");
      //set the complete gridDropdown list
      $("#" + ele.name + "-gridDropdown_set").html(gridDropdownHtml);
      $("#gridDummy").html(tempGridDummy);

      var self = this;
      //Binding Events
      $("#" + self.name + "-multipleUls").on("focusout blur", {ele: self, debugInfo: 'multipleUls Blur'}, self.onBlur);

      if (typeof self.searchAndType == "undefined") {//Bind blur event on _box
        var eventName = (isBrowserIE() === false) ? "blur" : "customBlur";
        $("#" + self.name + "_box").on(eventName, {ele: self, debugInfo: 'searchAndType undefined blur'}, self.onBlur);
      }

      //call the binding click function
      ele.clickBindValuesInList(ele);
    }


    //On My Blur
    gridDropdownType.prototype.onBlur = function (event, ele) {
      if (typeof ele == "undefined") {
        ele = event.data.ele;
        //console.log(event.data.debugInfo);
      }
      else
      {
        //console.log('grid Blur');
      }

      //Hide grid or hide open multipleUls
      ele.gridHide(ele);
      //parent function call
      gridDropdownType.prototype.parent.onBlur.call(ele, event, ele);

    }
    //constructor
    function gridDropdownType (fieldElement, arr) {
      //parent constructor call
      gridDropdownType.prototype.parent.constructor.call(this, fieldElement, arr);
      this.doOncelabelLogic = false;
      this.columnsGrid = arr.columnNo;
      this.showInp = 1;
      this.showDrop = 1;
      this.selected = "";
      this.selectedId = "";
      this.fromIndia = 0;
      this.depShown = 1;
      this.depMusShown = 0;
      this.depSunniShown = 0;
      this.dependentObj = "";
      this.muslimDependentObj = "";
      this.sunniDependentObj = "";
      this.searchAndType = this.fieldElement.attr("data-search");
      this.fromSubList = 0;
      if (this.name == "city")
        inputData["country_res"] = "51";
      if (this.name == "familyCity")
        inputData["native_country"] = "51";
    }
    return gridDropdownType;
  })();
  this.gridDropdownType = gridDropdownType;

}).call(this);

//this is for the fields which have autosuggest type
(function () {
  var autoSuggestType = (function () {

    inheritsFrom(autoSuggestType, inputField);
    autoSuggestType.prototype.changeFieldCss = function () {
      autoSuggestType.prototype.parent.changeFieldCss.call(this);
      var ele = this;
      var a = [];
      for (i = 65; i <= 90; i++)
        a.push(i);
      for (i = 97; i <= 122; i++)
        a.push(i);
      for (i = 48; i <= 57; i++)
        a.push(i);
      a.push(32);
      a.push(46);
      a.push(8);
      a.push(38);
      a.push(40);
      var typingTimer;                //timer identifier
      var doneTypingInterval = 300;
      var me = this;
      $("#" + this.name + "_box").on("keydown", {ele: ele, lRArrow: false}, me.handleKeyboardNavigation);
      $("#" + this.name + "-inputBox_set").bind("keypress", function (e) {
          var k = e.which;
          if (e.shiftKey === true && (k ==60 || k==62)) {
            e.preventDefault();
          }
      });
      $("#" + this.name + "-inputBox_set").bind("keyup", function (e) {
        var k = e.which;
        stringToSend = $(this).val().trim();

        if (stringToSend == this.lastValue)
          return;

        this.lastValue = stringToSend;
        inputData[ele.formKey] = stringToSend;
        clearTimeout(typingTimer);
        var queryData = {q:stringToSend,type:''};
        if(ele.name == "subcaste"){
            queryData.type = "subcaste";
            queryData.caste = $("#caste_value").val();
        }
        if(ele.name == "pgCollege" || ele.name == "ugCollege"){
            queryData.type = "collg";
        }
            
        //check if there are more than one characters and also pressed key is not to be blocked
        if (stringToSend.length > 1 && ($.inArray(k, a) != -1)) {
          typingTimer = setTimeout(function () {
            ele1 = ele;
            //put an ajax requests on user action on keydown to fetch subcaste values on an array
            $.ajax({
              type: 'GET',
              url: "/register/autoSug",
              data: queryData,
              success: function (data) {
                if (data) {
                  response = data.split("\n");
                  ele1.createDropdown(ele1, response);
                  $("#" + ele1.name + "-gridDropdown_set").removeClass("disp-none");
                }
              }
            });
          }, doneTypingInterval);
        }
        else {
          $("#" + ele.name + "-gridDropdown_set").addClass("disp-none");
        }
      });
      $("#" + this.name + "-inputBox_set").bind("blur", function (ev) {
        $("#" + ele.name + "-gridDropdown_set").addClass("disp-none");
      });
    }
    autoSuggestType.prototype.createDropdown = function (ele1, resArr) {
      widthLi = (ele1.fieldElement.parent().width() - padding);
      tempGridDummy = $("#gridDummy").html();
      gridLiHtml = $("#gridUlDummy").html();
      dummyGrid = $("#gridUlDummy").html();
      k = 0;
      //creating array values in dropdown format
      for (x in resArr) {
        tempHtml = $("#gridUlDummy").html();
        tempHtml = tempHtml.replace(/{{customValue}}/g, resArr[x]);
        tempHtml = tempHtml.replace(/{{cusDbVal}}/g, "");
        tempHtml = tempHtml.replace(/{{customId}}/g, ele1.name + "_" + k++);
        tempHtml = tempHtml.replace(/data-style=\"\"/g, "style=width:" + widthLi + "px");
        gridLiHtml = gridLiHtml.replace(/{{newLi}}/g, tempHtml);
      }
      dummyLiRegEx = new RegExp(dummyGrid.substring(0, dummyGrid.length - 10), 'g');
      gridLiHtml = gridLiHtml.replace(dummyLiRegEx, "");
      gridLiHtml = gridLiHtml.replace(/{{newLi}}/g, "");
      //set the populated li's into the ul dummy
      $("#gridUlDummy").html(gridLiHtml);
      gridDropdownHtml = $("#gridDummy").html();
      gridDropdownHtml = gridDropdownHtml.replace(/gridUlDummy/g, ele1.name + "-gridUl");
      gridDropdownHtml = gridDropdownHtml.replace(/multipleUls/g, ele1.name + "-multipleUls");
      $("#" + ele1.name + "-gridDropdown_set").html(gridDropdownHtml);
      $("#gridDummy").html(tempGridDummy);

      $("#" + ele1.name + "-multipleUls").mousedown(function (event1) {
        event1.stopPropagation();
        var target = $(event1.target);
        //if clicked element is a li 
        if (target.parent().is("li")) {
          chosenValDb = target.text();
          inputData[ele1.formKey] = chosenValDb;
          $("#" + ele1.name + "-inputBox_set").val(chosenValDb);
          $("#" + ele1.name + "-gridDropdown_set").addClass("disp-none");
        }
      });
    }

    function autoSuggestType (fieldElement, arr) {
      //parent constructor call
      autoSuggestType.prototype.parent.constructor.call(this, fieldElement, arr);
      this.lastValue = "";
    }
    return autoSuggestType;
  })();
  this.autoSuggestType = autoSuggestType;

}).call(this);

//execute when the page html has been rendered
//here all the objects for different fields are created
$(document).ready(function () {
  $('.js-tBox').each(function () {
    var Id = $(this).attr('id');
    var splitId = Id.split("_");
    var type = $(this).attr("data-type");
    var fieldType = $(this).attr("data-fieldType");
    //checking fields type
    //and creating objects of different types on the basis of these field types
    if (type == "radio") {
      var numberChoices = $(this).attr("data-number");
      var maxNo = $(this).attr("data-max-no-element");
      var subList = $(this).attr("data-sublist");
      var fieldType = $(this).attr("data-fieldType");
      var showSections = $(this).attr("data-show-sections");
      var noOfSections = $(this).attr("data-no-of-sections");
      if (!maxNo)
        maxNo = numberChoices;
      var arr = {elementId: Id, name: splitId[0], choiceNumber: parseInt(numberChoices), maxNo: parseInt(maxNo), subList: subList, showSections: showSections, noOfSections: noOfSections};
      regField[fieldType] = new radioButtonType($(this), arr);
    }
    if (type == "text") {
      var arr = {elementId: Id, name: splitId[0]};
      regField[fieldType] = new textFieldType($(this), arr);
      if (regField[fieldType].name == "aboutme" || regField[fieldType].name == "aboutfamily")
        bindAboutmePlaceholder(regField[fieldType].name);
    }
    if (type == "dobSpecial") {
      var arr = {elementId: Id, name: splitId[0]};
      regField[fieldType] = new dobType($(this), arr);
    }
    if (type == "gridDropdown") {
      var arr = {elementId: Id, name: splitId[0], columnNo: $(this).attr("data-columns")};
      regField[fieldType] = new gridDropdownType($(this), arr);
    }
    if (type == "autoSuggest") {
      var arr = {elementId: Id, name: splitId[0]};
      regField[fieldType] = new autoSuggestType($(this), arr);
    }
    regField[fieldType].changeFieldCss();
    if (fieldType == "password")
      passwordK = fieldType;
  });
  if((pageId=="JSPCR2" || pageId=="JSPCR3") && incomplete ==true)
    prefillValues();
  //handle click on the button
  $(".buttonSub").click(function () {
    var orderArray = {};
    var k = 0;
    //create an array for storing the correct order of fields in the form
    $('.js-tBox').each(function () {
      orderArray[k++] = $(this).attr("data-fieldType");
    });
    var formError = false;
    var firstField = false;
    //loop on each field to find the element with errors on button submit
    for (b in orderArray) {
      var a = orderArray[b];
      var id = '#' + regField[a].name + '_box';
      var val = $("#" + regField[a].fieldType + "_value").val();
      //scroll every label except those which are hidden
      if ($(id).parent().parent().hasClass('disp-none') == false)
      {
        if (regField[a].required != false && regField[a].required != "false")
          scrolLabel_1(regField[a].name);
        if (regField[a].name == "phone" && val == '')
          regField[a].fieldElement.click();
        //by default open all the visible radio button type fields
        if ((regField[a].required != false && regField[a].required != "false") && ((regField[a].fieldElement.attr("data-type") == "radio" || regField[a].fieldElement.attr("data-type") == "dobSpecial") && (val == '')))
          regField[a].fieldElement.parent().mousedown();
        if (regField[a].fieldElement.attr("data-type") == "dobSpecial" && val == '')
          regField[a].fieldElement.parent().blur();
        regField[a].fieldValidator(regField[a], "submit");
        if (regField[a].validator.error)
        {
          if (firstField == false)
            firstField = regField[a];
          formError = true;
        }
      }
    }
    //focus on the first field with error in the form
    if (formError)
    {
      $("body, html").scrollTop(firstField.fieldElement.offset().top - 17);
      firstField.fieldElement.focus();
    }
    //if there is no error submit form through ajax
    if (!formError)
    {
      $("#hiddenParams").children().each(function () {
        hiddenTypeArr[$(this).attr("name")] = $(this).attr("value");
      });
      leadid = $("#leadid").val();
      inputData["source"] = $("#reg_source").val();
//        inputData["record_id"]=$("#reg_record_id").val();
      if(pageId=="JSPCR2")
      {
        inputData["casteNoBar"] = $("#caste_no_bar").is(':checked');  
      }      
      inputData["_csrf_token"] = $("#registrationData__csrf_token").val();
      if(inputData.hasOwnProperty('state_res')){
          if(inputData['city_res'] == '0' && inputData['country_res']=='51')
              inputData['city_res'] = inputData['state_res']+'OT';
          delete inputData['state_res'];
      }
          
      $.ajax({
        url: "/register/regPage",
        type: "POST",
        datatype: 'json',
        cache: true,
        async: true,
        data: {formValues: inputData, page: pageId, hiddenValues: hiddenTypeArr, leadid: leadid},
        beforeSend: function (xhr) {
           showCommonLoader(); 
        },
        success: function (res) {
            hideCommonLoader();
          result = res;//$.parseJSON(res);
          if(result == "logout"){
              prevUrl = window.location.href;
              window.location.href = "/static/logoutPage?redirectUri="+escape(prevUrl);
          }
          if (result.responseStatusCode.indexOf("0") === -1)
          {
            var formErr = false;
            var firstErrField = false;
            var errorArr = result.error;
            for (b in orderArray)
            {
              var a = orderArray[b];
              ele = regField[a];
              ele.validator.error = errorArr[ele.formKey];
              if (ele.validator.error) {
                ele.showError(ele);
                if (firstErrField == false)
                  firstErrField = regField[a];
                formErr = true;
              }
              else
                ele.hideError(ele);
              ele.validator.error = '';
            }
            if (formErr)
            {
              $("body, html").scrollTop(firstErrField.fieldElement.offset().top - 17);
            }
          }
          else if (result.responseStatusCode.indexOf("0") !== -1 && nextPageId) {
            if (result.leadid)
              $("#leadid").val(result.leadid);
            var form = document.createElement("form");

            if(incomplete == true)
                form.action = "/register/page" + nextPageId.trim().slice(-1)+"?incompleteUser=1";
            else
                form.action = "/register/page" + nextPageId.trim().slice(-1);
		if(nextPageId=="JSPCR5")
			form.action += "?fromReg=1";
            form.method = "post";
            form.name = "formValues";

            for (a in hiddenTypeArr) {
              input = document.createElement("input");
              input.name = a;
              input.setAttribute("value", hiddenTypeArr[a]);
              form.appendChild(input);
            }
            input = document.createElement("input");
            input.name = "leadid";
            input.setAttribute("value", $("#leadid").val());
            form.appendChild(input);
            input = document.createElement("input");
            input.name = "groupname";
            input.setAttribute("value", $("#groupname").val());
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
          }
        }
      });
    }
  });
});

//function to scroll a label and show drop-down with a given class
function scrolLabel_1 (param) {
  setTimeout(function () {
    var width = $('.js-' + param)[0].getBoundingClientRect().width;
    var animate = 140 - width;
    $('.js-div1 .showdd').css('display', 'none');
    $('.js-' + param).animate({"left": animate, "top": "10px"}, 100, function () {

      $('.jsdd-' + param).css('display', 'block');
    });
  }, 0);
}
//for auto-correcting the email
function emailAutoCorrect (email) {
  if (email.indexOf('@') != -1) {
    var domain = email.split('@');
    var oldDom = domain[1];
    oldDom = oldDom.toLowerCase();
    if (!emailCorrections[oldDom])
      return false;
    var stringToReplace = emailCorrections[oldDom];
    return email = domain[0] + '@' + stringToReplace;
  }
  return false;
}
//password strength bar css changes based on checking conditions
function passwordStrength (passVal) {
  var getCharNo = passVal.length;
  if (getCharNo == 0) {
    $("#strength-span").width("0%");
    //$("#strength-span").css("background","#f5f5f5");
  }
  else if ((getCharNo > 0 && getCharNo < 8) || !regField[passwordK].validator.checkCommonPassword(passVal) || !regField[passwordK].validator.checkPasswordUserName(passVal, regField[passwordK].validator.getValue("email"))) {
    $("#strength-span").width("33%");
    $("#strength-span").css("background", "#a03");
  }
  else if (getCharNo >= 8 && !(digit_regex.test(passVal) || smallCase_regex.test(passVal) || upperCase_regex.test(passVal) || specialChars_regex.test(passVal))) {
    $("#strength-span").width("100%");
    $("#strength-span").css("background", "#76c261");
  }
  else if (getCharNo >= 8) {
    $("#strength-span").width("66%");
    $("#strength-span").css("background", "#2d98f3");
  }
}
function send_username_password (to_send_email)
{
  to_send_email = escape(to_send_email);
  var to_post = to_send_email;
  var data1 = {"to_send_email": to_send_email, "forgot_password": 1};
  var url1 = "/profile/registration_ajax_validation.php";
  $.ajax({
    type: 'POST',
    url: url1,
    data: data1,
    success: function (data) {
      response = data;
      ele = regField['email'];
      ele.validator.error = response;
      ele.showError(ele);
    }
  });
}
//handling the functionality of placeholder on firefox and IE
function bindAboutmePlaceholder (aboutwhat)
{
    if(aboutwhat == "aboutfamily")
        placeholder = aboutfamilyPlaceholder;
    if(aboutwhat == "aboutme")
        placeholder = aboutmePlaceholder;
  if (!(/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())))
  {
    scrolLabel_1(aboutwhat);

    if (!$("#"+aboutwhat+"_value").val())
    {
      $("#"+aboutwhat+"_value").val(placeholder).css("color", "#a7a7a7");
    }

    $("#"+aboutwhat+"_value").bind("focus", function ()
    {
      if ($("#"+aboutwhat+"_value").val() == placeholder)
      {
        $("#"+aboutwhat+"_value").val('').css("color", '#000');
      }
    });

    $("#"+aboutwhat+"_value").bind("blur", function ()
    {
      if ($("#"+aboutwhat+"_value").val() == '' || $("#"+aboutwhat+"_value").val() == placeholder)
      {
        $("#"+aboutwhat+"_value").val(placeholder).css("color", '#a7a7a7').show();
      }
    });

    $("#"+aboutwhat+"_value").blur();
  }
  else
  {
    $("#"+aboutwhat+"_value").attr("placeholder", placeholder);
  }
}

//function to show hide degree fields when highest degree is chosen

function showDegreeFields()
        {
		$("#other_pg_degree").hide();
		$("#other_ug_degree").hide();
		var highestDegree = inputData['edu_level_new'];
		var inug = $.inArray(highestDegree,ugArr);
                bindAddMoreLinks();
		if(inug!=-1)
		{
                        if(highestDegree != ''){
                            clearUgDegree();
                            clearPgDegree();
                        }
			$("#college").hide();
			$("#addMoreUgDegree").hide();
			$("#pg_college").hide();
			$("#degree_ug").hide();
			$("#degree_pg").hide();
                        $("#addUg").hide();
                        $("#addPg").hide();
			$("#addMorePgDegree").hide();
                        $("#otherUgDegreeInput").hide();
                        $("#otherPgDegreeInput").hide();
			return;
		}
		var inbachelor = $.inArray(highestDegree,bachelorArr);
		if(inbachelor!=-1)
		{
                        if(highestDegree != ''){
                            clearPgDegree();
                        }
			$("#college").show();
                /*
		        if($("#otherUgDegreeInput").css('display')=='none')
                            $("#addMoreUgDegree").show();
		*/
			$("#pg_college").hide();
			$("#degree_ug").hide();
			$("#degree_pg").hide();
                        $("#addUg").hide();
                        $("#addPg").hide();
			$("#addMorePgDegree").hide();
                        $("#otherPgDegreeInput").hide();
                        inputData['degree_ug'] = inputData['edu_level_new'];
			return;
		}
		var inmaster = $.inArray(highestDegree,pgDegreeArr);
		if(inmaster!=-1)
		{
			$("#college").show();
			$("#addMoreUgDegree").hide();
			$("#pg_college").show();
			$("#degree_ug").show();
/*
                        if($("#otherUgDegreeInput").css('display')=='none')
                            $("#addUg").show();
*/
			$("#degree_pg").hide();
                        $("#addPg").hide();
/*
                        if($("#otherPgDegreeInput").css('display')=='none')
                            $("#addMorePgDegree").show();
*/
                        inputData['degree_pg'] = inputData['edu_level_new'];
			return;
		}
		var inPhd = $.inArray(highestDegree,phdArr);
		if(inPhd!=-1)
		{
			$("#college").show();
			$("#addMoreUgDegree").hide();
			$("#pg_college").show();
			$("#degree_ug").show();
			$("#degree_pg").show();
/*
                        if($("#otherUgDegreeInput").css('display')=='none')
                            $("#addUg").show();
                        if($("#otherPgDegreeInput").css('display')=='none')
                            $("#addPg").show();
*/
			$("#addMorePgDegree").hide();
			return;
		}
		if(inug==-1 && inmaster==-1 && inPhd==-1 && inbachelor==-1)
		{
                        if(highestDegree != ''){
                            clearUgDegree();
                            clearPgDegree();
                        }
			$("#college").hide();
			$("#addMoreUgDegree").hide();
			$("#pg_college").hide();
			$("#degree_ug").hide();
			$("#degree_pg").hide();
                        $("#addUg").hide();
                        $("#addPg").hide();
                        $("#otherUgDegreeInput").hide();
                        $("#otherPgDegreeInput").hide();
			$("#addMorePgDegree").hide();
		}
        }
        
// function for handling click on add more links
function bindAddMoreLinks(){
    $(".js-moreUgDegree").bind("click mousedown",function(){
      $("#otherUgDegreeInput").show();
      $(this).hide();
    });
    $(".js-morePgDegree").bind("click mousedown",function(){
      $("#otherPgDegreeInput").show();
      $(this).hide();
    });
}

//function to clear values on change of highest education
function clearPgDegree(){
    inputData['degree_pg'] = '';
    $("#pgDegree_value").val("");
    $("#pgDegree-inputBox_set").html("");
    $("#pgDegree-list_set").find(".activeopt").removeClass("activeopt");
    regField["pgDegree"].showInput = 1;
    regField["pgDegree"].shown = 1;
    regField["pgDegree"].chosenValue = "";
    $("#pgCollege-inputBox_set").val('');
    inputData['pg_college'] = '';
    $("#otherPgDegree_value").val('');
}

//function to clear values on change of highest education
function clearUgDegree(){
    inputData['degree_ug'] = '';
    $("#ugDegree_value").val("");
    $("#ugDegree-inputBox_set").html("");
    $("#ugDegree-list_set").find(".activeopt").removeClass("activeopt");
    regField["ugDegree"].showInput = 1;
    regField["ugDegree"].shown = 1;
    regField["ugDegree"].chosenValue = "";
    
    inputData['college'] = '';
    $("#ugCollege-inputBox_set").val('');
    $("#otherUgDegree_value").val('');
}

  
//for handling body click on IE . For closing of dropdowns on click of body
(function () {
  if (isBrowserIE() === false)
    return;


  var arrAllowedFields = [];

  var arrFields = $('[data-attr]');

  for (var j = 0; j < arrFields.length; j++) {

    var fieldName = typeof ($(arrFields[j]).attr('id')) != "undefined"
      ? $(arrFields[j]).attr('id').split('_')[0]
      : "";
    if (fieldName.length > 0 && arrAllowedFields.indexOf(fieldName) === -1) {
      arrAllowedFields.push(fieldName);
    }
  }

  $('#mainRegContent, body , .buttonSub').on("click focus", function mainContentHandling (event) {
    var arrNotAllowedIds = ["NfiLink", "height_box", "mstatus_box", "income_box", "fatherOccupation_box", "motherOccupation_box"];
    //if($(event.target).attr("id") == "NfiLink" || )
    var targetId = $(event.target).attr("id");
    //console.log(targetId);
    if (targetId && typeof (targetId) !== "undefined" &&
      (arrNotAllowedIds.indexOf(targetId) != -1 || targetId.indexOf("_label") != -1)
      )
    {
      return;
    }
    for (var i = 0; i < arrAllowedFields.length; i++) {
      var id = '#' + arrAllowedFields[i] + '-multipleUls';
      var dummyID2 = "#" + arrAllowedFields[i] + '_box';
      var valueId = "#" + arrAllowedFields[i] + '_value';

      if (isDomElementVisible(id) &&
        $(valueId).attr('data-type') == "gridDropdown")
      {
        $(dummyID2).trigger("customBlur");
      }
    }
  });
})();

//Handling History Back
(function () {
  if (typeof (historyStoreObj) === "undefined") {
    return;
  }
  
  // Declare Varibales
  var overlay       = '.js-overlay';
  var overlayMsg    = '.js-regOverlayMsg';
  var overlayClose  = '.js-regOverlayClose';
  var displayNone   = 'disp-none';
  var msgTimeout    = 5000;
  var timeoutId     = null;
  //Function to show hide overlay 
  function showHideOverlay(bShow)
  {
    if(0 === $(overlay).length)
      return false;
    
    if(true === bShow){
      $(overlay).removeClass(displayNone);
      $(overlayMsg).removeClass(displayNone);
    }
    else if(false === bShow)
    {
      $(overlay).addClass(displayNone);
      $(overlayMsg).addClass(displayNone);
    }
  }
  
  //Binding Close Button on overlay
  $(overlayClose).on('click',function(){
    if(null !== timeoutId){
      clearTimeout(timeoutId);
      timeoutId = null;
    }
    showHideOverlay(false);
  });
  
  //Show Back Btn Msg
  var showBrowserBackMsg = function () {
    showHideOverlay(true);
    historyStoreObj.push(onBrowserBack, "#register");
    timeoutId = setTimeout(function(){
      showHideOverlay(false);
      timeoutId = null;
    },msgTimeout);
  }
  
  //Function callback when browser back will called
  var onBrowserBack = function () {
    if (location.href.indexOf("register") != -1) {
      showBrowserBackMsg();
      return true;
    }
    return false;
  }
  if(pageId != "JSPCR1")
    historyStoreObj.push(onBrowserBack, "#register");
  
})();
    $(document).ready(function(e) {
	if(pageId=="JSPCR1")
	{
	inputData['displayname']="Y";
        $(".optionDrop li").each(function(index, element) {
            $(this).on("click",function(){
                                $(".optionDrop li").each(function(index, element) {
                                        $(this).removeClass("selected");
                                });
                                $(this).addClass("selected");
                                if($(this).attr("id") == "showYes") {
                                        $("#showText").html("Show to All");
                                }
                                else {
                                        $("#showText").html("Don't show my name");
                                }
				var displayNameVal = $(this).attr('data-fieldVal');
				inputData['displayname']=displayNameVal;
				$("#optionDrop").removeClass("optionDrop");
				setTimeout(function(){ $("#optionDrop").addClass("optionDrop");}, 500);
                        });
        });
    }
    });

