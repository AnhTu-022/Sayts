<?php include 'header.php' ?>

<div id='notSupported' class='notSupported' style='display: none;'>
	<div>
		<span>Your browser doesn't support the needed HTML5 APIs. Please use an up-to-date version of Firefox or Chrome and allow access to the APIs when asked.</span>
	</div>
</div>

<div class='title flex_centered'>
    Welcome to Sayts!
</div>

<div class='flex_vert flex_centered' id='welcome' style='display: none'>
    <p>I will find your current position and display interesting places near it on a map.</p>
    <p>[Map]</p>
</div>

<div id="geoObjectsContainer"></div>

<!--<div>
    <div id="moAbsolute"></div>
    <div id="moAccel"></div>
    <div id="moRotation"></div>
    <div id="moInterval"></div>
    <script>
        /*if (window.DeviceMotionEvent) {
         window.addEventListener('devicemotion', deviceMotionHandler, false);
         } else {
         document.getElementById("dmEvent").innerHTML = "Not supported."
         }

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
        
        function getLocation()
				{
				if (navigator.geolocation)
					{
					navigator.geolocation.getCurrentPosition(showPosition);
					}
				else{x.innerHTML = "Geolocation is not supported by this browser.";}
				}
			function showPosition(position)
			{
			x.innerHTML = "Latitude: " + position.coords.latitude +
			"<br>Longitude: " + position.coords.longitude;
			}*/
    </script>
</div>-->

<div>
    <video autoplay height="250px"></video>

    <!--<select id="geoobjectsTest" size="10"></select>-->

    <script type="application/javascript" src="res/start.js"></script>
</div>

<?php include 'footer.php' ?>
