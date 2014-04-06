<?php

namespace PageHandlers;

class CsvImportPageHandler extends PageHandler {

    public function handle() {
        $this->setPhpTemplate('csvupload');

        // if form not submitted
        if (!isset($_FILES['csvfile'])) {
            $this->cleanup();
            return $this;
        }

        if ($_FILES['csvfile']['type'] != 'text/csv' || $_FILES['csvfile']['error'] != UPLOAD_ERR_OK || !isset($_POST['name'])) {
            $this->fail('File not valid', null);
            return $this;
        }

        $icon = NULL;
        if (isset($_FILES['icon']) && isset($_FILES['icon']['tmp_name']) && $_FILES['icon']['tmp_name'] != '') {
            $file = fopen($_FILES['icon']['tmp_name'], 'r');
            if ($file !== false) {
                $icon = fread($file, filesize($_FILES['icon']['tmp_name']));
                fclose($file);
            }
        }

        // create new type
        $con = \Connection::getConnection();
        $con->beginTransaction();
        $stmt = $con->prepare('INSERT INTO types(name, originUrl, icon, description) VALUES (:name,:originUrl,:icon,:description)');
        if (!$stmt->execute(array(':name' => $_POST['name'], ':originUrl' => $_POST['originUrl'], ':icon' => $icon, ':description' => $_POST['description']))) {
            $this->fail('Failed to insert type', null);
            return $this;
        }
        $typeId = $con->lastInsertId();

        $titles = NULL;
        $file = fopen($_FILES['csvfile']['tmp_name'], 'r');
        if ($file === false) {
            $this->fail('Failed to open csv file', $typeId);
            return $this;
        }

        // get titles from csvs
        $values = fgetcsv($file);
        if ($values === false) {
            fclose($file);
            $this->fail('Error while reading csv', $typeId);
            return $this;
        }
        $fidCol = array_search('FID', $values);
        $pointCol = array_search('SHAPE', $values);
        $addressCol = array_search('STRNAM', $values);
        $descriptionCol = array_search('WEITERE_INFORMATION', $values);
        $austriaId = 167;
        $cityName = 'Wien';
//echo "fidCol: $fidCol\npointCol: $pointCol\naddressCol: $addressCol\ndescriptionCol: $descriptionCol\n";
        $stmt = $con->prepare('INSERT INTO geoobjects(point, typeId, address, city, countryId, fid, description) VALUES (PointFromText(:point), :typeId, :address, :city, :countryId, :fid, :description)');

        $line = 1;
        // import values
        while (($values = fgetcsv($file)) !== false) {
            $line++;
            /* echo "line $line:";
              var_dump($values); */
            $fid = $values[$fidCol];
            $point = $values[$pointCol];
            $address = $values[$addressCol];
            $description = $values[$descriptionCol];
            //echo "values:\nfid: $fid\npoint: $point\naddress: $address\ndescription: $description\ntypeId: $typeId\ncity: $cityName\ncountryId: $austriaId\n";
            if (!$stmt->execute(array(':point' => $point, ':typeId' => $typeId, ':address' => $address, ':city' => $cityName, ':countryId' => $austriaId, ':fid' => $fid, ':description' => $description))) {
                fclose($file);
                $this->fail('Failed to insert line from csv', $typeId);
                return $this;
            }
        }

        fclose($file);
        $con->commit();
        $this->setPageData('success', true);
        $this->cleanup();
        return $this;
    }

    private function fail($message, $typeId) {
        // MyISAM doesn't support transactions -> rollback manually
        if ($typeId != null) {
            $con = \Connection::getConnection();
            $stmt = $con->prepare('DELETE FROM geoobjects WHERE typeId=:typeId');
            $stmt->execute(array(':typeId' => $typeId));
            $stmt = $con->prepare('DELETE FROM types WHERE id=:id');
            $stmt->execute(array(':id' => $typeId));
        }
        //$con->rollBack();
        $this->setPageData('failed', true);
        $this->setPageData('errormessage', $message);
        $this->cleanup();
    }

    private function cleanup() {
        if (isset($_FILES['icon'])) {
            @unlink($_FILES['icon']['tmp_name']);
        }
        if (isset($_FILES['csvfile'])) {
            @unlink($_FILES['csvfile']['tmp_name']);
        }
    }

}
