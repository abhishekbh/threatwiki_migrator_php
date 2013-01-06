<?php

/*
  var DataPoint = new Schema ({
      title: { type: String, required: true }
    , description: { type: String, required: true }
    , Location: {
        title: { type: String, required: true }
      , latitude: { type: String, required: true }
      , longitude: { type: String, required: true }
      }
    , soc: { type: String, required: true }
    , modified: { type: Date, required: true }
    , created: { type: Date, required: true }
    , event_date: {type: Date, required: true }
    // foreign key
    , tags      : [{ type: ObjectId, ref: 'Tag' }]
    , createdBy : { type: ObjectId, ref: 'User' }
    , modifiedBy : { type: ObjectId, ref: 'User' }
    , archive: { type: Boolean}
  });
*/
function buildDatapointObject(){}

function parse_month($month_in){}

/*
  ==Rephrase Dates==
  1. Format to 14.June.2012
  2. If null, make it 0.0.0000
*/
function parse_date($date_in){}

/*
  ==Create Tags==
  1. If Datapoint has tag
     then do a lookup to see if the tag already exists
     if it does, get its ObjectID and add to local object
     if not, then create the datapoint and apply id to local object
*/
function doTagStuff(){}

function getJSONResponse($url,$data){
  $data_string = json_encode($data);                                                                                   
 
  $ch = curl_init($url);                                                                      
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($data_string))                                                                       
  );                                                                                                                   
 
  $result = curl_exec($ch);
}

/*
  ==Geolocate==
  1. http://maps.googleapis.com/maps/api/geocode/json?address=' + locations[i] + ',+Iran&sensor=false
  2. Make sure the location does not say something like US, or UN
*/
function geolocate($location){
  $location = $location.',Iran';
  $url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$location.',+Iran&sensor=false';

  getJSONResponse($url,$location);
}

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
  echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

  $row = 1;
  if (($handle = fopen('data/IBSOC2012FULL.csv', 'r')) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<br />";
        $row++;
        for ($c=0; $c < $num; $c++) {
            if ($c == 0) {
              echo "<b>Source</b><br/>";
              echo utf8_encode($data[$c]) . "<br />";
            } else if ($c == 1) {
              echo "<b>Title</b><br/>";
              echo utf8_encode($data[$c]) . "<br />";
            } else if ($c == 2) {
              echo "<b>Publication Date</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 3) {
              echo "<b>Event Date</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 4) {
              echo "<b>Owner of Publication</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 5) {
              echo "<b>Location</b><br/>";
              $locations = $data[$c];
              foreach ($locations as $loc) {
                echo $location . "<br/>";
              }
            } else if ($c == 6) {
              echo "<b>Tags</b><br/>";
              $tags = explode("/", $data[$c]);
              foreach ($tags as $tag) {
                echo $tag . "<br />";
              }
            } else if ($c == 7) {
              echo "<b>Comments</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 8) {
              echo "<b>Corroboration</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 9) {
              echo "<b>Photograph Available?</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 10) {
              echo "<b>Date Tweeted</b><br/>";
              echo $data[$c] . "<br />";
            } else {
              echo "<b>Extra</b><br/>";
              echo $data[$c] . "<br />";
            }
        }
    }
    fclose($handle);
  }
