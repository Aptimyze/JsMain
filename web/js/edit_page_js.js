      // Load the Google Transliteration API
	    google.load("elements", "1", {
          packages: "transliteration"
          });
function onLoad() {
        var options = {
            sourceLanguage:
                google.elements.transliteration.LanguageCode.ENGLISH,
            destinationLanguage:
                google.elements.transliteration.LanguageCode.HINDI,
           // transliterationEnabled: true,
            shortcutKey: 'ctrl+a' // Disable the ShortCut Keys
        };
	if(document.getElementById('checkboxId2'))			
	{
		if (document.getElementById('checkboxId').checked == false || document.getElementById('checkboxId2').checked == false)
		{
			document.getElementById('languageDropDown1').disabled = true;
			document.getElementById('languageDropDown2').disabled = true;
		}
	}
	else
	{	
		 if (document.getElementById('checkboxId').checked == false)
			document.getElementById('languageDropDown4').disabled = true;
	}
        // Create an instance on TransliterationControl with the required
        // options.
        transliterationControl =
          new google.elements.transliteration.TransliterationControl(options);
		transliterationControl.toggleTransliteration();
        // Enable transliteration in the textfields with the given ids.
	if(document.getElementById("checkboxId2"))
		var ids = ["about_myself","about_family"];
	else
	        var ids = ["about_dpp"];
        transliterationControl.makeTransliteratable(ids);

        // Add the STATE_CHANGED event handler to correcly maintain the state
        // of the checkbox.
        transliterationControl.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.STATE_CHANGED,
            transliterateStateChangeHandler);

        // Add the SERVER_UNREACHABLE event handler to display an error message
        // if unable to reach the server.
        transliterationControl.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.SERVER_UNREACHABLE,
            serverUnreachableHandler);

        // Add the SERVER_REACHABLE event handler to remove the error message
        // once the server becomes reachable.
        transliterationControl.addEventListener(
            google.elements.transliteration.TransliterationControl.EventType.SERVER_REACHABLE,
            serverReachableHandler);

        // Set the checkbox to the correct state.
        /*document.getElementById('checkboxId').checked =
          transliterationControl.isTransliterationEnabled();

	document.getElementById('checkboxId2').checked =
          transliterationControl.isTransliterationEnabled();

   	document.getElementById('checkboxId3').checked =
          transliterationControl.isTransliterationEnabled(); */
	if(document.getElementById("checkboxId2"))
	{
		var mydiv = document.getElementById("about_myself");
        	var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
        	mydiv.style.width = (curr_width + 420) +"px";	
	        var curr_height = parseInt(mydiv.style.height); // removes the "px" at the end
        	mydiv.style.height = (curr_height + 100) +"px";
		var mydiv = document.getElementById("about_family");
        	var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
        	mydiv.style.width = (curr_width + 420) +"px";
		var curr_height = parseInt(mydiv.style.height); // removes the "px" at the end
        	mydiv.style.height = (curr_height + 100) +"px";
	}
	else
	{
		var mydiv = document.getElementById("about_dpp");
                var curr_width = parseInt(mydiv.style.width); // removes the "px" at the end
                mydiv.style.width = (curr_width + 420) +"px";   
                var curr_height = parseInt(mydiv.style.height); // removes the "px" at the end
                mydiv.style.height = (curr_height + 100) +"px";
	}
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
      }
function ismaxlength(obj){
var mlength=obj.getAttribute? parseInt(obj.getAttribute("maxlength")) : ""
if (obj.getAttribute && obj.value.length>mlength)
obj.value=obj.value.substring(0,mlength)
}
