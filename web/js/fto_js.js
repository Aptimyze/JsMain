$('[id^="fto_"]').bind("click",function(){
	
        showftoLoader(this.id,"EOI","fto");
});
function showftoLoader(id,tobestatus,maindiv)
{
	showLayer(id,tobestatus,maindiv,1);
	var idArr=id.split("_");
	profid=idArr[1];
	commonData[id]=postDataVar;
	onExpressInterest(profid);
}
function ftoExpress(id)
{
	showftoLoader(id,"EOI","fto");
}
function getFtoStyleLeft(id)
{
	var offsetLeft=0;
	var styleLeft="";
		offsetLeft=dID(id).offsetLeft-140;
		//alert(dID(id).offsetLeft+"HI"+offsetLeft);
		if(offsetLeft<=10)
			offsetLeft=1;
		else
		{
			offsetLeft+=400;
		//alert(offsetLeft+" "+screen.width);	
			if(offsetLeft>=(screen.width-50))
				offsetLeft=-200;
			else
				offsetLeft=0;
		}
		if(offsetLeft!=0)
		styleLeft='style="left:'+offsetLeft+'px"';
		return styleLeft;
}
