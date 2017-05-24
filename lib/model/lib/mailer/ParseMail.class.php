<?php
/**
 * Page level block
 *
 * @File ParseMail.class.php
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 * @created Fri Jul 27 15:47:04 IST 2012
 * @modified Wed Aug 29 17:35:11 IST 2012
 */

/**
 * Page class description starts here
 *
 * <p>
 * This class is used to replace tags in html template with their actual values
 * </p>
 */

/**
 * This class will parse and replace Variables in mail template
 * 
 * <p>
 * This class reads the html buffer supplied or the html file supplied,
 * identifies the various various variables in the mail template
 * and appropriately replaces them with the values found for that particular
 * profile. For testing purposes, thi can be initialized in the following manner
 * </p>
 * <code> 
 * $obj = new Page(); 
 * </code>
 * </p>
 * @include ParseMessageTemplateVariables.class.php
 **/

class ParseMail {

  /**
   * This holds the HTML template
   * 
   * @access private
   * @var string
   */
  private $_buffer;

  /**
   * This holds the variables(tags) found in the template
   * 
   * @access private
   * @var array
   */
  private $_tags;

  /**
   * This has the instance of ParseMessageTemplateVariables class
   * 
   * @access private
   * @var ParseMessageTemplateVariables 
   */
  private $_parse_message_var;

  /**
   * This keeps count of number of tags(variables) found so far
   * 
   * @access private
   * @var int
   */
  private $_tag_count;

  /**
   * This variable holds the HTML DOM object for HTML parsing
   *
   * @access private
   * @var DOMDocument
   */
  private $_html;

  /**
   * This variable holds the text status for the buffer supplied
   *
   * @access private
   * @var string
   */
  private $_is_text;

  /**
   * Constructor for Page class
   *
   * <p>
   * Creates an object for Page class
   * </p>
   * @access public
   */
  function __construct() {
    $this->_tags = null;
    $this->_parse_message_var = /*/0/*/new ParseMessageTemplateVariables()/**/;
    $this->_buffer = null;
    $this->_tag_count = 0;
    $this->_html = new DOMDocument;
  }

  /**
   * Loads an HTML file in $_buffer in string format
   *
   * <p>
   * When an HTML file is specified to replace tags, this function will be called.
   * </p>
   * @access private
   * @param $file  File name.
   * @throws BadFileNameException.
   */
  private function _loadHTMLFile($file) {
    if(file_exists($file)) {
      ob_start();
      include($file);
      $this->_buffer = ob_get_contents();
      ob_end_clean();
    } else {
      throw new BadFileNameException('Bad file name');
    }
  }

  /**
   * Loads HTML string in Page class's private buffer
   *
   * <p>
   * This function is called if the HTML is provided in the form of a string
   * </p>
   * @access private
   * @param $buffer The String buffer which contains the HTML code.
   * @throws NullStringBufferException
   */
  private function _loadHTMLBuffer($buffer) {
    if (isset($buffer)) {
      $this->_buffer = $buffer;
    } else {
      throw new NullStringBufferException('No HTML present in buffer');
    }
  }

  /**
   * Loads Text String in Page class's private buffer
   *
   * <p>
   * This function is called if input is in plain text format
   * </p>
   * @access private
   * @param $buffer The plain text
   * @throws NullStringBufferException
   */
  private function _loadTextBuffer($buffer) {
    if (isset($buffer)) {
      $this->_is_text = true;
      $this->_buffer = $buffer;
    } 
    else {
      throw new NullStringBufferException('No plain text present in buffer');  
    }
  }

  /**
   * This function is an interface to types of load buffers
   *
   * <p>
   * This function is called to load the html in the Page class's buffer
   * regardless of input. If a file is provided as input, then {@link Page#_loadHTMLFile()}
   * will be called and if a string buffer is provided as input, then {@link Page#_loadHTMLBuffer()}
   * will be called.
   * </p>
   * @access public
   * @param $input
   * <br />The input supplied to this function. Can be either
   * <ol>
   * <li>A File</li>
   * <li>A String</li>
   * </ol>
   */
  public function load($input, $is_text = false) {
    if (!$is_text) {
      if (is_file($input)) {
        $this->_loadHTMLFile($input);
      } else {
        $this->_loadHTMLBuffer($input);
      }
    }
    else {
      $this->_loadTextBuffer($input);
    }
  }

