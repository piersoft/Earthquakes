<?php

$url = 'http://openmap.rm.ingv.it/gmaps/rec/files/last90days_events.csv';
$file = "elenco-eq.csv";
$fileok = "elenco-eq1.csv";
$fileok1 = "elenco-eq2.csv";
$fileok2 = "elenco-eq3.csv";
$src = fopen($url, 'r');

$dest = fopen($file, 'w');
stream_copy_to_stream($src, $dest);


$search="LAT";
$replace="lat";

$output = passthru("sed -e 's/$search/$replace/g' $file > $fileok");

$search1="LON";
$replace1="lng";

$output1 = passthru("sed -e 's/$search1/$replace1/g' $fileok > $fileok1");

$search2=",";
$replace2=";";

$output2 = passthru("sed -e 's/$search2/$replace2/g' $fileok1 > $fileok2");

//echo stream_copy_to_stream($src, $dest) . "";

?>
<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="utf-8">
		<title>Earthquakes last 90 days</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

      <!-- Leaflet 0.5: https://github.com/CloudMade/Leaflet-->
		<link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.css" />
		<!--[if lte IE 8]> <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.ie.css" />  <![endif]-->  
		<script src="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.js"></script>

		<!-- MarkerCluster https://github.com/danzel/Leaflet.markercluster -->
		<link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.css" />
		<link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.Default.css" />
		<!--[if lte IE 8]> <link rel="stylesheet" href="http://joker-x.github.io/Leaflet.geoCSV/lib/MarkerCluster.Default.ie.css" /> <![endif]-->
		<script src="http://joker-x.github.io/Leaflet.geoCSV/lib/leaflet.markercluster-src.js"></script>

		<!-- GeoCSV: https://github.com/joker-x/Leaflet.geoCSV -->
		<script src="leaflet.geocsv-src.js"></script>

		<!-- jQuery 1.8.3: http://jquery.com/ -->
		<script src="http://joker-x.github.io/Leaflet.geoCSV/lib/jquery.js"></script>

		<style>	
		html, body, #mapa {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;	
			font-family: Arial, sans-serif;
			font-color: #38383;
		}

		#botonera {
			position:fixed;
			top:10px;
			left:50px;
			z-index: 2;
		}

		#cargando {
			position:fixed;
			top:0;
			left:0;
			width:100%;
			height:100%;
			background-color:#666;
			color:#fff;
			font-size:2em;
			padding:20% 40%;
			z-index:10;
		}

		.boton {
			border: 1px solid #96d1f8;
			background: #65a9d7;
			background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
			background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
			background: -moz-linear-gradient(top, #3e779d, #65a9d7);
			background: -ms-linear-gradient(top, #3e779d, #65a9d7);
			background: -o-linear-gradient(top, #3e779d, #65a9d7);
			padding: 12px 24px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			-webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
			-moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
			box-shadow: rgba(0,0,0,1) 0 1px 0;
			text-shadow: rgba(0,0,0,.4) 0 1px 0;
			color: white;
			font-size: 17px;
			/*font-family: Helvetica, Arial, Sans-Serif;*/
			text-decoration: none;
			vertical-align: middle;
		}
		.boton:hover {
			border-top-color: #28597a;
			background: #28597a;
			color: #ccc;
		}
		.boton:active {
			border-top-color: #1b435e;
			background: #1b435e;
		}
#infodiv{
       position:fixed;
        left:2px;
        bottom:2px;
	font-size: 10px;
        z-index:9999;
        border-radius: 10px; 
        -moz-border-radius: 10px; 
        -webkit-border-radius: 10px; 
        border: 2px solid #808080;
        background-color:#fff;
        padding:5px;
        box-shadow: 0 3px 14px rgba(0,0,0,0.4)
}
		</style>
	</head>
	<body>
		<div id="mapa"></div>
		<div id="cargando">Loading data...</div>

<div id="infodiv" style="leaflet-popup-content-wrapper">
<b>Earthquakes last 90 days until the last registrated</b>
<b>. Magnitudo: </b>>=2 <img src="pingiallo.png" width="8" height="8">  >=3 <img src="pinarancio.png" width="8" height="8">  >=4 <img src="pinarancioforte.png" width="8" height="8">  >=5 <img src="pinrosso.png" width="8" height="8"> | by @Piersoft with data from <a href="http://openmap.rm.ingv.it/gmaps/rec/files/last90days_events.csv">INGV</a> | Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors
</div>

<script>


//;$(function() {

var mapa = L.map('mapa', {attributionControl:false}).setView([40.46, -3.75], 5);

L.tileLayer('http://tile.osm.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors | <a href="http://dati.comune.matera.it">OpenData Matera</a>'}).addTo(mapa);
var customicon = 'pingrigio.png';
var bankias = L.geoCsv(null, {
	onEachFeature: function (feature, layer) {
		var popup = '';
		var popup1 = '';
    
var link='Link valid only for the last 30 days';




		for (var clave in feature.properties) {






			var title = bankias.getPropertyTitle(clave);
			popup += '<b>'+title+'</b><br />'+feature.properties[clave]+'<br /><br />';
popup1 =popup+'<a href="http://cnt.rm.ingv.it/data_id/'+feature.properties.code+'/event.html">'+link+'</a>';


		}
		layer.bindPopup(popup1);
	},

	pointToLayer: function (feature, latlng) {
if (feature.properties.magnitude >=2){ customicon='pingiallo.png'};
if (feature.properties.magnitude >=3){ customicon='pinarancio.png'};
if (feature.properties.magnitude >=4){ customicon='pinarancioforte.png'};

if (feature.properties.magnitude >=5 ){ customicon='pinrosso.png'};
		return L.marker(latlng, {
			icon:L.icon({
				iconUrl: customicon,
				shadowUrl: 'marker-shadow.png',
				iconSize: [20,20],
				shadowSize:   [30, 30],
				shadowAnchor: [10, 18]
			})
		});
	},
	firstLineTitles: true
});

$.ajax ({
	type:'GET',
	dataType:'text',
	url:'elenco-eq3.csv',
   error: function() {
     alert('Non riesco a caricare i dati');
   },
	success: function(csv) {
      var cluster = new L.MarkerClusterGroup();
		bankias.addData(csv);
		cluster.addLayer(bankias);
		mapa.addLayer(cluster);
		mapa.fitBounds(cluster.getBounds());
	},
   complete: function() {
      $('#cargando').delay(500).fadeOut('slow');
   }
});


$('#localizame').click(function(e) {
	mapa.locate();
	$('#localizame').text('Localizzando...');
	mapa.on('locationfound', function(e) { 
		mapa.setView(e.latlng, 15);
		$('#localizame').text('Localizzato');
	});
});

//});
</script>

	</body>
</html>
