<?php

require 'connection.php';
require 'pagehandlers/pagehandler.php';

$con = new PDO('mysql:host=localhost;dbname=sayts', 'sayts', 'asd');
Connection::setConnection($con);

PageHandlers\PageHandler::setBaseDir('/media/Data/FH/weg/Sayts/');
?>
