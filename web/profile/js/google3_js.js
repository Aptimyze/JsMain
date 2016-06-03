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
    document.write("<table cellSpacing=0 cellpadding=\"6\" width=\"140\" border=0 bgcolor=\"#FFF1D3\" align=\"center\">");
 
    // Print "Ads By Google" -- include link to Google feedback page if available
    document.write("<tr bgcolor=\"#FAB98C\"><td height=22 class=mediumblack align=\"center\">");
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
        document.write("<tr><td height=22 class=mediumblack><span class=blacklinku>" +
	  "<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\"><b>" +
          break_words(max_word_len,break_char,google_ads[i].line1) + "</b></a></span><br><span class=blacklink>" +
	 "<a href=\"" +  google_ads[i].url + "\" target=\"_new\" onMouseover=\"window.status='" + google_ads[i].visible_url + "'; return true\" onMouseout=\"window.status=''; return true\">" +
          break_words(max_word_len,break_char,google_ads[i].line2) + "<br>" +
          break_words(max_word_len,break_char,google_ads[i].line3) + "</a></span><br><span class=blacklinku>" + 
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
    document.write ("<tr><td class=bgbrownL3>&nbsp;</td></tr></table>");
  }

	var names=new Array('wedding coordinating','wedding directories','wedding guides','wedding matches','wedding resources','wedding services','wedding service','wedding sites','wedding websites','wedding planning','wedding advice','Muslimwedding','wedding agency','wedding bureau','wedding information','women for marriage','marriage bureau','marriage advice','marriage agencies','asian marriage bureau','classifieds personal','marriage bureau','delhi marriage bureau','gujarati marriage bureau','hyderabad marriage bureau','india marriage bureau','indian marriage bureau','indian marriage bureau uk','islamic marriage bureau','kerala marriage bureau','las vegas marriage bureau','las vegas marriage license bureau','late marriage','marathi marriage bureau','marriage agency','marriage bureau bangalore','marriage bureau delhi','marriage bureau hyderabad','marriage bureau in bangalore','marriage bureau in delhi','marriage bureau in hyderabad','marriage bureau in india','marriage bureau in mumbai','marriage bureau in pakistan','marriage bureau in pune','marriage bureau india','marriage bureau mumbai','marriage bureau pune','marriage bureau russia','marriage bureau sites','marriage bureau uk','marriage bureau washington dc','marriage information','marriage license bureau','Nikaah','bridal resources','bride websites','bridal search','bride search','couples','love matches','manglik','meet people','meeting people','nikah','personal ad','personal adds','personal ads','personals','photo personals','weddinghub','contact brides','contact grooms','happy marriage','Mumbai boys','Mumbai girls','samaj','seeking');
	
	var ranNum= Math.round(Math.random()*77);

	 google_ad_client   = 'ca-jeevansathi-site_js';
         google_ad_output   = 'js';
         google_max_num_ads = '5';
         google_ad_type   = 'text_image';
         google_image_size   = '120x600';
         google_ad_format = '120x600_pas_abgnc';
	 google_color_line = "ff0000";
         google_language  = 'en';
         google_encoding  = 'latin1';
         google_safe      = 'high';
         google_adtest    = 'off';
	 //google_ad_region = 'otherinfo';
	 //google_kw = names[ranNum];
	 google_kw = 'googletestadimage';
         google_kw_type = 'exact';
	 google_page_url = 'http://www.jeevansathi.com';
	//alert(names[ranNum]);
