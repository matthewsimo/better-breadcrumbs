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

    // Create some helper functions
    function bc_wrap($lis){
      $crumbOpen   = '<nav class="breadcrumbs"><ol>'; // Note plural class name here
      $crumbClose  = '</ol></nav>';

      if ($lis != '')
        return $crumbOpen . $lis . $crumbClose;
      else
        return;
    }

    function li_wrap($anchor_string) {
      $li_open  = '<li class="breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
      $li_close = '</li>';

      return $li_open . $anchor_string . $li_close;
    }

    // Initialize the breadcrumbs var that will hold our breadcrumbs & our homecrumb item.
    $breadcrumbs = '';
    $homecrumb = li_wrap('<a href="' . $homeLink . '" itemprop="url"><span itemprop="title">' . $homeText . '</span></a>');

    if (  is_home() || is_front_page()  ){

      if ($showOnHome) { // We're on the home/front page, should we build breadcrumbs
        $breadcrumbs .= $homecrumb;
      }

    } else { // We're not on the home/front page.
      // Move on to the logic for finding what kind of page and building the breadcrumbs specific to that page.

      if ( is_category() ) { // Category specific pages

        global $wp_query;
        $thisCat = get_category($wp_query->get_queried_object()->term_id);
        $parentCat = get_category($thisCat->parent);

        if ($thisCat->parent != 0) {
          $breadcrumbs .= "<li>" . get_category_parents($parentCat, TRUE, "</li>\n  $delimiter\n  <li>");
        } else {
          $breadcrumbs .= $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after . "</li>" . "\n";
        }

      } elseif ( is_search() ) { // Search specific pages

        $breadcrmbs .= li_wrap( $before . 'Search results for "' . get_search_query() . '"' . $after;

      } elseif ( is_day() ) { // Day Archive specific pages

      } elseif ( is_month() ) { // Month Archive specific pages

      } elseif ( is_year() ) { // Year Archive specific pages

      } elseif ( is_single() && !is_attachment() ) { // Single that's not an attachement

      } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) { // Custom post types

      } elseif ( is_attachment() ) { // Attachment specific pages

      } elseif ( is_page() && !$post->post_parent ) { // WP Page w/o parents specific pages

      } elseif ( is_page() && $post->post_parent ) { // WP Page w/ parents specific pages

      } elseif ( is_tag() ) { // Tag Archive specific pages

      } elseif ( is_author() ) { // Author specific pages

      } elseif ( is_404() ) { // 404 specific pages

      } else { // Anything not caught yet

      }

    }


    // return our freshly baked breadcrumbs
    return bc_wrap($breadcrumbs);
  }
}

$bbcrumbs = new BBCrumbs;
