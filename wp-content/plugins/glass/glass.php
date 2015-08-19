<?php
/*
Plugin Name: Glass
Plugin URI: http://codeblab.com/glass/
Description: Glass adds a magnifying glass to your images.
Author: Jan-Mark Wams
Version: 1.3.2
Author URI: http://codeblab.com/
License: GPL3
*/

/*
 * Glass.php, create a magnifying glass effect for images, a WordPress Plugin.
 * Copyright (C) 2011  Jan-Mark S. Wams (jms@cs.vu.nl)
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (!class_exists("MyrtheGlass")) {
  class MyrtheGlass {
    var $adminOptionsName = "MyrtheGlassAdminOptions";
    var $glassIsActive = true;
    function MyrtheGlass() {}
    function init() { $this->getAdminOptions(); }

    /* Check to see if this page should have an active glass plugin.  */
    function glassActive()
    {
      function trimMe(&$v) { $v = trim($v); }  /* Callback for array_walk(). */
      $opt = $this->getAdminOptions();

      /* Check if platform is excluded. */
      $ignorePlat = $opt['myrtheGlassExcludePlatforms'] or $ignorePlat = '';
      if ($ignorePlat != '') {
	$ignorePlatArray = explode(',', $ignorePlat);
	array_walk($ignorePlatArray, 'trimMe');
	foreach($ignorePlatArray as $iggy)
	  if (strpos($_SERVER['HTTP_USER_AGENT'], $iggy) !== false)
	    return false;  /* Fail if user agent matches any keyword. */
	/* Fall through. */
      }
      
      /* Check if this is the home page and if it is allowed. */
      if (is_front_page()) {
	$onFrontPage = $opt['myrtheGlassOnFrontPage'] or $onFrontPage = 'n';
	if ($onFrontPage == 'y') { return true; }
      }

      /* Check if category is allowed or not. */
      if (is_category() || is_single()) {
	$allowCats = $opt['myrtheGlassCategories'] or $allowCats = '';
	if ($allowCats == '') return true;        /* All categories are ok. */
	$allowCatsArray = explode(',', $allowCats);
	array_walk($allowCatsArray, 'trimMe');
	return in_category($allowCatsArray);      /* Explicit allow. */
      }
	
      /* Check if page is allowed or not. */
      if (is_page()) {
	$allowPages = $opt['myrtheGlassPages'] or $allowPages = '';
	if ($allowPages == '') return true;           /* All pages are ok. */
	$allowPagesArray = explode(',', $allowPages);
	array_walk($allowPagesArray, 'trimMe');
	return is_page($allowPagesArray);             /* Explicit allow. */
      }

      /* All other others disallow the plugin. */
      return false;
    }

    /* Add the script to the header. */
    function addScript() 
    {      
      $file = get_option('siteurl')
	.'/wp-content/plugins/'
	.dirname(plugin_basename(__FILE__))
	.'/glass.js';
      wp_enqueue_script('glass', $file, false, '1.3.2');
    }
    
    /* Add all 'myrtheGlass' defaults to document for JavaScript usage. */
    function addDefaults()
    {
      /* Set the active boolean. */
      $this->glassIsActive = $this->glassActive();

      if ($this->glassIsActive) 
      {
	$myrtheGlassOptions = $this->getAdminOptions();
	echo "\n";
	echo "<script type='text/javascript'> // .oOXOo. Glass plugin.\n";
	foreach ($myrtheGlassOptions as $key => $value)
	  if (substr($key, 0, 11) == 'myrtheGlass')
	    echo "  document.$key='$value';\n";
	/* Add basename for image URLs. */
	$url = get_option('siteurl')
	  .'/wp-content/plugins/'.dirname(plugin_basename(__FILE__)) ."/";
	echo "  document.myrtheGlassImgURL='$url';\n";
	echo "</script>\n";
      }
    }

    function addBodyOnload()
    {
      if ($this->glassIsActive) 
      {
	echo "<script type='text/javascript'>\n";
	/* echo "window.onload='glassInit()';\n"; */
	echo "  glassInit();\n"; 
	echo "</script>\n";
      }
    }
    
    function getAdminOptions() 
    {
      /* Default options array. */
      $myrtheGlassAdminOptions = array(
	'myrtheGlassDx' => '0',
	'myrtheGlassDy' => '0',
	'myrtheGlassMinEnlarge' => '2.0',
	'myrtheGlassMaxEnlarge' => '100.0',
	'myrtheGlassDefaultSize' => '4',
	'myrtheGlassRimPath' => '',
	'myrtheGlassRimRGB' => 'CC6633',
	'myrtheGlassBackgroundRGB' => '999',
	'myrtheGlassCategories' => '',
	'myrtheGlassOnFrontPage' => 'y',
	'myrtheGlassPages' => '',
	'myrtheGlassExcludePlatforms' => 'iPod',
	'myrtheGlassLinkActive' => '', 
        'myrtheGlassSuffixActive' => '');
      
      /* Get the options from the system. */
      $myrtheGlassOptions = get_option($this->adminOptionsName);
      
      /* If system has options, copy them into options array. */ 
      if (!empty($myrtheGlassOptions)) {
	foreach ($myrtheGlassOptions as $key => $option)
	  $myrtheGlassAdminOptions[$key] = $option;
      }				

      /* Just to be sure, update the system options. */
      update_option($this->adminOptionsName, $myrtheGlassAdminOptions);
      
      /* Return the options array. */
      return $myrtheGlassAdminOptions;
    }
    
    /* Create admin page content. */
    function printAdminPage()
    {
      function fixRGB($rgb)
      {
	$rgb = preg_replace('/[^0-9A-Fa-f]+/', '', $rgb);  /* Kill non hex. */
	if (strlen($rgb) == 3)
	  $rgb = preg_replace('/([0-9A-Fa-f])/', '\1\1', $rgb); /* Shorthand.*/
	else                  
	  $rgb = substr($rgb."000000",0,6);  /* Padd with zeros. */
	return strtoupper($rgb);
      }
      $a_Options = array(glassDefaultRadius);
      $myrtheGlassOptions = $this->getAdminOptions();

      /* Update the settings from the $_POST values. */
      if (isset($_POST['update_MyrtheGlassSettings'])) { 

	/* If OnFrontPage is not selected, it won't be in the $_POST. */
	$myrtheGlassOptions['myrtheGlassOnFrontPage'] = 'n';

	/*  Update all the updatables from $_POST to own array. */
	foreach ($myrtheGlassOptions as $key => $value) 
	  if (isset($_POST[$key])) $myrtheGlassOptions[$key] = $_POST[$key];

	/* Fix the Rim Path on prefix and trailing slash. */
	$key = 'myrtheGlassRimPath';
	$path = $myrtheGlassOptions[$key];
   	if ($path != '') {
	  if (!strstr($path, '/'))
	    $path = get_option('siteurl')."/wp-content/plugins/glass/".$path;
	  if (substr($path, -1) != '/') 
	    $path .= '/';
	  $myrtheGlassOptions[$key] = $path;
	}

	/* Fix RGB if need be. */
	$key = 'myrtheGlassBackgroundRGB';
	$myrtheGlassOptions[$key] = fixRGB($myrtheGlassOptions[$key]);
	$key = 'myrtheGlassRimRGB';
	$myrtheGlassOptions[$key] = fixRGB($myrtheGlassOptions[$key]);

	/* There is many more checks that could be performed. Add as you go. */

	/* Update the database. */
	update_option($this->adminOptionsName, $myrtheGlassOptions);

	echo "<div class='updated'><p><strong>";
	echo __("Settings Updated.", "MyrtheGlass");
	echo "</strong></p></div>\n";
      }	
      
      echo "<div class='wrap'>\n";
      echo "<div id='icon-options-general' class='icon32'><br /></div>\n";
      echo "<h2>Glass</h2>";

      echo "<form method='post' action='".$_SERVER["REQUEST_URI"]."'>";

      echo "<table class='form-table'>\n";
      echo "<tr valign='top'>\n";

      /* Glass default size. */
      $key     = "myrtheGlassDefaultSize";
      $sizeMin = 0;
      $sizeMax = 10;
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Default glass size, from small ($sizeMin)"
                 ." to big ($sizeMax).";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<th scope='row'>";
      echo "<label for='key'>";
      echo "</label>";
      echo "</th>\n";
      echo "<tr><td>";
      echo "$name: ";
      echo "<select name='$key' id='$key' class='postform'>";
      for ($i = $sizeMin; $i <= $sizeMax; $i += 1) {
	echo "<option  class='level-0' value='$i'";
	if ($i == $val) echo " selected='selected'";
	echo ">$i</option>";
      }
      echo "</select>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td></tr>\n";

      /* Glass Rim Image Path. */
      $eg      = get_option('siteurl')."/wp-content/plugins/glass/spy/";
      $key     = "myrtheGlassRimPath";
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Path to the rim images with trailing '/'.";
      $expl   .= "<br />";
      $expl   .= "E.g.: $eg";
      $expl   .= "<br />";
      $expl   .= "Leave empty for solid color rim or try 'spy'.";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='24' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Glass Rim Color. */
      $key     = "myrtheGlassRimRGB";
      $sizeMin = 0;
      $sizeMax = 0xFFFFFF;
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "RGB value of the color of the rim of the glass";
      $expl   .= " (000000-FFFFFF).";
      $expl   .= "<br />";
      $expl   .= "(Irrelevant if a Glass Rim Path is given.)";
      $name    = preg_replace('/([A-Z])/', ' $1', $key);
      $name    = preg_replace('/myrthe /', '', $name);
      $name    = preg_replace('/R G B/', 'RGB', $name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='6' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Glass Background Color. */
      $key     = "myrtheGlassBackgroundRGB";
      $sizeMin = 0;
      $sizeMax = 0xFFFFFF;
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "RGB value of the background color of the glass";
      $expl   .= " (000000-FFFFFF).";
      $expl   .= "<br />";
      $expl   .= "Visiable during loading and around the edges.";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);
      $name    = preg_replace('/R G B/', 'RGB', $name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='6' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Enlargement Factor. */
      $key     = "myrtheGlassMinEnlarge";
      $sizeMin = 0;
      $sizeMax = 0xFFFFFF;
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Minimum enlargement factor (try around 2.0).";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='8' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Enlargement Factor. */
      $key     = "myrtheGlassMaxEnlarge";
      $sizeMin = 0;
      $sizeMax = 0xFFFFFF;
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Maximum enlargement factor (try around 20.0).";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='8' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Dx and Dy for non image link. */
      $keyX    = "myrtheGlassDx";
      $keyY    = "myrtheGlassDy";
      $valX    = $myrtheGlassOptions[$keyX]; 
      $valY    = $myrtheGlassOptions[$keyY]; 
      $expl    = "For an image (thumbnail) linked to a page, the original "
	       . "image URL"
	       . "<br />"
               . "is guessed from the link. This may slow pages with many "
               . "thumbnails."
	       . "<br />"
               . "By setting these values to and <b>existing</b> (smaller) "
               . " dimension this can"
	       . "<br />"
               . "be remedied. Turn off: leave blank. Turn on: set to 0,0.";
      $name    = "Thumb dx dy";

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$keyX' id='$keyX'";
      echo " class='postform' size='4' value='$valX' \>";
      echo "<input type='text' name='$keyY' id='$keyY'";
      echo " class='postform' size='4' value='$valY' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo "$expl";
      echo "</label>";
      echo "</td>\n";

      /* On the Frontpage, yes or no? */
      $key     = "myrtheGlassOnFrontPage";
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Use Glass on the front page.";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "<input type='checkbox' name='$key' id='$key' value='y'";
      if ($val == 'y') { echo " checked='checked'"; }
      echo " /> $name";
      echo "</td><td>";
      echo "<label for='key'>";
      echo "$expl";
      echo "</label>";
      echo "</td>\n";

      /* Maybe use only specified categories. */
      $key     = "myrtheGlassCategories";
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Use Glass on named categories only (comma-delimited).";
      $expl   .= "<br />";
      $expl   .= "Leave empty for all categories.";
      $expl   .= " Use non existing category to exclude all.";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='24' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Maybe use only specified pages. */
      $key     = "myrtheGlassPages";
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Use Glass on named pages only (comma-delimited).";
      $expl   .= "<br />";
      $expl   .= "Leave empty for all pages.";
      $expl   .= " Use non existing name to exclude all.";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='24' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* Maybe exclude some platforms. */
      $key     = "myrtheGlassExcludePlatforms";
      $val     = $myrtheGlassOptions[$key]; 
      $expl    = "Exclude some platforms (comma-delimited).";
      $expl   .= "<br />";
      $expl   .= "You might want to exclude iPod";
      $expl   .= " (checks HTTP_USER_AGENT only).";
      $name    = preg_replace('/([A-Z])/', ' $1',$key);
      $name    = preg_replace('/myrthe /', '',$name);

      echo "<tr><td>";
      echo "$name: ";
      echo "<input type='text' name='$key' id='$key'";
      echo " class='postform' size='24' value='$val' \>";
      echo "</td><td>";
      echo "<label for='key'>";
      echo " $expl";
      echo "</label>";
      echo "</td>\n";

      /* End of table. */
      echo "</tr>\n";
      echo "</table>\n";
      
      echo "<div class='submit'>";
      echo "<input type='submit' name='update_MyrtheGlassSettings'";
      echo " value='".__("Update Settings", "MyrtheGlass")."' />";	
      echo "</div>";

      echo "</form>";
      echo "</div>";
    }
  }  /*  End Class MyrtheGlass. */
} /* End Class redefine protection. */
  