  /**
   * Validates tag format
   *
   * <p>
   * This function validates the tag format for received token.
   * </p>
   * @access private
   * @param $tag 
   * <br />The tag to be validated.
   * @throws InvalidProfileIdException
   * @throws UnqualifiedVariableNameException
   * @return bool
   */
  private function _validateTagFormat($tag) {
    $tokens = explode(":", $tag);
    if (preg_match("/(\w+?)/i", $tokens[0])) {
      return true;
    } else { //Not a valid Variable Name
      throw new UnqualifiedVariableNameException("Unqaulified Variable Name. '" . $tokens[0] . "' in tag '" . $tag . "'");
    }
    return false;
  }

  /**
   * Get token from custom tags present in html
   *
   * <p>
   * This function extracts the tokens from custom tags present in html
   * </p>
   * @access private
   * @return $token the token extracted
   * @throws HTMLCustomTagException
   */
  private function _getTokenForCustomDOMElements($custom_tag) {
    $token = "";
	if(strpos($custom_tag, ")"))
	{
		$pos_start = strpos($custom_tag, ")");
		$pos_end = strrpos($custom_tag, "(");
	}
	else
	if(strpos($custom_tag, ">"))
	{
		$pos_start = strpos($custom_tag, ">");
		$pos_start += 2;
		$pos_end = strrpos($custom_tag, "<");
		$pos_end -= 2;
	}
    if ($pos_end === 0) {
      throw new HTMLCustomTagException('Did you forget to close the ' . $custom_tag . ' tag?');
    }

    $start = $pos_start + 1;
    $end = $pos_end - $start;

    $token = substr($custom_tag, $start, $end);

    return $token;
  }

  /**
   * Replace HTML Link ,i.e, href tags for variable replacement
   *
   * <p>
   * This function identifies all the anchor tags with href attribute present
   * Parses them and replace tags with actual link values.
   * </p>
   * 
   * @access private
   * @throws HTMLCustomTagException
   * @throws InvalidTagFoundException
   */
  private function _replaceHTMLLinkTags($mail_group="") {

    //Load Html buffer
    @$this->_html->loadHTML($this->_buffer);

    //Get all anchor Dom elements
    $links = $this->_html->getElementsByTagName('a');
    foreach ($links as $link) {

      $href = $link->getAttribute('href');
      //Get Attribute corresponding to href
		if (stripos($href, '<var>') !== false){
			
			 $link_tag = $this->_getTokenForCustomDOMElements($href);
			 if ($this->_validateTagFormat($link_tag)) {
				$link_tag_value = trim($this->_parse_message_var->parseToken($link_tag,$mail_group), " \n\t\r");
			}
			else if (stripos($href, 'var') !== false) {
			throw new HTMLCustomTagException('Please check the custom tag format for links in the docs. The tag received is ' . $href);
			}
			else {
			throw new InvalidTagFoundException("Tag could not be validated. '" . $link_tag . "'");
			}
			//Remove href attribute
			$link->removeAttribute('href');

			//Set href attribute wiht calculated value
			if($link_tag_value=="photos@jeevansathi.com")
				$link->setAttribute('href', "mailto:".$link_tag_value);
			else
				$link->setAttribute('href', $link_tag_value);
		}
		 //Get Attribute corresponding to href
		elseif (stripos($href, '(LINK)') !== false) {

			//Parse href attribute value
			$link_tag = $this->_getTokenForCustomDOMElements($href);

			//Validate tag Format
			if ($this->_validateTagFormat($link_tag)) {
			$link_tag_value = trim($this->_parse_message_var->parseToken($link_tag,$mail_group), " \n\t\r");
			}
        
			else if (stripos($href, 'LINK') !== false) {
			throw new HTMLCustomTagException('Please check the custom tag format for links in the docs. The tag received is ' . $href);
			}
			else {
			throw new InvalidTagFoundException("Tag could not be validated. '" . $link_tag . "'");
			}

			//Remove href attribute
			$link->removeAttribute('href');

			//Set href attribute wiht calculated value
			$link->setAttribute('href', $link_tag_value);
      }
    }

    //Save HTML
    $this->_buffer = $this->_html->saveHTML();
  }

