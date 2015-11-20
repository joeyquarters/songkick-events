<?php

class SongKickEventsWidgetTest extends WP_UnitTestCase {

  public function test_fill_template(){

    $widget = new SongKickEventsWidget;
    $widget->options = get_option('songkick_events');
    $template = "%EVENT_LOCATION% %EVENT_LINK% %EVENT_DATE% %EVENT_VENUE% %EVENT_TIME%";
    $event = array( 'venue' => 'Test Venue', 'location' => 'Nowhere, PA, USA', 'date' => '2015-11-17', 'time' => '23:00:00', 'url' => 'http://google.com' );
    
    $fill_template = $widget->fill_template( $template, $event );
    $fill_template_array = explode(" ", $fill_template);

    $this->assertNotContains("%EVENT_LOCATION%", $fill_template_array);
    $this->assertNotContains("%EVENT_LINK%", $fill_template_array);
    $this->assertNotContains("%EVENT_DATE%", $fill_template_array);
    $this->assertNotContains("%EVENT_VENUE%", $fill_template_array);
    $this->assertNotContains("%EVENT_TIME%", $fill_template_array);

  }

}