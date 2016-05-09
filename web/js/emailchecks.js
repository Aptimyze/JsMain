function check_email(email_act){
	var at="@";
	var dot=".";
	var lat=email_act.indexOf(at);
	var lstr=email_act.length;
	var ldot=email_act.indexOf(dot);
	var lastdot=email_act.lastIndexOf(dot);
	if((!(email_act.indexOf("@")!=-1 && email_act.indexOf(".")!=-1)) || CharsInBag(email_act)==false || (email_act.indexOf(at)==-1) || (email_act.indexOf(at)==-1 || email_act.indexOf(at)==0 || email_act.indexOf(at)==lstr) || (email_act.indexOf(dot)==-1 || email_act.indexOf(dot)==0 || email_act.indexOf(dot)==lstr || email_act.substring(lastdot+1)=="") || (email_act.indexOf(at,(lat+1))!=-1) || (email_act.substring(lat-1,lat)==dot || email_act.substring(lat+1,lat+2)==dot) || (email_act.indexOf(dot,(lat+2))==-1) || (email_act.indexOf(" ")!=-1))
			return false;
		return true;
}
function CharsInBag(s)
{
	var bugchars = '!#$^&*()+|}{[]?><-`%:;/,=~"\''; 
	var i;
	var lchar="";
	for (i = 0; i < s.length; i++)
	{
	var c = s.charAt(i);
		if(i>0)lchar=s.charAt(i-1);
	if (bugchars.indexOf(c) != -1 || (lchar=="." && c=="."))
		return false;
	 }
	 return true;
}

var hint_text = "e.g. raj1983, vicky1980 ";

/**** LIST OF BANNED WORDS ****/

var banned_words = ["no", "none", "messenger id", "messenger", "gmail", "facebook", "gmail.com", "yahoo", "no id", "google", "rediffmail","rediff", "na", "nil", "any", "good", "non", "yes", "later", "hello", "hindi", "orkut", "skype", "love", "airtel", "nothing", "face book", "i love you", "google talk"];


/********************************************************************************
**                                                                             **
**   Error Codes for messenger ID                                              **
**   0 => All things OK                                                        **
**   1 => Too few chars. Minimum Length of Messenger ID should be 4 characters **
**   2 => Contains Banned Words (Banned words are defined in a global array)   **
**   3 => Invalid Messenger ID (Patterns found to be invalid as per trac #756) **
**   4 => Messenger Channel not selected (only if messenger id is entered)     **
**                                                                             **
********************************************************************************/

// Defining Enum for above error codes

var MID_ERR_CODES = 
{
    OK                              : {value: "0", reason: "OKAY"},
    TOO_FEW_CHARS                   : {value: "1", reason: "Messenger ID should be atleast 4 <br />characters long."},
    BANNED_WORD                     : {value: "2", reason: "Word not allowed. Please provide a valid<br />messenger ID."},
    INVALID_MESSENGER_ID            : {value: "3", reason: "Please provide a valid messenger ID."},
    MESSENGER_CHANNEL_NOT_SELECTED  : {value: "4", reason: "Please select type of messenger - GTalk,<br />Yahoo ..etc."},
    AT_LEAST_ONE_CHAR_REQUIRED      : {value: "5", reason: "At least one alphabet should be present<br />in messenger ID"}
};


/* function to check the validity of messenger ID in terms of allowed chars and pattern.
** input: messenger ID passed by check_messenger_id
** output: one of the above error codes */

function is_messenger_id_valid(messenger_id)
{
    var valid_chars = /^[a-zA-Z0-9._\-]+$/;
    var only_chars = /^[a-zA-Z]+$/;
    if (valid_chars.test(messenger_id))
    {
        var lastchar = "";
        var char_count = 0;
        if (messenger_id.indexOf('.') == 0) {
            return MID_ERR_CODES.INVALID_MESSENGER_ID;
        }
        for (var i = 0; i < messenger_id.length; i++)
        {
            var c = messenger_id.charAt(i);
            if (only_chars.test(c)) {
                char_count += 1;
            }
            if (i > 0) lastchar = messenger_id.charAt(i - 1);
            if (c == "." && lastchar == ".") // messenger ID cannot have 2 consecutive dots.
            {
                return MID_ERR_CODES.INVALID_MESSENGER_ID;
            }
        } 
        if (char_count == 0) 
            return MID_ERR_CODES.AT_LEAST_ONE_CHAR_REQUIRED; 
        else 
            return MID_ERR_CODES.OK; // is a valid messenger ID
    } else {
        return MID_ERR_CODES.INVALID_MESSENGER_ID; // not valid
    }
    
}

/* function that gets called to check for the entered messenger ID
** input: the messenger ID entered by the user.
** output: one of the above error codes. */

function check_messenger_id(messenger_id)
{
    if (messenger_id == "" || messenger_id == hint_text) // messenger id is not entered. which means we don't have to validate on submit or otherwise.
    {
        return MID_ERR_CODES.OK;
    }

    if ($.inArray(messenger_id.toLowerCase(), banned_words) != -1) // Entered messenger ID is a banned word
    {
        return MID_ERR_CODES.BANNED_WORD;
    }

    if (messenger_id.indexOf("@") != -1) // "@" is present in messenger id. So stripping all characters after "@" inclusive.
    {
        messenger_id = messenger_id.split("@");
        messenger_id = messenger_id[0]; // taking messenger id prefix only
    }

    if (messenger_id.length < 4) // Messenger ID's length is less than 4 characters
    {
        return MID_ERR_CODES.TOO_FEW_CHARS;
    } else { // Check whether entered Messenger ID is valid (considering allowed special characters and patterns)
        var isValid = is_messenger_id_valid(messenger_id);
        return isValid;
    }
    

    return MID_ERR_CODES.OK; // Valid messenger ID
}

/* function to check whether messenger channel is selected if messenger ID is entered.
** input: messenger ID and messenger channel as entered by the user.
** output: one of the above error codes */

function is_messenger_channel_selected(messenger_id, messenger_channel)
{
        var result_code = check_messenger_id(messenger_id);
        if (result_code.value != "0")
            return result_code; // not a valid messenger ID
        else if (result_code.value == "0") {
            if (messenger_id == "" || messenger_id == "e.g. raj1983, vicky1980 ") 
                return MID_ERR_CODES.OK;
            else if (messenger_id && messenger_channel == '')
                return MID_ERR_CODES.MESSENGER_CHANNEL_NOT_SELECTED;
            else 
                return MID_ERR_CODES.OK; // messenger channel selected is none
        }
        else
            return MID_ERR_CODES.OK; // everything's fine
 }
