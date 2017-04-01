<?php
/**
 * Return one <script> tag for all javascripts configured in view.yml or added to the response object.
 *
 * You can use this helper to decide the location of javascripts in pages.
 * By default, if you don't call this helper, symfony will automatically include javascripts before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <script> tag
 */
include_once (sfConfig::get("sf_web_dir")."/profile/commonfile_functions.php");
function minify_get_javascripts($response, $minify, $placement, $async=false)
{
  if(!$minify) return get_javascripts();

  sfConfig::set('symfony.asset.javascripts_included', true);

  $already_seen = array();
  $minify_files = array();
  foreach ($response->getPositions() as $position)
  {
    foreach ($response->getJavascripts($position, $placement) as $files => $options)
    {
      if (!is_array($files))
      {
        $files = array($files);
      }

      $options = array_merge(array('type' => 'text/javascript'));
      if($async) {
        $options['async'] = 'true';
      }
      foreach ($files as $key=>$file)
      {
        if (isset($already_seen[$file])) continue;
	if(strstr($file,'http'))  continue;

        $already_seen[$file] = 1;
	if($placement == "bottom")
	{
		if(strstr($file,"@bottom")) $file = substr($file,0,-7);
		else continue;
	}
	elseif($placement=="commonTop")
	{
		if(strstr($file,"@commonTop")) 
			$file = substr($file,0,-10);
		else 
			continue;
	}
	elseif($placement=="commonBottom")
	{
		if(strstr($file,"@commonBottom")) 
			$file = substr($file,0,-13);
		else 
			continue;
	}
	else
	{
		if(strstr($file,"@bottom")) 
			continue;
		elseif(strstr($file,"@commonTop")) 
			continue;
		elseif(strstr($file,"@commonBottom")) 
			continue;
		else 
			$file = $file;
	}
	$file = getJavascriptFileName($file);
	if(!$file)
		throw new jsException("","$files[$key] javascript is not present in commonfile");
	$file = javascript_path($file);
        $type = serialize($options);

        if(isset($minify_files[$type]))
        {
          array_push($minify_files[$type], $file);
        }
        else
        {
          $minify_files[$type] = array($file);
        }
      }
    }
  }
  $html = '';
  foreach($minify_files as $options => $files)
  {
    $options = unserialize($options);
    $options['src'] = sfConfig::get("app_img_url")."/min/?f=".join($files, ',');
    $html   .= content_tag('script', '', $options)."\n";
  }

  return $html;
}

/**
 * Print <script> tag for all javascripts configured in view.yml or added to the response object.
 *
 * @param placement top or bottom wherever you need to include javascripts, Added by Tanu
 * @see minify_get_javascripts()
 */
function minify_include_javascripts($placement="",$async=false)
{
	$response = sfContext::getInstance()->getResponse();
	$minify = true;
	echo minify_get_javascripts($response, $minify, $placement, $async);
}


/**
 * Return one <link> tag for all stylesheets configured in view.yml or added to the response object.
 *
 * You can use this helper to decide the location of stylesheets in pages.
 * By default, if you don't call this helper, symfony will automatically include stylesheets before </head>.
 * Calling this helper disables this behavior.
 *
 * @return string <link> tags
 */
