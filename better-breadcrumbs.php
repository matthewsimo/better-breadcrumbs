<?php
/*
 * Plugin Name: Better Breadcrumbs
 * Plugin URI: http://upthemes.com
 * Description: Better breadcrumbs for wordpress
 * Author: Matthew Simo - <a href="http://twitter.com/matthewsimo">@matthewsimo</a> & <a href="http://upthemes.com">Upthemes</a>
 * Author URI: http://upthemes.com
 * Version: 1.0.0
 * * * * * * * * * * * * * * */

class BBCrumbs {

  public function __construct() {

    // Add [breadcrumbs] shortcode
    add_shortcode('breadcrumbs', array($this, 'shortcode'));

    // Add shortcode support to widgets, incase they want to throw it a text widget or something...
    add_filter('widget_text', 'do_shortcode');
  }

  // Method to actually build the breadcrumbs - sets up with default options
  public function buildBBCrumbs($opts = array(
                                          'showOnHome'     => 0,
                                          'delimiter'      => '&raquo',
                                          'homeText'       => 'Home',
                                          'showCurrent'    => 1,
                                          'beforeCurrent'  => '<span class="current">',
                                          'afterCurrent'   => '</span>'
                                        )){

    // Extract options for use.
    $showOnHome    = $opts['showOnHome']; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter     = $opts['delimiter']; // delimiter between crumbs
    $homeText      = $opts['homeText']; // text for the 'Home' link
    $showCurrent   = $opts['showCurrent']; // 1 - show current post/page title in breadcrumbs, 0 - don't show
    $beforeCurrent = $opts['beforeCurrent']; // tag before the current crumb
    $afterCurrent  = $opts['afterCurrent']; // tag after the current crumb

    global $post;
    $homeLink = get_bloginfo('url');

    // Initialize the breadcrumbs var that will hold our breadcrumbs
    $breadcrumbs = '';
    $crumbOpen   = '<nav class="breadcrumbs"><ol>'; // Note plural class name here
    $crumbClose  = '</ol></nav>';


    if ( ( $showOnHome == true ) && ( is_home() || is_front_page() ) ){
      $breadcrumbs .= $crumbOpen;

      $breadcrumbs .= '<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
      $breadcrumbs .= '<a href="' . $homeLink . '" itemprop="url">';
      $breadcrumbs .= '<span itemprop="title">' . $homeText . '</span></a></li>';

      $breadcrumbs .= $crumbClose;
    }


    // return our freshly baked breadcrumbs
    return $breadcrumbs;
  }
}

$bbcrumbs = new BBCrumbs;