/* Make sure there is a global plugin object for MyrtheClass. */
if (!isset($myrtheGlass_plugin)) 
  $myrtheGlass_plugin = new MyrtheGlass();

/* Create global ap function. */
if (!function_exists("MyrtheGlass_ap")) {
  function MyrtheGlass_ap() 
  {
    global $myrtheGlass_plugin;
    if (isset($myrtheGlass_plugin) && function_exists('add_options_page')) {
      add_options_page('Glass', 'Glass', 9, plugin_basename(__FILE__), 
		       array(&$myrtheGlass_plugin, 'printAdminPage'));
    }
  }
}

/* Add Glass page to backend. */
add_action('admin_menu', 'MyrtheGlass_ap');

/* Add Extra links to plugin blurp on plugin page. */
function set_plugin_meta($links, $file) 
{
  $plugin = plugin_basename(__FILE__);
  if ($file == $plugin) {
    return array_merge(
      $links, array(
	sprintf('<a href="options-general.php?page=%s">%s</a>', $plugin, __('Settings')),
        '<a href="http://codeblab.com/feedback/your-glass-plugin-works-great/">Like +1</a>',
        '<a href="http://codeblab.com/feedback/your-glass-plugin-does-not-work/">Report a problem</a>')
      );
  }
  return $links;
}
add_filter( 'plugin_row_meta', 'set_plugin_meta', 10, 2 );

/* If not in backend engage. */
if (!is_admin()) {
  add_action('init', array(&$myrtheGlass_plugin, 'addScript'));
  add_action('wp_head', array(&$myrtheGlass_plugin, 'addDefaults'));
  add_action('wp_footer', array(&$myrtheGlass_plugin, 'addBodyOnload'));
}

?>