function minify_get_stylesheets($response, $minify ,$placement='')
{
  if(!$minify) return get_stylesheets();

  sfConfig::set('symfony.asset.stylesheets_included', true);

  $already_seen = array();
  $minify_files = array();
  foreach ($response->getPositions() as $position)
  {
    foreach ($response->getStylesheets($position) as $files => $options)
    {
      if (!is_array($files))
      {
        $files = array($files);
      }

      $options = array_merge(array('rel' => 'stylesheet', 'type' => 'text/css'), $options);
      foreach ($files as $key=>$file)
      {
        if (isset($already_seen[$file])) continue;

        $already_seen[$file] = 1;

        if($placement == "common")
	{
                if(strstr($file,"@common")) 
			$file = substr($file,0,-7);
                else 
			continue;
        }
        else
	{
                if(strstr($file,"@common")) 
			continue;
                else 
			$file = $file;
        }

        $absolute = false;
        if (isset($options['absolute']))
        {
          unset($options['absolute']);
          $absolute = true;
        }

        if(!isset($options['raw_name']))
        {
          $file = getCssFileName($file);
       	  if(!$file)
		throw new jsException("","$files[$key] css is not present in commonfile");
          $file = stylesheet_path($file, $absolute);
        }
        else
        {
          unset($options['raw_name']);
        }

        $type = serialize($options);

        if(isset($minify_files[$type]))
        {
          array_push($minify_files[$type], $file);
        }
        else
        {
          $minify_files[$type] = array($file);
        }
      }
    }
  }

  $html = '';
  foreach($minify_files as $options => $files)
  {
    $options = unserialize($options);
    $options['href'] = sfConfig::get("app_img_url")."/min/?f=".join($files, ',');
    $html .= tag('link', $options)."\n";
  }
  return $html;
}

/**
 * Print <link> tag for all stylesheets configured in view.yml or added to the response object.
 *
 * @see minify_get_stylesheets()
 */
function minify_include_stylesheets($placement="")
{
	$response = sfContext::getInstance()->getResponse();
	$minify = true;
  	echo minify_get_stylesheets($response, $minify, $placement);
}
function minify_get_mobile($which="js",$placement="",$newMobileSite="",$async = false)
{
	$config = sfYaml::load ( sfConfig::get ( 'sf_app_dir' ) .'/config/mobview.yml' );
	
	if($newMobileSite){
		if($which=="js")
		{
				
				$arr=$config["jsmsMobLayout"][javascripts];
				$options = array_merge(array('type' => 'text/javascript'));
                if($async) {
                    $options['async'] = 'true';
                }
		}
		else 
		{
					$options = array('rel' => 'stylesheet', 'type' => 'text/css');
					$arr=$config["jsmsMobLayout"][stylesheets];
					
		}
	}
	else{
		
		if($which=="js")
		{
				
				$arr=$config["default"][javascripts];
				$options = array_merge(array('type' => 'text/javascript'));
		}
		else 
		{
					$options = array('rel' => 'stylesheet', 'type' => 'text/css');
					$arr=$config["default"][stylesheets];
					
		}
	}
	$type = serialize($options);
	foreach($arr as $key=>$val)
	{
		$file="";
		if($which=="js")
		{
			$file=getJavascriptFileName($val);
			if(!$file)
        throw new jsException("","$val js  is not present in commonfile");
      $file=javascript_path($file);
    }
    else
    {
			$file=getCssFileName($val);
			if(!$file)
        throw new jsException("","$val css  is not present in commonfile");
      $file=stylesheet_path($file);
		} 

		
		if(isset($minify_files[$type]))
		{
			array_push($minify_files[$type], $file);
		}
		else
		{
			$minify_files[$type] = array($file);
		}
	}
	if($minify_files)
	{
			$html = '';
			if(is_array($minify_files)){
			foreach($minify_files as $options => $files)
			{
					$options = unserialize($options);
					
					if($which=="js")
					{
						$options['src'] = sfConfig::get("app_img_url")."/min/?f=".join($files, ',');
						$html   .= content_tag('script', '', $options)."\n";
					}			
					else
					{
						$options['href'] = sfConfig::get("app_img_url")."/min/?f=".join($files, ',');
						$html .= tag('link', $options)."\n";		
					}			
			}

		}
	}
	
	return $html;
}
/**
 * Return one <script> tag for all javascripts configured in module level view.yml. It ignores application level default javascripts.
 *
 * You can use this helper to decide the location of javascripts in pages.
 *
 * @param placement top or bottom wherever you need to include javascripts
 * @return string <script> tag
 * @author Tanu Gupta
 */
