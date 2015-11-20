<?php

class SongKickEventsAdmin {

  /**
   * Holds the values to be used in the fields callbacks
   */
  private $options;

  public function __construct(){
    add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
    add_action( 'admin_init', array( $this, 'page_init' ) );
  }

  /**
   * Add Options Page
   */
  public function add_plugin_page(){
    // This page will be under "Settings"
    add_options_page(
        'Songkick Events Admin', 
        'Songkick Events', 
        'manage_options', 
        'songkick-events-admin', 
        array( $this, 'create_admin_page' )
    );
  }

  /**
   * Options page callback
   */
  public function create_admin_page(){
    // Set class property
    $this->options = get_option( 'songkick_events' );
    ?>
    <div class="wrap">
        <h2>Songkick Events Settings</h2>           
        <form method="post" action="options.php">
        <?php
            // This prints out all hidden setting fields
            settings_fields( 'songkick_events_group' );   
            do_settings_sections( 'songkick-events-admin' );
            submit_button(); 
        ?>
        </form>
    </div>
    <?php
  }

  /**
   * Register and add settings
   */
  public function page_init(){
    register_setting(
      'songkick_events_group', // Option group
      'songkick_events', // Option name
      array( $this, 'sanitize' ) // Sanitize
    );

    add_settings_section(
      'songkick_events_settings', // ID
      'Songkick Events Settings', // Title
      array( $this, 'print_section_info' ), // Callback
      'songkick-events-admin' // Page
    );  

    add_settings_field(
      'api_key', // ID
      'API Key', // Title 
      array( $this, 'api_key_callback' ), // Callback
      'songkick-events-admin', // Page
      'songkick_events_settings', // Section
      array( 'label_for' => 'api_key' ) // Label           
    );

    add_settings_field(
      'artist_id', // ID
      'Artist ID', // Title 
      array( $this, 'artist_id_callback' ), // Callback
      'songkick-events-admin', // Page
      'songkick_events_settings', // Section
      array( 'label_for' => 'artist_id' ) // Label       
    );

    add_settings_field(
      'time_format', // ID
      'Time Format', // Title 
      array( $this, 'time_format_callback' ), // Callback
      'songkick-events-admin', // Page
      'songkick_events_settings', // Section
      array( 'label_for' => 'time_format' ) // Label
    );

    add_settings_field(
      'date_format', // ID
      'Date Format', // Title 
      array( $this, 'date_format_callback' ), // Callback
      'songkick-events-admin', // Page
      'songkick_events_settings', // Section           
      array( 'label_for' => 'date_format' ) // Label
    );

    add_settings_field(
      'shortcode_template', // ID
      'Shortcode Template', // Title
      array( $this, 'shortcode_template_callback'), // Callback
      'songkick-events-admin', // Page
      'songkick_events_settings', // Section
      array( 'label_for' => 'shortcode_template' ) // Label
    );

    add_settings_field(
      'no_results_template', // ID
      'No Results Template', // Title
      array( $this, 'no_results_template_callback'), // Callback
      'songkick-events-admin', // Page
      'songkick_events_settings', // Section
      array( 'label_for' => 'no_results_template' ) // Label
    );
  }

  /**
   * Sanitize each setting field as needed
   *
   * @param array $input Contains all settings fields as array keys
   */
  public function sanitize( $input ){
      $new_input = array();

      if ( isset( $input['api_key'] ) ){
        $new_input['api_key'] = trim( $input['api_key'] );
      }

      if ( isset( $input['artist_id'] ) ){
        $new_input['artist_id'] = filter_var( $input['artist_id'], FILTER_SANITIZE_NUMBER_INT );
      }

      if ( isset( $input['date_format'] ) ){
        $new_input['date_format'] = trim( $input['date_format'] );
      }

      if ( isset( $input['time_format'] ) ){
        $new_input['time_format'] = trim( $input['time_format'] );
      }

      if ( isset( $input['shortcode_template'] ) ){
        $new_input['shortcode_template'] = $input['shortcode_template'];
      }

      if ( isset( $input['no_results_template'] ) ){
        $new_input['no_results_template'] = $input['no_results_template'];
      }

      return $new_input;
  }

  /** 
   * Print the Section text
   */
  public function print_section_info(){
    echo '<p>To use the widget, simply insert the shortcode <code>[songkick-events]</code> into a post or page.</p>';
    echo 'The time format and date format fields utilize the PHP date formatting parameters, <a href="http://php.net/manual/en/function.date.php" target="_blank">found here.</a>';
    echo '<p>Enter your settings below:</p>';
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function api_key_callback(){
    printf(
      '<input type="text" id="api_key" name="songkick_events[api_key]" value="%s" />',
      isset( $this->options['api_key'] ) ? esc_attr( $this->options['api_key']) : ''
    );
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function artist_id_callback(){
    printf(
      '<input type="text" id="artist_id" name="songkick_events[artist_id]" value="%s" />',
      isset( $this->options['artist_id'] ) ? esc_attr( $this->options['artist_id']) : ''
    );
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function date_format_callback(){
    printf(
      '<input type="text" id="date_format" name="songkick_events[date_format]" value="%s" />',
      isset( $this->options['date_format'] ) ? $this->options['date_format'] : ''
    );
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function time_format_callback(){
    printf(
      '<input type="text" id="time_format" name="songkick_events[time_format]" value="%s" />',
      isset( $this->options['time_format'] ) ? $this->options['time_format'] : ''
    );
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function shortcode_template_callback(){
    printf(
      '<textarea class="code large-text" rows="3" id="shortcode_template" name="songkick_events[shortcode_template]">%s</textarea>',
      isset( $this->options['shortcode_template'] ) ? $this->options['shortcode_template'] : ''
    );
  }

  /** 
   * Get the settings option array and print one of its values
   */
  public function no_results_template_callback(){
    printf(
      '<textarea class="code large-text" rows="3" id="no_results_template" name="songkick_events[no_results_template]">%s</textarea>',
      isset( $this->options['no_results_template'] ) ? $this->options['no_results_template'] : ''
    );
  }

}