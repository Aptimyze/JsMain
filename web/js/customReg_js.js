function onRegSubmit(){
  if($('#reg').valid() == false)
    return false;
}
/*Function to trim specified characters*/
function trim (str, chars)
{
  return ltrim(rtrim(str, chars), chars);
}

/*Function to trim specified characters from left*/
function ltrim (str, chars)
{
  chars = chars || "\\s";
  return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

/*Function to trim specified characters from right*/
function rtrim (str, chars)
{
  chars = chars || "\\s";
  return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}