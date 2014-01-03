<?php
/*
$url = 'http://openmap.rm.ingv.it/gmaps/rec/files/last90days_events.csv';

if (($handle = fopen($url, 'r')) === false) {
    die('Error opening file');
}

$headers = fgetcsv($handle, 1024, ',');
$complete = array();

while ($row = fgetcsv($handle, 1024, ',')) {
    $complete[] = array_combine($headers, $row);
}

fclose($handle);

//echo json_encode($complete);

$src=json_encode($complete);

$src=str_replace('Magnitude','mag',$src);
$src=str_replace('UTC_Date','time',$src);
$src=str_replace('","Locality"',',"Locality"',$src);
$src=str_replace('"mag":"','"mag":',$src);
*/

$csvfile = 'http://openmap.rm.ingv.it/gmaps/rec/files/last90days_events.csv';
$handle = fopen($csvfile, 'r');

# Build GeoJSON feature collection array
$geojson = array(
    'type' => 'FeatureCollection',
    'features' => array()
);

# Loop through rows to build feature arrays
$header = NULL;
while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
    if (!$header) {
        $header = $row;
    } else {
        $data = array_combine($header, $row);
        $properties = $data;
        # Remove x and y fields from properties (optional)
        unset($properties['Lat']);
        unset($properties['Lon']);
        $feature = array(
'properties' => $properties,
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => array(
                    $data['Lon'],
                    $data['Lat']
                )
            )
        );
        # Add feature arrays to feature collection array
        array_push($geojson['features'], $feature);
    }
}
fclose($handle);

//header('Content-type: application/json');
$src=json_encode($geojson);

//$src=str_replace('"Magnitude":"','"mag":',$src);
$src=str_replace('"UTC_Date":"','"time_utc":',$src);
$src=str_replace('","Locality"',',"Locality"',$src);
$src=str_replace('"Magnitude":"','"Magnitude":',$src);
$src=str_replace('{"type":"FeatureCollection"','eqfeed_callback({"type":"FeatureCollection"',$src);
$src=str_replace('}}]}','}}]});',$src);
$src=str_replace('"time_utc":','"time_utc":"',$src);
$src=str_replace('\/','/',$src);
$src=str_replace('"type":"Feature","geometry"','"geometry"',$src);
$src=str_replace('"Code":"','"ID":"<a href=\"http://cnt.rm.ingv.it/data_id/',$src);
$src=str_replace('","Query_Time"','/event.html\">Link only for last 30 days</a>","Query_Time"',$src);
$file = "elenco-eq.json";


$XMLFile = fopen($file, "w") or die("can't open file");
  
  fwrite($XMLFile, $src);
  fclose($XMLFile);

//sleep(10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Earthquakes last 90 days from INGV</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Piersoft">
    <link href="http://humangeo.github.io/leaflet-dvf/examples/lib/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
      .mag-value {
			vertical-align: middle;
			text-align: center;
			line-height: 10px;
			color: rgba(0, 0, 0, 0.8);
		}
    </style>
    <link rel="stylesheet" href="http://humangeo.github.io/leaflet-dvf/examples/lib/bootstrap/css/bootstrap-responsive.css" >
    <link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="http://humangeo.github.io/leaflet-dvf/dist/css/dvf.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="http://humangeo.github.io/leaflet-dvf/examples/css/example.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="http://humangeo.github.io/leaflet-dvf/examples/css/ui.css" type="text/css" media="screen" />
</head>

<body>
	<div class="navbar navbar-inverse">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Map complex</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li class="active"><a href="mappaeq_csv2.php">Map simple</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
    	<div class="row-fluid">
			<div id="map"></div>
		</div>
	</div>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="http://humangeo.github.io/leaflet-dvf/examples/lib/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="http://cdn.leafletjs.com/leaflet-0.6.4/leaflet.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.0.0/moment.min.js"></script>
	<script type="text/javascript" src="http://humangeo.github.io/leaflet-dvf/dist/leaflet-dvf.js"></script>
	<script type="text/javascript" src="earthquakes.js"></script>
	<script src="leaflet.geocsv-src.js"></script>
</body>
</html>