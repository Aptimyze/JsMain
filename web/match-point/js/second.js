function shower1()
{
        document.getElementById("a3").style.display="block"
        document.getElementById("a4").style.display="none"
        document.getElementById("d2").style.paddingBottom="38px"
        document.getElementById("maindiv2").style.display="block"
        document.getElementById("close2").style.display="block"
}
function shower2()
{
        document.getElementById("a1").style.display="block"
        document.getElementById("a2").style.display="none"
        document.getElementById("d1").style.paddingBottom="38px"
        document.getElementById("maindiv1").style.display="block"
        document.getElementById("close1").style.display="block"
}
function hider2()
{
        document.getElementById("a4").style.display="block"
        document.getElementById("a3").style.display="none"
        document.getElementById("d2").style.paddingBottom="0"
        document.getElementById("maindiv2").style.display="none"
        document.getElementById("close2").style.display="none"

}
function hider1()
{
        document.getElementById("a2").style.display="block"
        document.getElementById("a1").style.display="none"
        document.getElementById("d1").style.paddingBottom="0"
        document.getElementById("maindiv1").style.display="none"
        document.getElementById("close1").style.display="none"
	if(document.getElementById("u9").style.display=="block")
		ReverseContentDisplay('u9');
}