  /**
   * Replace <var> tag in the HTML buffer
   *
   * <p>
   * Replaces all <var> tags found in HTML with their corresponding calculated Value
   * </p>
   * @access private
   * @throws InvalidTagFoundException
   */
  private function _replaceHTMLVarTags($mail_group) {

    //Load HTML buffer
    $this->_html->loadHTML($this->_buffer);

    //Extract all <var> tags as DOM objects
    $elements = $this->_html->getElementsByTagName('var');
    foreach ($elements as $element) {

      //Tokenize nodeValue around {{ and }}
      $tag = strtok($element->nodeValue, "{{}}");

      //Validate Tag format
      if ($this->_validateTagFormat($tag)) {
//		  echo $this->_parse_message_var->parseToken($tag);
        $element->nodeValue = trim($this->_parse_message_var->parseToken($tag,$mail_group), " \n\t\r");
      }
      else {
        throw new InvalidTagFoundException("Tag could not be validated. '" . $tag . "'");
      }
    }

    //Save HTML
    $this->_buffer = $this->_html->saveHTML();

    //Replace <var> and </var> tags with empty string
	$to_replace=array("<var>","</var>","&lt;","&gt;");
	$replace_with=array("","","<",">");
    $this->_buffer = str_replace($to_replace, $replace_with, $this->_buffer);
  }

  /**
   * Replace Custom Photo tag
   *
   * <p>
   * This function identifies all the custom photo tags in the html
   * Parses them and replace tags with actual link values.
   * </p>
   * 
   * @access private
   * @throws HTMLCustomTagException
   * @throws InvalidTagFoundException
   */
  private function _replaceHTMLPhotoTags() {

    $this->_html->loadHTML($this->_buffer);

    $images = $this->_html->getElementsByTagName('img');
    foreach($images as $image) {

      $src = $image->getAttribute('src');

      if (stripos($src, "(PHOTO)") !== false) {

        $photo_tag = $this->_getTokenForCustomDOMElements($src);

        if ($this->_validateTagFormat($photo_tag)) {
         	$photo_tag_value = trim($this->_parse_message_var->parseToken($photo_tag), " \n\t\r"); 
        }
        else if (stripos($src, 'PHOTO') !== false) {
          throw new HTMLCustomTagException('Please check the custom tag format for photos in the docs. The tag received is ' . $src);
        }
        else {
          throw new InvalidTagFoundException("Tag could not be validated. '" . $photo_tag . "'");
        }

        $image->removeAttribute('src');
        $image->setAttribute('src', $photo_tag_value);
      }
      $this->_buffer = $this->_html->saveHTML();
    }
  }

  /**
   * Replaces tags in the plain text buffer with actual values
   *
   * <p>
   * This function replaces various tags that are present in plain text buffer
   * </p>
   *
   * @access private
   * @throws InvalidTagFoundException 
   */
  private function _replaceTextTags() {
    
    // Get all tokens
    preg_match_all('/<var>{{\w+:\w+=\d+}}<\/var>/', $this->_buffer, $tokens);

    // For all the tags found.
    for ($i = 0; $i < count($tokens[0]); ++$i) {
      $token = $tokens[0][$i];
      $start = strpos($token, '{{');
      $end = strpos($token, '}}');
      $end = $end - 2 - $start;
      $text_tag = substr($token, $start + 2, $end);
      if ($this->_validateTagFormat($text_tag)) {
        $text_tag_val = trim($this->_parse_message_var->parseToken($text_tag), " \n\t\r");
        $this->_buffer = str_replace($tokens[0][$i], $text_tag_val, $this->_buffer);
      }
      else {
        throw new InvalidTagFoundException("Tag could not be validated. '" . $text_tag . "'");
      }
    }
  }

  /**
   * Replaces tags in the buffer with actual values
   *
   * <p>
   * This function replaces tags in the buffer with the actual values
   * </p>
   * @access public
   * @return $this->_buffer
   * <br />The buffer with replaced values.
   */
  public function replaceTags($mail_group="") {

    // A problem is occuring with custom tokens. Symfony is escaping for html special chars. That results in poor tag formation 
    // Resolution: Changed the custom tag format to () instead of angular brackets.
    if (!$this->_is_text) {
      $this->_replaceHTMLLinkTags($mail_group);

      $this->_replaceHTMLPhotoTags();

      $this->_replaceHTMLVarTags($mail_group);
    }
    else {
      $this->_replaceTextTags();
    }
    return $this->_buffer;
  }
}
/*
$obj = new ParseMail();
$obj->load("Mr. <var>{{USERNAME:profileid=3188023}}</var> is <var>{{GENDER:profileid=3188023}}</var> <a href='<var>{{MTONGUE:profileid=3188023}}</var>' onclick='jsclick()'></a>", true);
echo $obj->replaceTags();
*/
