<?php

class SongKickApi {

  /**
   * Songkick API key
   * @var string
   */
  private $api__key;

  public function __construct($api_key)
  {
      $this->api__key = $api_key;
  }

  public function get_artist_data($artist_id, $type)
  {
    $url = 'http://api.songkick.com/api/3.0/artists/' . $artist_id . '/' . $type . '.json?apikey=' . $this->api__key;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $output_array = json_decode($output);

    if ( $output_array->resultsPage->totalEntries > 0 ){
      $events = array();
      foreach ( $output_array->resultsPage->results->event as $sk_event ){
        $event = array();
        $event['venue'] = $sk_event->venue->displayName;
        $event['location'] = $sk_event->location->city;
        $event['date'] = $sk_event->start->date;
        $event['time'] = $sk_event->start->time;
        $event['url'] = $sk_event->uri;
        
        $events[] = $event;
      }
      return $events;
    }

  }

  public function get_artist_upcoming_events($artist_id)
  {
    return $this->get_artist_data($artist_id, "calendar");
  }

  public function get_artist_past_events($artist_id)
  {
    return $this->get_artist_data($artist_id, "gigography");
  }

}