      google.load("elements", "1", {
            packages: "transliteration"
          });
      var transliterationControl;
      function onLoad() {
        var options = {
            sourceLanguage:
                google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage:
                google.elements.transliteration.LanguageCode.HINDI,
            transliterationEnabled: true,
            shortcutKey: 'ctrl+g' // Disable the ShortCut Keys
        };
			
	if (document.getElementById('checkboxId').checked == false || document.getElementById('checkboxId2').checked == false || document.getElementById('checkboxId3').checked == false)
	{
		document.getElementById('languageDropDown1').disabled = true;
		document.getElementById('languageDropDown2').disabled = true;
		document.getElementById('languageDropDown3').disabled = true;
	}

        // Create an instance on TransliterationControl with the required
        // options.
        transliterationControl =
          new google.elements.transliteration.TransliterationControl(options);
		transliterationControl.toggleTransliteration();
        // Enable transliteration in the textfields with the given ids.
        var ids = ["about_yourself","about_desired_partner","about_family"];
        transliterationControl.makeTransliteratable(ids);

        // Add the STATE_CHANGED event handler to correcly maintain the state of the checkbox.

        transliterationControl.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED,
            transliterateStateChangeHandler); 

        // Add the SERVER_UNREACHABLE event handler to display an error message if unable to reach the server.

        transliterationControl.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.SERVER_UNREACHABLE,
            serverUnreachableHandler);

        // Add the SERVER_REACHABLE event handler to remove the error message once the server becomes reachable.

        transliterationControl.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.SERVER_REACHABLE,
            serverReachableHandler);

        /* Set the checkbox to the correct state.

        document.getElementById('checkboxId').checked =
          transliterationControl.isTransliterationEnabled();

	document.getElementById('checkboxId2').checked =
          transliterationControl.isTransliterationEnabled();

   	document.getElementById('checkboxId3').checked =
          transliterationControl.isTransliterationEnabled(); */

	 	   // Increasing the Height and Width Forcely for the TextArea

		   var mydiv = document.getElementById("about_family");
		   var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
		   mydiv.style.width = (curr_width + 400) +"px"; //font-family
		   mydiv.style.fontFamily = "arial";
		   mydiv.style.fontSize = "12px";
		   
		   var mydiv = document.getElementById("about_family");
		   var curr_height = parseInt(mydiv.style.height); // removes the "px" at the end
		   mydiv.style.height = (curr_height + 80) +"px";

		   var mydiv = document.getElementById("about_yourself");
		   var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
		   mydiv.style.width = (curr_width + 400) +"px";
		   mydiv.style.fontFamily = "arial";
		   mydiv.style.fontSize = "12px";

		   var mydiv = document.getElementById("about_yourself");
		   var curr_height = parseInt(mydiv.style.height); // removes the "px" at the end
		   mydiv.style.height = (curr_height + 126) +"px";

		   var mydiv = document.getElementById("about_desired_partner");
		   var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
		   mydiv.style.width = (curr_width + 400) +"px";
		   mydiv.style.fontFamily = "arial";
		   mydiv.style.fontSize = "12px";

		   var mydiv = document.getElementById("about_desired_partner");
		   var curr_height = parseInt(mydiv.style.height); // removes the "px" at the end
		   mydiv.style.height = (curr_height + 126) +"px";

        // Populate the language dropdown

        var destinationLanguage =
          transliterationControl.getLanguagePair().destinationLanguage;
        var languageSelect = document.getElementById('languageDropDown');
        var supportedDestinationLanguages =
          google.elements.transliteration.getDestinationLanguages(
            google.elements.transliteration.LanguageCode.ENGLISH);

        for (var lang in supportedDestinationLanguages) {
          var opt = document.createElement('option');
          opt.text = lang;
          opt.value = supportedDestinationLanguages[lang];
          if (destinationLanguage == opt.value) {
            opt.selected = true;
          }
          try {
            languageSelect.add(opt, null);
          } catch (ex) {
            //languageSelect.add(opt); 
          }
        }
	      //Event Handling for the Check Boxes
	      /*document.getElementById("checkboxId").onclick=checkboxClickHandler;
	      document.getElementById("checkboxId2").onclick=checkboxClickHandler;
	      document.getElementById("checkboxId3").onclick=checkboxClickHandler;*/


	     /* function addWidth() {
		   var mydiv = document.getElementById("about_family");
		   var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
		   mydiv.style.width = (curr_width + 400) +"px";
	      } */

      }

      // Handler for STATE_CHANGED event which makes sure checkbox status reflects the transliteration enabled or disabled status.

      function transliterateStateChangeHandler(e) {
        document.getElementById('checkboxId').checked = e.transliterationEnabled;
      }

      // Handler for checkbox's click event.  Calls toggleTransliteration to toggle the transliteration state.

      function checkboxClickHandler(ele_name,ele_name1) {
		
		var check_c=0;
		var arr =new Array;
		arr['checkboxId']='about_family';
		arr['checkboxId2']='about_yourself';
		arr['checkboxId3']='about_desired_partner';
		
		var ele_id=document.getElementById(ele_name);

		if(ele_id.checked)
		{
			check_c=1;
			document.getElementById(arr[ele_id.id]).focus();
			document.getElementById(arr[ele_id.id]).style.color='#000000';
		}
		else
		{
			check_c=0;
		}
		
		if(check_c==1)
		{
			document.getElementById('checkboxId').checked = true;
			document.getElementById('checkboxId2').checked = true;
			document.getElementById('checkboxId3').checked = true;
			
			document.getElementById('spellcheck1').style.display = 'none';
			document.getElementById('spellcheck2').style.display = 'none';
			document.getElementById('spellcheck3').style.display = 'none';
			
			if(document.styleSheets[6].deleteRule)
				document.styleSheets[6].deleteRule(0);
			else
				document.styleSheets[6].removeRule(0);
		}
		else
		{	
			if(document.styleSheets[6].insertRule)
				document.styleSheets[6].insertRule('.transTip{display:none;}',0);
			else
				document.styleSheets[6].addRule('.transTip','{display:none;}',0);

			document.getElementById('checkboxId').checked = false;
			document.getElementById('checkboxId2').checked = false;
			document.getElementById('checkboxId3').checked = false;

			document.getElementById('spellcheck1').style.display = 'inline';
			document.getElementById('spellcheck2').style.display = 'inline';
			document.getElementById('spellcheck3').style.display = 'inline';
		}
		
		if (document.getElementById('checkboxId').checked == true && document.getElementById('checkboxId2').checked == true && document.getElementById('checkboxId3').checked == true)
	  	{
			     document.getElementById('languageDropDown1').disabled = false;
		   	     document.getElementById('languageDropDown2').disabled = false;
	           	     document.getElementById('languageDropDown3').disabled = false;
	        }
	     
	     	if (document.getElementById('checkboxId').checked == false || document.getElementById('checkboxId2').checked == false || document.getElementById('checkboxId3').checked == false)
	     	{
			document.getElementById('languageDropDown1').disabled = true;
		        document.getElementById('languageDropDown2').disabled = true;
	                document.getElementById('languageDropDown3').disabled = true;
		}
	  	
		transliterationControl.toggleTransliteration();

      }

      // Handler for dropdown option change event.  Calls setLanguagePair to  set the new language.

      function languageChangeHandler(qq) {

	var  dropdown= document.getElementById(qq.title), txtarea , txtarea1 , txtarea2;
	
	txtarea = document.getElementById("about_family");
	txtarea1 = document.getElementById("about_yourself");
	txtarea2 = document.getElementById("about_desired_partner");
	
	var h = parseInt(txtarea.style.height),  w = parseInt(txtarea.style.width);
	var a = parseInt(txtarea1.style.height), b = parseInt(txtarea1.style.width);
	var c = parseInt(txtarea2.style.height), d = parseInt(txtarea2.style.width);
	var e = txtarea.style.fontFamily;
	var f = txtarea1.style.fontFamily;
	var g = txtarea2.style.fontFamily;
	var i = parseInt(txtarea.style.fontSize);
	var j = parseInt(txtarea1.style.fontSize);
	var k = parseInt(txtarea2.style.fontSize);

        transliterationControl.setLanguagePair(
            google.elements.transliteration.LanguageCode.ENGLISH,
            dropdown.options[dropdown.selectedIndex].value);
	   
	    if(h) txtarea.style.height = h + "px";
    	    if(w) txtarea.style.width = w + "px";

	    if(a) txtarea1.style.height = a + "px";
	    if(b) txtarea1.style.width = b + "px";

	    if(c) txtarea2.style.height = c + "px";
	    if(d) txtarea2.style.width = d + "px";

	    if(e) txtarea.style.fontFamily = 'arial';
	    if(f) txtarea1.style.fontFamily = 'arial';
	    if(g) txtarea2.style.fontFamily = 'arial';

	    if(i) txtarea.style.fontSize = 12 +"px";
	    if(j) txtarea1.style.fontSize = 12 +"px";
	    if(k) txtarea2.style.fontSize = 12 +"px";


	    if(qq.title=='languageDropDown1')
	    {
		document.getElementById('languageDropDown2').options.selectedIndex=dropdown.options.selectedIndex;
		document.getElementById('languageDropDown3').options.selectedIndex=dropdown.options.selectedIndex;
	    }
	    else if(qq.title == 'languageDropDown2')
	    {	
		document.getElementById('languageDropDown1').options.selectedIndex=dropdown.options.selectedIndex;
		document.getElementById('languageDropDown3').options.selectedIndex=dropdown.options.selectedIndex;
	    }
	    else if(qq.title == 'languageDropDown3')
	    {
		document.getElementById('languageDropDown2').options.selectedIndex=dropdown.options.selectedIndex;
		document.getElementById('languageDropDown1').options.selectedIndex=dropdown.options.selectedIndex;

	    }
      }

      // SERVER_UNREACHABLE event handler which displays the error message.
      function serverUnreachableHandler(e) {
        document.getElementById("errorDiv").innerHTML =
            "Transliteration Server is unreachable";
      }

      // SERVER_UNREACHABLE event handler which clears the error message.
      function serverReachableHandler(e) {
        document.getElementById("errorDiv").innerHTML = "";
      }
      google.setOnLoadCallback(onLoad);
