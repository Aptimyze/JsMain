function checkFlash() 
{
	var flashinstalled = 0;
        MSDetect = "false";
        if (navigator.plugins && navigator.plugins.length)
        {
                if (navigator.plugins["Shockwave Flash"] || navigator.plugins["Shockwave Flash 2.0"])
                {
                        flashinstalled = 2;
                }
                else
                {
                        flashinstalled = 1;
                }
        }
        else if (navigator.mimeTypes && navigator.mimeTypes.length)
        {
                x = navigator.mimeTypes['application/x-shockwave-flash'];
                if (x && x.enabledPlugin)
                {
                        flashinstalled = 2;
                }
                else
                {
                        flashinstalled = 1;
                }
        }
        else
        {
                MSDetect = "true";
        }

	var version = getFlashVersion().split(',').shift();

        if (flashinstalled == 1 || version < 9)
        {
                parent.location.href= "/social/compUploadNoFlash";
        }
}

function resizeFrame() 
{
 	var f = document.getElementById("myframe");
      	var height = parseInt(f.contentWindow.document.body.scrollHeight);
       	f.style.height = height+"px";
	f.style.visibility = "visible";
 	document.getElementById("iframe_loader").style.display = "none";
}

function getFlashVersion(){
  // ie
  try {
    try {
      // avoid fp6 minor version lookup issues
      // see: http://blog.deconcept.com/2006/01/11/getvariable-setvariable-crash-internet-explorer-flash-6/
      var axo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash.6');
      try { axo.AllowScriptAccess = 'always'; }
      catch(e) { return '6,0,0'; }
    } catch(e) {}
    return new ActiveXObject('ShockwaveFlash.ShockwaveFlash').GetVariable('$version').replace(/\D+/g, ',').match(/^,?(.+),?$/)[1];
  // other browsers
  } catch(e) {
    try {
      if(navigator.mimeTypes["application/x-shockwave-flash"].enabledPlugin){
        return (navigator.plugins["Shockwave Flash 2.0"] || navigator.plugins["Shockwave Flash"]).description.replace(/\D+/g, ",").match(/^,?(.+),?$/)[1];
      }
    } catch(e) {}
  }
  return '0,0,0';
}
