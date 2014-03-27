<?php

namespace PageHandlers;

class GeoObjectsPageHandler extends PageHandler {

    public function handle() {
        $this->setAjaxTemplate('geoobjects');

        if (!isset($_GET['lat']) || !isset($_GET['long'])) {
            return $this;
        }

        $long1 = $_GET['long'] - 0.01;
        $long2 = $_GET['long'] + 0.01;
        $lat1 = $_GET['lat'] - 0.01;
        $lat2 = $_GET['lat'] + 0.01;

        $con = \Connection::getConnection();
        $stmt = $con->prepare("SELECT geoobjects.id id, X(point) x, Y(point) y, types.name type, address, city, countries.name country, fid, geoobjects.description description, originUrl, HEX(icon) icon, types.description typeDescription " .
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

        // 0.01 degrees = 100 - 1100m, depending on latitude
        $stmt->execute(array(':long1_1' => $long1, ':long1_2' => $long1, ':long1_3' => $long1, ':long2_1' => $long2, ':long2_2' => $long2,
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
