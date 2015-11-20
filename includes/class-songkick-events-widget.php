<?php

/**
 * Creates the shortcode and widget
 */
class SongKickEventsWidget {

  public function __construct()
  {
    add_shortcode( 'songkick-events', array($this, 'songkick_shortcode_handler') );
  }

  public function songkick_shortcode_handler( $atts, $content = null )
  {
    $template = $this->get_option_param('shortcode_template');
    $event = $this->get_upcoming_event();
    $completed_template = $this->fill_template( $template, $event );
    return $completed_template;
  }

  public function get_upcoming_event()
  {
    // Grab first event from array
    $event = reset( $this->get_events() );
    return $event;
  }

  /**
   * Gets the JSON returned from SongKick
   */
  public function get_events()
  {
    // look for transient
    $events = get_transient( 'songkick_events' );

    if ( $events ) {
      $this->events = $events;
    }
    else {
      $api = new SongKickApi( $this->get_option_param('api_key') );
      $events = $api->get_artist_upcoming_events( $this->get_option_param('artist_id') );
      set_transient( 'songkick_events', $events, 6 * HOUR_IN_SECONDS );
      $this->events = $events;
    }

    return $this->events;
  }

  /**
   * Fills the user-defined template
   */
  public function fill_template( $template = null, $event = null )
  {
    /* Return if no events */
    if ( empty($event) ) {
      return $this->get_option_param('no_results_template');
    }

    if ( ! $template ) { $template = $this->get_option_param('shortcode_template'); }
    $date = strtotime($event['date']);
    $date_format = $this->get_option_param('date_format');
    $time = strtotime($event['time']);
    $time_format = $this->get_option_param('time_format');

    $template = str_replace( "%EVENT_LOCATION%", $event['location'] , $template );
    $template = str_replace( "%EVENT_LINK%", $event['url'], $template );
    $template = str_replace( "%EVENT_DATE%", date( $date_format, $date ), $template );
    $template = str_replace( "%EVENT_VENUE%", $event['venue'], $template );
    $template = str_replace( "%EVENT_TIME%", date( $time_format, $time ), $template );

    return $template;
  }

  /**
   * Helper function for Wordpress get_option()
   */
  private function get_option_param( $param )
  {
    if ( ! $this->options )
      $this->options = get_option( 'songkick_events' );

    return $this->options[$param];
  }

}