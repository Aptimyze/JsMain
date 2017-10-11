  // This function displays the ad results.
  // It must be defined above the script that calls show_ads.js
  // to guarantee that it is defined when show_ads.js makes the call-back.

  function break_words(word_length,middle_string,input_string)
  {
    var re = new RegExp("[^ ]{"+word_length+",}","g");
    if(!(str_ary = input_string.match(re)))return input_string;
    for(var i=0;i<str_ary.length;i++)
    {
      newstr = str_ary[i].substring(0,word_length)+middle_string+str_ary[i].substring(word_length,str_ary[i].length);
      input_string = input_string.replace(str_ary[i],newstr);
    }
    return input_string;
  }

  function google_ad_request_done(google_ads) {

    // Proceed only if we have ads to display!
    if (google_ads.length < 1 )
      return;

    var max_word_len=22;
    var break_char="<br>";

    // Display ads in a table
    document.write("<table cellSpacing=0 cellpadding=\"6\" width=\"170\" border=0 bgcolor=\"F1F5E1\" align=\"center\">");
 
    // Print "Ads By Google" -- include link to Google feedback page if available
    document.write("<tr bgcolor=\"DBE6BB\"><td height=22 class=mediumblack align=\"center\">");
    if (google_info.feedback_url) {
      document.write("<a href=\"" + google_info.feedback_url + 
        "\">Ads by Google</a>");
    } else {
      document.write("Ads By Google");
    }
    document.write("</td></tr>");  
  
    // For text ads, display each ad in turn.
    // In this example, each ad goes in a new row in the table.
    if (google_ads[0].type == 'text') {
      for(i = 0; i < google_ads.length; ++i) {
        document.write("<tr><td height=22 class=mediumblack><span class=class5>" +
	  "<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\"><b>" +
          break_words(max_word_len,break_char,google_ads[i].line1) + "</b></a></span><br><span class=class11>" +
	 "<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\">" +
          break_words(max_word_len,break_char,google_ads[i].line2) + "<br>" +
          break_words(max_word_len,break_char,google_ads[i].line3) + "</a></span><br><span class=class5>" + 
	 "<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\">" +
          break_words(max_word_len,break_char,google_ads[i].visible_url) +
          "</a></span><br></td></tr>"); 
      }
    }

    // For an image ad, display the image; there will be only one .
    if (google_ads[0].type == 'image') {
      document.write("<tr><td align=\"center\">" +
        "<a href=\"" + google_ads[0].url + "\">" +
        "<img src=\"" + google_ads[0].image_url + 
        "\" height=\"" + google_ads[0].height + 
        "\" width=\"" + google_ads[0].width +
        "\" border=\"0\"></a></td></tr>");
    }

    // Finish up anything that needs finishing up
    document.write ("</table>");
  }

         google_ad_client   = 'ca-jeevansathi_js';
         google_ad_output   = 'js';
         google_max_num_ads = '5';
         google_ad_type   = 'text_image';
         google_image_size   = '120x600';
	 google_color_line = "ff0000";
         google_language  = 'en';
         google_encoding  = 'latin1';
         google_safe      = 'high';
         google_adtest    = 'off';
	 google_ad_region = 'otherinfo';
