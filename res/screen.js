(function() {

    var mockJson = [
        {"id": "A", "x": "0", "y": "0"},
        {"id": "B", "x": "1", "y": "0"},
        {"id": "C", "x": "1", "y": "1"},
        {"id": "D", "x": "0", "y": "1"}
    ];

    var jsonResponse = null;
    var mainDiv = document.getElementById("mainDiv");
    mainDiv.innerHTML = '<h1>HEY</h1>';
    getData();

    function getData() {
        var ajaxCall = new XMLHttpRequest();
        var sqlQuery = "index.php?action=geoobjects&lat=16.37&long=48.24";
        ajaxCall.onreadystatechange = function() {
            if (4 === ajaxCall.readyState && 200 === ajaxCall.status) {
                if (null !== ajaxCall.responseText) {
                    jsonResponse = JSON.parse(ajaxCall.responseText);
                    console.log(jsonResponse);
                    mainDiv.innerHTML = ajaxCall.responseText;
                } else {
                    mainDiv.innerHTML = "json response was null";
                }
            }
        };
        ajaxCall.open("GET", sqlQuery, true);
        ajaxCall.send();
    }
    ;

}());
