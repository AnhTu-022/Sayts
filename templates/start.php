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

    <script>
        var errorCallback = function(e) {
            showNotSupported();
        };

        navigator.getMedia = (navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia ||
                navigator.msGetUserMedia);

				if (!isFunction(navigator.getMedia))
				{
					showNotSupported()
				}
				else
				{
		      // Not showing vendor prefixes.
		      navigator.getMedia({video: true, audio: true}, function(localMediaStream) {
		          var video = document.querySelector('video');
		          video.src = window.URL.createObjectURL(localMediaStream);
		          video.volume = 0;

		          // Note: onloadedmetadata doesn't fire in Chrome when using it with getUserMedia.
		          // See crbug.com/110938.
		          video.onloadedmetadata = function(e) {
		              // Ready to go. Do some stuff.
		          };
		      }, errorCallback);
				}

				const c_visibleAngle = 10;
        var currentGeoObjects = Array();
				var currentLat = null;
				var currentLong = null;
				
        document.body.onload = function()
        {
  				if (!window.DeviceOrientationEvent || !navigator.geolocation) {
	  				showNotSupported();
  					return;
  				}
  				
      		loadGeoObjects();
      		
          window.addEventListener('deviceorientation', function(eventData)
          {
	          displayGeoObjects(currentGeoObjects, eventData.alpha, geoObjectInViewCondition);
          }, false);
        }


        // Phase 1: get position
        // Phase 2: ajax request
        // Phase 3: store in global field
				function loadGeoObjects()
				{
				  navigator.geolocation.getCurrentPosition(gotPosition);
				}
				
				function gotPosition(position)
				{
          var lat = 16.37;
          var long = 48.24;

          var long = position.coords.latitude;
          var lat = position.coords.longitude;
          
          currentLat = lat;
          currentLong = long;
          ajaxRequest('index.php?action=geoobjects&lat=' + lat + '&long=' + long);


				}

        function resultFunction(result)
        {
          /*var geoobjectsTest = document.getElementById('geoobjectsTest');

          for (var i = 0; i < result.data.length; i++)
          {
            var geoobject = result.data[i];
            geoobjectsTest.innerHTML = geoobjectsTest.innerHTML + '<option value="' + geoobject.id + '">' + geoobject.fid + '</option>';
          }*/

					currentGeoObjects = result.data;

          displayGeoObjects(result.data, 0, geoObjectInViewCondition);
        }

        function ajaxRequest(url)
        {
          var xmlhttp = new XMLHttpRequest();
          xmlhttp.open("GET", url, true);
          xmlhttp.onreadystatechange = readyStateChangeProxy(xmlhttp, resultFunction);
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
        
        function geoObjectInViewCondition(geoobject, angle)
        {
        	return (Math.abs(geoobject.angle - angle) < c_visibleAngle);
        }
        
        function displayGeoObjects(geoobjects, angle, condition)
        {
          if (geoobjects == undefined || geoobjects == null || currentLat == null || currentLong == 0)
            return;

        	var width = window.innerWidth;
      		var container = document.getElementById("geoObjectsContainer");
      		container.innerHTML = ""; // clear all childs

					angle = angle < 180 ? angle : 360 - angle; // max angle value from server = 180
      		
        	for(var i=0; i<geoobjects.length; i++)
        	{
        		var go = geoobjects[i];
						if (condition(go, angle))
						{
							
						
		      		var div = document.createElement("div");
		      		div.className = "geoObject";
		      		//div.style.left = Math.round((go.angle - angle + 10) * width / 20) + "px";
		      		div.style.left = (Math.round(width/2 + (go.angle - angle)/c_visibleAngle*width/2)-150) + "px";
		      		
		      		var icon = document.createElement("img");
		      		icon.className = "geoIcon";
		      		icon.src = "?action=icon&id=" + go.typeId;
		      		div.appendChild(icon);
		      		
		      		div.innerHTML += "<span class=\"geoobjectType\">" + go.type + "<br />" + go.address + " " + go.city + "</span>";
		      		container.appendChild(div);
        		}
        	}
        }
        
        function showNotSupported()
        {
        	document.getElementById("notSupported").style.display = "block";
        }
        
        function isFunction(obj)
        {
        	return !!(obj && obj.constructor && obj.call && obj.apply);
        }
    </script>
</div>

<?php include 'footer.php' ?>
