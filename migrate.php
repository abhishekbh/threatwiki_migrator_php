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

function parse_month($month_in){
  switch (strtolower($month_in)) {
    case 'january':
      return 1;
      break;
    case 'february':
      return 2;
      break;
    case 'march':
      return 3;
      break;
    case 'april':
      return 4;
      break;
    case 'may':
      return 5;
      break;
    case 'june':
      return 6;
      break;
    case 'july':
      return 7;
      break;
    case 'august':
      return 8;
      break;
    case 'september':
      return 9;
      break;
    case 'october':
      return 10;
      break;
    case 'november':
      return 11;
      break;
    case 'december':
      return 12;
      break;
    case 'jan':
      return 1;
      break;
    case 'feb':
      return 2;
      break;
    case 'mar':
      return 3;
      break;
    case 'apr':
      return 4;
      break;
    case 'jun':
      return 6;
      break;
    case 'jul':
      return 7;
      break;
    case 'aug':
      return 8;
      break;
    case 'sep':
      return 9;
      break;
    case 'sept':
      return 9;
      break;
    case 'oct':
      return 10;
      break;
    case 'nov':
      return 11;
      break;
    case 'dec':
      return 12;
      break;
    default:
      return 0;
  }
}

/*
  ==Rephrase Dates==
  1. Incoming format mostly 14.June.2012 but can also use dashes instead of periods
  2. Format to 2012-12-21
  3. If null, make it 0.0.0000

  Special Cases:
  1. (Recent)
  2. Multiple Dates of style 3.February.2012 / 5.February 2012
  3. null
  4. 12-13.June.2012.
  5. Trial (June 10) / Recent days
  6. (past few months) June
  7. (Recent Months) June
  8. June (approx)
  9. Several Dates 
  10. 29-30 Septemeber 2012
  11. 25. October. 2012 - 27. October. 2012
*/
function parse_date($date_in){
  $explode = explode('.', $date_in);

  // If not period type of date format, then try dashes
  if (count($explode) == 1) {
    $explode = explode('-', $date_in);
  }

  // If neither of above formats, assume special case
  if (count($explode) != 3) {
    return "SPECIAL_CASE";
  }

  $day = trim($explode[0]);
  $month = parse_month(trim($explode[1]));
  $year = trim($explode[2]);

  if ($month == 0) {
    return "SPECIAL_CASE";
  }

  return $year.'.'.$month.'.'.$day;
}

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
        echo "<br/>";
        $row++;
        for ($c=0; $c < $num; $c++) {
            if ($c == 0) {
              echo "<b>Source</b><br/>";
              echo utf8_encode($data[$c]) . "<br/>";
            } else if ($c == 1) {
              echo "<b>Title</b><br/>";
              echo utf8_encode($data[$c]) . "<br/>";
            } else if ($c == 2) {
              echo "<b>Publication Date</b><br/>";
//              echo "Original: " . $data[$c] . "<br/>";
              echo parse_date($data[$c]) . "<br/>";
            } else if ($c == 3) {
              echo "<b>Event Date</b><br/>";
//              echo "Original: " . $data[$c] . "<br/>";
              echo parse_date($data[$c]) . "<br/>";
            } else if ($c == 4) {
              echo "<b>Owner of Publication</b><br/>";
              echo $data[$c] . "<br/>";
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
                echo $tag . "<br/>";
              }
            } else if ($c == 7) {
              echo "<b>Comments</b><br/>";
              echo $data[$c] . "<br />";
            } else if ($c == 8) {
              echo "<b>Corroboration</b><br/>";
              echo $data[$c] . "<br/>";
            } else if ($c == 9) {
              echo "<b>Photograph Available?</b><br/>";
              echo $data[$c] . "<br/>";
            } else if ($c == 10) {
              echo "<b>Date Tweeted</b><br/>";
              echo $data[$c] . "<br/>";
            } else {
              echo "<b>Extra</b><br/>";
              echo $data[$c] . "<br/>";
            }
        }
    }
    fclose($handle);
  }
