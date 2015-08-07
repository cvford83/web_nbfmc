<?php

/* 
 * Script elements credited to:
 * https://www.codeofaninja.com/2011/07/display-facebook-events-to-your-website.html
 */

// Don't know where the server is or how its clock is set, so default to UTC
date_default_timezone_set( "UTC" );

$app_id ='1575670146011328';
$app_secret = 'b297fbc74c517baf1415da9bc08ae89a';
$per = "https://graph.facebook.com/oauth/access_token?client_id={$app_id}&client_secret={$app_secret}&grant_type=client_credentials";
//echo file_get_contents($per);
$fb_page_id = "348232209811";
//FB login access token
$access_token = file_get_contents($per);

$year_range = 1;

// automatically adjust date range
// human readable years
$since_date = date('Y-m-d');
$until_date = date('Y-12-31', strtotime('+' . $year_range . ' years'));

// unix timestamp years
$since_unix_timestamp = strtotime($since_date);
$until_unix_timestamp = strtotime($until_date);

if(isset($_GET['display']) &&  $_GET['display'] == 'past'){
    // automatically adjust date range
    // human readable years
    $since_date = date('Y-01-01', strtotime('-' . $year_range . ' years'));
    $until_date = date('Y-m-d', strtotime('-1 day'));

    // unix timestamp years
    $since_unix_timestamp = strtotime($since_date);
    $until_unix_timestamp = strtotime($until_date);
}

    


//Prepare FB graph API link
$fields="id,name,location,venue,timezone,start_time,cover";
 
$json_link = "https://graph.facebook.com/{$fb_page_id}/events?{$access_token}&fields={$fields}&since={$since_unix_timestamp}&until={$until_unix_timestamp}&limit=25";
 
$json = file_get_contents($json_link);

$obj = json_decode($json, true, 512);
//$obj = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $json), true);
// for those using PHP version older than 5.4, use this instead:
// $obj = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $json), true);
// count the number of events
$event_count = count($obj['data']);
//offset time by time zone
$daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open( "EST" ), new DateTime() );
if(isset($_GET['display']) &&  $_GET['display'] == 'past'){
    for($x=0; $x<$event_count; $x++){
        // facebook events will be here
        $start_date = date( 'l, F d, Y', strtotime($obj['data'][$x]['start_time']));
        
        //offset time by time zone
        $daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open( "EST" ), new DateTime($obj['data'][$x]['start_time']) );
        
        // in my case, I had to subtract 9 hours to sync the time set in facebook
        $start_time = date('g:i a', strtotime($obj['data'][$x]['start_time']) + $daylight_savings_offset_in_seconds);

        $pic_big = isset($obj['data'][$x]['cover']['source']) ? $obj['data'][$x]['cover']['source'] : "https://graph.facebook.com/{$fb_page_id}/picture?type=large";

        $eid = $obj['data'][$x]['id'];
        $name = $obj['data'][$x]['name'];
        $location = isset($obj['data'][$x]['location']) ? $obj['data'][$x]['location'] : "";
        $latitude = isset($obj['data'][$x]['venue']['latitude']) ? $obj['data'][$x]['venue']['latitude'] : "33.334885";
        $longitude = isset($obj['data'][$x]['venue']['longitude']) ? $obj['data'][$x]['venue']['longitude'] : "-84.783372";
        //
        $event[]= array('name' => $name, 'id' => $eid,'start_date'=> $start_date, 'start_time'=> $start_time, 'event_img' => $pic_big,
                        'location' => $location, 'event_lat' => $latitude, 'event_long' => $longitude);
    }
}else{
    for($x=$event_count-1; $x>=0; $x--){
        // facebook events will be here
        $start_date = date( 'l, F d, Y', strtotime($obj['data'][$x]['start_time']));

        //offset time by time zone
        $daylight_savings_offset_in_seconds = timezone_offset_get( timezone_open( "EST" ), new DateTime($obj['data'][$x]['start_time']) );
        
        // in my case, I had to subtract 9 hours to sync the time set in facebook
        $start_time = date('g:i a', strtotime($obj['data'][$x]['start_time']) + $daylight_savings_offset_in_seconds);

        $pic_big = isset($obj['data'][$x]['cover']['source']) ? $obj['data'][$x]['cover']['source'] : "https://graph.facebook.com/{$fb_page_id}/picture?type=large";

        $eid = $obj['data'][$x]['id'];
        $name = $obj['data'][$x]['name'];
        $location = isset($obj['data'][$x]['location']) ? $obj['data'][$x]['location'] : "";
        $latitude = isset($obj['data'][$x]['venue']['latitude']) ? $obj['data'][$x]['venue']['latitude'] : "33.334885";
        $longitude = isset($obj['data'][$x]['venue']['longitude']) ? $obj['data'][$x]['venue']['longitude'] : "-84.783372";
        //build array for json object
        $event[]= array('name' => $name, 'id' => $eid,'start_date'=> $start_date, 'start_time'=> $start_time, 'event_img' => $pic_big,
                        'location' => $location, 'event_lat' => $latitude, 'event_long' => $longitude);
    }
}

echo json_encode($event,JSON_FORCE_OBJECT);

?>