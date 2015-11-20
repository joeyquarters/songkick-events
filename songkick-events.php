<?php
/*
Plugin Name: Songkick Events Widget
Version: 1.0
Description: Creates a shortcode that outputs an artist's next show via the SongKick API.
Author: Joey Nichols
*/

if ( ! defined( 'WPINC' ) ) {
  wp_die();
}

register_activation_hook( __FILE__, array('SongKickEventsPlugin', 'load_default_options') );

/**
 * Main Plugin Class
 */
class SongKickEventsPlugin {

  private $options;
  protected $events;

  public function __construct()
  {
    $this->load_classes();
    $this->load_admin_page();
    $this->load_widget();
  }

  /**
   * Loads the necessary classes for the plugin
   */
  public function load_classes()
  {
    require_once( plugin_dir_path( __FILE__ ) . 'admin/class-songkick-events-admin.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'includes/class-songkick-api.php' );
    require_once( plugin_dir_path( __FILE__ ) . 'includes/class-songkick-events-widget.php' );
  }

  /**
   * Loads the admin page
   */
  public function load_admin_page()
  {
    if ( is_admin() ){
      $admin_page = new SongKickEventsAdmin();
    }
  }

  /**
   * Put default options in the database
   */
  public function load_default_options()
  {
    $shortcode_template = '<h2>NEXT STOP:</h2><h4>%EVENT_LOCATION%</h4>'.
      '<h3><a href="%EVENT_LINK%" target="_blank">%EVENT_DATE%</h3>'.
      '<h4><em>%EVENT_VENUE% | %EVENT_TIME%</em></a></h4>';

    $option_values = array(
      'api_key' => '',
      'artist_id' => '',
      'date_format' => 'D. M jS',
      'time_format' => 'gA',
      'shortcode_template' => $shortcode_template,
      'no_results_template' => '<h3>No upcoming events.</h3>'
    );

    update_option('songkick_events', $option_values);
  }

  /**
   * Loads the widget and shortcode
   */
  public function load_widget()
  {
    $songkick_events = new SongKickEventsWidget();
  }

}

$songkick_events = new SongKickEventsPlugin();