<?php

namespace PageHandlers;

class GeoObjectsPageHandler extends PageHandler {

    public function handle() {
        $this->setAjaxTemplate('geoobjects');

        if (!isset($_GET['lat']) || !isset($_GET['long'])) {
            return $this;
        }

				$lat = $_GET['lat'];
				$long = $_GET['long'];
        // 0.01 degrees = 100 - 1100m, depending on latitude
        $long1 = $long - 0.01;
        $long2 = $long + 0.01;
        $lat1 = $lat - 0.01;
        $lat2 = $lat + 0.01;

        $con = \Connection::getConnection();
        $stmt = $con->prepare("SELECT geoobjects.id id, X(point) x, Y(point) y, types.name type, address, city, countries.name country, fid, geoobjects.description description, originUrl, types.id typeId, types.description typeDescription, " .
        "degrees(acos((((X(point)-:latA)*(:latB))+((Y(point)-:longA)*(90-:longB)))/(sqrt(pow((X(point)-:latC),2)+pow((Y(point)-:longC),2))*sqrt(pow((:latD),2)+pow((90-:longD),2))))) angle " .
                "FROM geoobjects " .
                "JOIN types ON types.id = typeId " .
                "JOIN countries ON countries.id = countryId " .
                "WHERE MBRContains(" .
                "LINESTRING(" .
                "POINT(:lat1_1,:long1_1)," .
                "POINT(:lat1_2,:long2_1)," .
                "POINT(:lat2_1,:long2_2)," .
                "POINT(:lat2_2,:long1_2)," .
                "POINT(:lat1_3,:long1_3)" .
                ")" .
                ", point)");

 
        $stmt->execute(array(':latA' => $lat, ':latB' => $lat, ':latC' => $lat, ':latD' => $lat, ':longA' => $long, ':longB' => $long, ':longC' => $long, ':longD' => $long, 
        	':long1_1' => $long1, ':long1_2' => $long1, ':long1_3' => $long1, ':long2_1' => $long2, ':long2_2' => $long2,
        	':lat1_1' => $lat1, ':lat1_2' => $lat1, ':lat1_3' => $lat1, ':lat2_1' => $lat2, ':lat2_2' => $lat2));
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($results == false)
            $results = array();

        $this->setPageData('geoobjects', $results);

        return $this;
    }

    public function loginRequired() {
        return false;
    }

}