function minify_get_module_javascripts($placement=""){
	$context = sfContext::getInstance();
	$module = $context->getModuleName();
	$actionTemplate = $context->getActionName()."Success";
	$config = sfYaml::load ( sfConfig::get ( 'sf_app_dir' ) .'/modules/'.$module.'/config/view.yml' );
	foreach($config as $template=>$properties){
		if($template == $actionTemplate){
		foreach($properties as $property=>$files){
			if($property == 'javascripts'){
			      if (!is_array($files))
			      {
				$files = array($files);
			      }
			      $options = array_merge(array('type' => 'text/javascript'));
			      foreach ($files as $key=>$file)
			      {
				if (isset($already_seen[$file])) continue;
				if(strstr($file,'http'))  continue;

				$already_seen[$file] = 1;
				if($placement == "bottom"){
					if(strstr($file,"@bottom")) $file = substr($file,0,-7);
					else continue;
				}
				else{
					if(strstr($file,"@bottom")) continue;
					else $file = $file;
				}
				$file = getJavascriptFileName($file);
				if(!$file)
					throw new jsException("","$files[$key] javascript is not present in commonfile");
				$file = javascript_path($file);
				$type = serialize($options);

				if(isset($minify_files[$type]))
				{
				  array_push($minify_files[$type], $file);
				}
				else
				{
				  $minify_files[$type] = array($file);
				}
			      }
			}
		}
	  }
	}
  $html = '';
  if(is_array($minify_files)){
  foreach($minify_files as $options => $files)
  {
    $options = unserialize($options);
    $options['src'] = sfConfig::get("app_img_url")."/min/?f=".join($files, ',');
    $html   .= content_tag('script', '', $options)."\n";
  }
  }
  return $html;
}

/**
 * Print <script> tag for all javascripts configured in module level view.yml. It ignores application level default javascripts.
 *
 * @param placement top or bottom wherever you need to include javascripts
 * @see minify_get_module_javascripts()
 * @author Tanu Gupta
 */
function minify_include_module_javascripts($placement="")
{
        echo minify_get_module_javascripts($placement);
}

/**
 * Return one <link> tag for all stylesheets configured in module level view.yml. It ignores application level default stylesheets
 *
 * You can use this helper to decide the location of stylesheets in pages.
 *
 * @return string <link> tags
 *
 * @author Tanu Gupta
 */
function minify_get_module_stylesheets(){
        $context = sfContext::getInstance();
        $module = $context->getModuleName();
        $actionTemplate = $context->getActionName()."Success";
        $config = sfYaml::load ( sfConfig::get ( 'sf_app_dir' ) .'/modules/'.$module.'/config/view.yml' );
        foreach($config as $template=>$properties){
                if($template == $actionTemplate){
                foreach($properties as $property=>$files){
                        if($property == 'stylesheets'){
			      if (!is_array($files))
			      {
				$files = array($files);
			      }

			      $options = array('rel' => 'stylesheet', 'type' => 'text/css');
			      foreach ($files as $key=>$file)
			      {
				if (isset($already_seen[$file])) continue;

				$already_seen[$file] = 1;

				$absolute = false;
				if (isset($options['absolute']))
				{
				  unset($options['absolute']);
				  $absolute = true;
				}

				if(!isset($options['raw_name']))
				{
				  $file = getCssFileName($file);
				  if(!$file)
					throw new jsException("","$files[$key] css is not present in commonfile");
				  $file = stylesheet_path($file, $absolute);
				}
				else
				{
				  unset($options['raw_name']);
				}

				$type = serialize($options);

				if(isset($minify_files[$type]))
				{
				  array_push($minify_files[$type], $file);
				}
				else
				{
				  $minify_files[$type] = array($file);
				}
			      }

			}
		}
		}
	}
  $html = '';
  if(is_array($minify_files)){
  foreach($minify_files as $options => $files)
  {
    $options = unserialize($options);
    $options['href'] = sfConfig::get("app_img_url")."/min/?f=".join($files, ',');
    $html .= tag('link', $options)."\n";
  }
  }
  return $html;
}

/**
 * Print <link> tag for all stylesheets configured in module level view.yml. It ignores application level default stylesheets
 *
 * @see minify_get_module_stylesheets()
 *
 * @author Tanu Gupta
 */
function minify_include_module_stylesheets()
{
        echo minify_get_module_stylesheets();
}
