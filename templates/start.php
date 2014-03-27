<?php include 'header.php' ?>

<div class='title flex_centered'>
	Welcome to Sayts!
</div>

<div class='flex_vert flex_centered' id='welcome' style='display: none'>
	<p>I will find your current position and display interesting places near it on a map.</p>
	<p>[Map]</p>
</div>

<div>
<div id="moAbsolute"></div>
<div id="moAccel"></div>
<div id="moRotation"></div>
<div id="moInterval"></div>
<script>
/*if (window.DeviceMotionEvent) {
  window.addEventListener('devicemotion', deviceMotionHandler, false);
} else {
  document.getElementById("dmEvent").innerHTML = "Not supported."
}*/

if (window.DeviceOrientationEvent) {
  window.addEventListener('deviceorientation', deviceOrientation, false);
} else {
  document.getElementById("doEvent").innerHTML = "Not supported."
}

function deviceOrientation(eventData)
{
	var info, xyz = "[X,<br/> Y<br/>, Z<br/>]";

  // direction in degrees
  info = xyz.replace("X", eventData.alpha);
  info = info.replace("Y", eventData.beta);
  info = info.replace("Z", eventData.gamma);
  document.getElementById("moAbsolute").innerHTML = info;  
}

function deviceMotionHandler(eventData) {
  var info, xyz = "[X,<br/> Y<br/>, Z<br/>]";

  // Grab the acceleration from the results
  var acceleration = eventData.acceleration;
  info = xyz.replace("X", acceleration.x);
  info = info.replace("Y", acceleration.y);
  info = info.replace("Z", acceleration.z);
  document.getElementById("moAccel").innerHTML = info;

  // Grab the rotation rate from the results
  var rotation = eventData.rotationRate;
  info = xyz.replace("X", rotation.alpha);
  info = info.replace("Y", rotation.beta);
  info = info.replace("Z", rotation.gamma);
  document.getElementById("moRotation").innerHTML = info;

  // // Grab the refresh interval from the results
  info = eventData.interval;
  document.getElementById("moInterval").innerHTML = info;       
}
</script>
</div>

<div>
<video autoplay height="250px"></video>

<select id="geoobjectsTest" size="10"></select>

<script>
  var errorCallback = function(e) {
    console.log('Reeeejected!', e);
  };

	navigator.getMedia = ( navigator.getUserMedia ||
                       navigator.webkitGetUserMedia ||
                       navigator.mozGetUserMedia ||
                       navigator.msGetUserMedia);

  // Not showing vendor prefixes.
  navigator.getMedia({video: true, audio: true}, function(localMediaStream) {
    var video = document.querySelector('video');
    video.src = window.URL.createObjectURL(localMediaStream);

    // Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
    // See crbug.com/110938.
    video.onloadedmetadata = function(e) {
      // Ready to go. Do some stuff.
    };
  }, errorCallback);
  

document.body.onload = function()
{
	//TODO: get data from geoposition api
	
	var lat = 16.37;
	var long = 48.24;
	
	ajaxRequest('index.php?action=geoobjects&lat='+lat+'&long='+long);
}

function resultFunction(result)
{
	var geoobjectsTest = document.getElementById('geoobjectsTest');

	for(var i=0; i<result.data.length; i++)
	{
		var geoobject = result.data[i];
		geoobjectsTest.innerHTML = geoobjectsTest.innerHTML + '<option value="'+geoobject.id+'">'+geoobject.fid+'</option>';
	}
}

function ajaxRequest(url)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET", url, true);
    xmlhttp.onreadystatechange = readyStateChangeProxy(xmlhttp, resultFunction);
    //xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8");
    xmlhttp.send();
}

function readyStateChangeProxy(ajaxObject, resultFunction)
{
    return function()
    {
        if (ajaxObject.readyState == 4 && ajaxObject.status == 200)
        {
            var result;
            if (ajaxObject.responseText.length > 0)
                result = eval("(" + ajaxObject.responseText + ")");
            resultFunction(result);
        }
        else if (ajaxObject.readyState == 4 && ajaxObject.status != 200)
        {
            alert("HTTP error " + ajaxObject.status + ": " + ajaxObject.responseText);
        }
    }
}
</script>
</div>

<?php include 'footer.php' ?>
