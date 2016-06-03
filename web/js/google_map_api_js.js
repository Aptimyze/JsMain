function HideContent(d) {
if(d.length < 1) { return; }
document.getElementById(d).style.display = "none";
}
function ShowContent(d) {
if(d.length < 1) { return; }
document.getElementById(d).style.display = "block";
}
function ReverseContentDisplay(d,latitude,longitude) 
{
	if(d.length < 1) { return; }
	if(document.getElementById(d).style.display == "none") 
	{ 
		document.getElementById("g_latitude").value = latitude;
		document.getElementById("g_longitude").value = longitude;		
		initLoader();
		document.getElementById(d).style.display = "block"; 
	}
	else 
	{
		document.getElementById("right_arrow").style.display ="none";
		document.getElementById("left_arrow").style.display ="none"; 
		document.getElementById("top_arrow").style.display='none';
		document.getElementById("bottom_arrow").style.display='none';
		document.getElementById(d).style.display = "none"; 
	}
}

function listener( e,top )
{
	var mousex=0;
	var mousey=0;
	var docY=0;

	document.getElementById("right_arrow").style.display ="none";
	document.getElementById("left_arrow").style.display ="none";
	if (!e) var e = window.event;
	mousex=e.clientX; //to get client window X axis
	virtual_top=e.clientY;
	if(document.documentElement.scrollTop)
	{
		docY += document.documentElement.scrollTop;
	}
        else if( document.body && ( document.body.scrollTop ) )
        {
        	docY += document.body.scrollTop;
        }
	mousey=e.clientY+docY;//to get client window Y axis
	if(virtual_top>230)
	{
	        if(top=='l')
	        {
			document.getElementById("left_arrow").style.display ="block";
               		top_pos= mousey-120; //e.clientY-120;
               		left_position=e.clientX+50;
       		}
       		else if(top=='r')
       		{
			document.getElementById("right_arrow").style.display ="block";
       		        top_pos=mousey-120 ;//e.clientY-120;
               		left_position=e.clientX-600; 
       		}
		else
		{
			document.getElementById("bottom_arrow").style.display ="block";
       			top_pos=mousey-280;
       			left_position=mousex-320;
		}
       	}
       	else
       	{
		document.getElementById("top_arrow").style.display ="block";
       		top_pos=mousey+30;
		if(e.clientX>650)
			left_position=mousex-400;
		else
       			left_position=mousex-320;
       	}
        top_position=top_pos+"px";
        left_position=left_position+"px";
	document.getElementById("u9").style.top=top_position;
	document.getElementById("u9").style.left= left_position
	
}
function point_it(event,top)
{
	listener(event,top);
}

function mapsLoaded() {
	loadM(); 
}
function loadMaps(lat,lng) {
	google.load("maps", "2", {"callback" : mapsLoaded});
}
function initLoader() {
	var script = document.createElement("script");
	script.src = document.getElementById("google_api_key").value; 
	script.type = "text/javascript";
	document.getElementsByTagName("head")[0].appendChild(script);
}
function loadM() 
{
	var latitude  = document.getElementById("g_latitude").value;
	var longitude = document.getElementById("g_longitude").value;
	if(latitude =="")
		latitude="28.588884";
	if(longitude =="")
		longitude = "77.322392";
	if (GBrowserIsCompatible()) 
	{
		var crossLayer = new GTileLayer(new GCopyrightCollection(""), 0, 15);
		crossLayer.getTileUrl =  function(tile, zoom) 
		{
		  	return "./include/tile_crosshairs.png";
		}
		crossLayer.isPng = function() {return true;}
		// Setting the location position specified by the lat&long
		var map = new GMap2(document.getElementById("map"), 
		{ size: new GSize(357,214) } );
                map.removeMapType(G_PHYSICAL_MAP);
                map.removeMapType(G_HYBRID_MAP);
		map.removeMapType(G_SATELLITE_MAP);
		map.setCenter(new GLatLng(latitude, longitude), 13);
		// Placing Marker to the place specified by lat&long variables
		var center = new GLatLng(latitude, longitude, 13);
		var marker = new GMarker(center, {draggable: false});
		map.addOverlay(marker);
		var mapControl = new GHierarchicalMapTypeControl();
		// GSmallMapControl used to add the small zoom level on top left corner
		map.addControl(new GSmallMapControl()); 	
		// Set up map type menu relationships
		mapControl.clearRelationships();
		// Add control after you've specified the relationships
		map.addControl(mapControl);
	}
}
