<?php

$url = 'http://openmap.rm.ingv.it/gmaps/rec/files/last90days_events.csv';
$file = "elenco-eq.csv";
$fileok = "elenco-eq1.csv";
$fileok1 = "elenco-eq2.csv";
$fileok2 = "elenco-eq3.csv";

$src = fopen($url, 'r');

$dest = fopen($file, 'w');
stream_copy_to_stream($src, $dest);


$search="Lat";
$replace="lat";

$output = passthru("sed -e 's/$search/$replace/g' $file > $fileok");

$search1="Lon";
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
		#botonera1 {
			position:fixed;
			top:40px;
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
			padding: 3px 3px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			-webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
			-moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
			box-shadow: rgba(0,0,0,1) 0 1px 0;
			text-shadow: rgba(0,0,0,.4) 0 1px 0;
			color: white;
			font-size: 13px;
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
.boton1 {
			border: 1px solid #96d1f8;
			background: #65a9d7;
			background: -webkit-gradient(linear, left top, left bottom, from(#3e779d), to(#65a9d7));
			background: -webkit-linear-gradient(top, #3e779d, #65a9d7);
			background: -moz-linear-gradient(top, #3e779d, #65a9d7);
			background: -ms-linear-gradient(top, #3e779d, #65a9d7);
			background: -o-linear-gradient(top, #3e779d, #65a9d7);
			padding: 3px 3px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			-webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
			-moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
			box-shadow: rgba(0,0,0,1) 0 1px 0;
			text-shadow: rgba(0,0,0,.4) 0 1px 0;
			color: white;
			font-size: 13px;
			/*font-family: Helvetica, Arial, Sans-Serif;*/
			text-decoration: none;
			vertical-align: middle;
		}
		.boton1:hover {
			border-top-color: #28597a;
			background: #28597a;
			color: #ccc;
		}
		.boton1:active {
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
div.textclass {
           
           position: absolute;
            left: 15px;
            top: 15px;
            width: 150px;
            height: 309px;
            vertical-align: middle;
            text-align: center;
            font-family: Georgia, "Times New Roman", Times, serif;
            font-style: italic;
            padding: 1em 0 1em 0;           
}
div.circlered {
	/* IE10 */
background-image: -ms-linear-gradient(top right, red 0%, black 100%);
 
/* Mozilla Firefox */
background-image: -moz-linear-gradient(top right, red 0%, black 100%);
 
/* Opera */
background-image: -o-linear-gradient(top right, red 0%, black 100%);
 
/* Webkit (Safari/Chrome 10) */
background-image: -webkit-gradient(linear, right top, left bottom, color-stop(0, red), color-stop(1,black));
 
/* Webkit (Chrome 11+) */
background-image: -webkit-linear-gradient(top right, red 0%, black 100%);
 
/* Regola standard */
background-image: linear-gradient(top right, red 0%, black 100%);
    background-color: red;
    border-color: black;
    border-radius: 50px;
    border-style: solid;
    border-width: 1px;
	font-color: white;
    width:5px;
    height:5px;
}
div.circleorange {
	/* IE10 */
background-image: -ms-linear-gradient(top right, orange 0%, black 100%);
 
/* Mozilla Firefox */
background-image: -moz-linear-gradient(top right, orange 0%, black 100%);
 
/* Opera */
background-image: -o-linear-gradient(top right, orange 0%, black 100%);
 
/* Webkit (Safari/Chrome 10) */
background-image: -webkit-gradient(linear, right top, left bottom, color-stop(0, orange), color-stop(1, black));
 
/* Webkit (Chrome 11+) */
background-image: -webkit-linear-gradient(top right, orange 0%, black 100%);
 
/* Regola standard */
background-image: linear-gradient(top right, orange 0%, black 100%);
    background-color: orange;
    border-color: black;
    border-radius: 50px;
    border-style: solid;
    border-width: 1px;
    width:5px;
    height:5px;
}
div.circleyellow {
	/* IE10 */
background-image: -ms-linear-gradient(top right, yellow 0%, black 100%);
 
/* Mozilla Firefox */
background-image: -moz-linear-gradient(top right, yellow 0%, black 100%);
 
/* Opera */
background-image: -o-linear-gradient(top right,yellow 0%, black 100%);
 
/* Webkit (Safari/Chrome 10) */
background-image: -webkit-gradient(linear, right top, left bottom, color-stop(0, yellow), color-stop(1, black));
 
/* Webkit (Chrome 11+) */
background-image: -webkit-linear-gradient(top right, yellow 0%, black 100%);
 
/* Regola standard */
background-image: linear-gradient(top right, yellow 0%, black 100%);
    background-color: yellow;
    border-color: black;
    border-radius: 50px;
    border-style: solid;
    border-width: 1px;
    width:5px;
    height:5px;
}
div.circlewhite {
		/* IE10 */
background-image: -ms-linear-gradient(top right, white 0%, black 100%);
 
/* Mozilla Firefox */
background-image: -moz-linear-gradient(top right, white 0%, black 100%);
 
/* Opera */
background-image: -o-linear-gradient(top right, white 0%, black 100%);
 
/* Webkit (Safari/Chrome 10) */
background-image: -webkit-gradient(linear, right top, left bottom, color-stop(0, white), color-stop(1, black));
 
/* Webkit (Chrome 11+) */
background-image: -webkit-linear-gradient(top right, white 0%, black 100%);
 
/* Regola standard */
background-image: linear-gradient(top right, white 0%, black 100%);
    background-color: white;
    border-color: black;
    border-radius: 50px;
    border-style: solid;
    border-width: 1px;
    width:5px;
    height:5px;
}
		</style>
	</head>
	<body>
		<div id="mapa"></div>
		<div id="cargando">Loading data...</div>

<div id="infodiv" style="leaflet-popup-content-wrapper">
<b>Earthquakes last 90 days until the last registrated</b>
<b>. Magnitudo: </b>>=2 <img src="pingiallo.png" width="8" height="8">  >=3 <img src="pinarancio.png" width="8" height="8">  >=4 <img src="pinarancioforte.png" width="8" height="8">  >=5 <img src="pinrossoe.png" width="8" height="8"> | by @Piersoft with data from <a href="http://openmap.rm.ingv.it/gmaps/rec/files/last90days_events.csv">INGV</a> | Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors
</div>

<div id="botonera">
<button onClick="reply_click(this.id)" class="boton" id="localizame" >Magnitudo >=5 (click twice) </button>
		</div>
<div id="botonera1">
<button onClick="reply_click1(this.id)" class="boton1" id="localizame" >Magnitudo >=3 (click twice) </button>
		</div>

<script>




//;$(function() {


mapa = L.map('mapa', {attributionControl:false}).setView([40.46, -3.75], 5);

var baseLayer = L.tileLayer('http://tile.cloudmade.com/82e1a1bab27244f0ab6a3dd1770f7d11/999/256/{z}/{x}/{y}.png', {maxZoom: 19, attribution: 'Map Data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors | <a href="http://dati.comune.matera.it">OpenData Matera</a>'});

baseLayer.addTo(mapa)

var prccEarthquakesLayer = L.tileLayer('http://{s}.tiles.mapbox.com/v3/bclc-apec.map-rslgvy56/{z}/{x}/{y}.png', {
		attribution: 'Map &copy; Pacific Rim Coordination Center (PRCC).  Certain data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
	});

var layerControl = new L.Control.Layers({
		'Cloudmade': baseLayer,
		'PRCC Earthquake Risk Zones': prccEarthquakesLayer 
	});

layerControl.addTo(mapa);

var customicon = 'pingrigio.png';

var button= "YES";
var button1= "YES";

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
var classs='';
if (feature.properties.magnitude >=2){classs='circlewhite'};
if (feature.properties.magnitude >=3){ classs='circleyellow'};
if (feature.properties.magnitude >=4){ classs='circleorange'};

if (feature.properties.magnitude >=5 ){ classs='circlered'};
		return L.marker(latlng, 
			 { 
		icon : L.divIcon({ 
			className : classs,
                      iconSize : [20*feature.properties.magnitude/3,20*feature.properties.magnitude/3],
html: '<div style="display: table; height:'+20*feature.properties.magnitude/3+'px; overflow: hidden; "><div align="center" style="display: table-cell; vertical-align: middle;"><div style="width:'+20*feature.properties.magnitude/3+'px;"><font color="white">'+feature.properties.magnitude+'</font></div></div></div>'}),
                      title: '<div>'+feature.properties.magnitude+'</div>'});



	},
	firstLineTitles: true
});

var bankias3 = L.geoCsv(null, {
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
filter: function(feature, layer) {

if (feature.properties.magnitude >= 3) {
					
return true;
				}
	return false;

    },

	pointToLayer: function (feature, latlng) {
var classs='';
if (feature.properties.magnitude >=2){classs='circlewhite'};
if (feature.properties.magnitude >=3){ classs='circleyellow'};
if (feature.properties.magnitude >=4){ classs='circleorange'};

if (feature.properties.magnitude >=5 ){ classs='circlered'};
		return L.marker(latlng, 
			 { 
		icon : L.divIcon({ 
			className : classs,
                      iconSize : [20*feature.properties.magnitude/3,20*feature.properties.magnitude/3],
html: '<div style="display: table; height:'+20*feature.properties.magnitude/3+'px; overflow: hidden; "><div align="center" style="display: table-cell; vertical-align: middle;"><div style="width:'+20*feature.properties.magnitude/3+'px;"><font color="white">'+feature.properties.magnitude+'</font></div></div></div>'}),
                      title: '<div>'+feature.properties.magnitude+'</div>'});

        


	},
	firstLineTitles: true
});

var bankias2 = L.geoCsv(null, {
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
filter: function(feature, layer) {

if (feature.properties.magnitude >= 5) {
					
return true;
				}
	return false;

    },

	pointToLayer: function (feature, latlng) {
var classs='';
if (feature.properties.magnitude >=2){classs='circlewhite'};
if (feature.properties.magnitude >=3){ classs='circleyellow'};
if (feature.properties.magnitude >=4){ classs='circleorange'};

if (feature.properties.magnitude >=5 ){ classs='circlered'};
		return L.marker(latlng, 
			 { 
		icon : L.divIcon({ 
			className : classs,
                      iconSize : [20*feature.properties.magnitude/3,20*feature.properties.magnitude/3],
html: '<div style="display: table; height:'+20*feature.properties.magnitude/3+'px; overflow: hidden; "><div align="center" style="display: table-cell; vertical-align: middle;"><div style="width:'+20*feature.properties.magnitude/3+'px;"><font color="white">'+feature.properties.magnitude+'</font></div></div></div>'}),
                      title: '<div>'+feature.properties.magnitude+'</div>'});



	},
	firstLineTitles: true
});


 var cluster='';

 var cluster1='';
 var cluster2='';


$.ajax ({
	type:'GET',
	dataType:'text',
	url:'elenco-eq3.csv',
   error: function() {
     alert('Non riesco a caricare i dati');
   },
	success: function(csv) {
      cluster = new L.MarkerClusterGroup();
		bankias.addData(csv);
		cluster.addLayer(bankias);
		mapa.addLayer(cluster);
		mapa.fitBounds(cluster.getBounds());
	},
   complete: function() {
      $('#cargando').delay(500).fadeOut('slow');
   }
});


function reply_click(clicked_id)
{

if (button == "YES"){


   // alert(clicked_id);

$.ajax ({
	type:'GET',
	dataType:'text',
	url:'elenco-eq3.csv',
   error: function() {
     alert('Unable to load data');
   },
     success: function(csv) {
          cluster1 = new L.MarkerClusterGroup();
		bankias2.addData(csv);
		cluster1.addLayer(bankias2);
		

	},
   complete: function() {

mapa.addLayer(cluster1);
mapa.fitBounds(cluster1.getBounds());

mapa.removeLayer(cluster);
mapa.removeLayer(cluster2);

button="NO";
$('#botonera').delay(100).fadeOut('slow');
      $('#cargando').delay(100).fadeOut('slow');


   }
});




}
}


function reply_click1(clicked_id)
{

if (button1 == "YES"){


   // alert(clicked_id);

$.ajax ({
	type:'GET',
	dataType:'text',
	url:'elenco-eq3.csv',
   error: function() {
     alert('Unable to load data');
   },
     success: function(csv) {
          cluster2 = new L.MarkerClusterGroup();
		bankias3.addData(csv);
		cluster2.addLayer(bankias3);
		

	},
   complete: function() {

mapa.addLayer(cluster2);
mapa.fitBounds(cluster2.getBounds());

mapa.removeLayer(cluster);
mapa.removeLayer(cluster1);


button1="NO";
$('#botonera1').delay(100).fadeOut('slow');
      $('#cargando').delay(100).fadeOut('slow');

   }
});




}
}




//});
</script>

	</body>
</html>
