(function($) {
  
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

}(jQuery));


