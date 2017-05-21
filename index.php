<?php

require_once "app/ErrorHandler.php";
require_once "app/Layout.php";
require_once "app/Database.php";

// buffer the output
ob_start();

// if a page is not requested then serve the home page
if (isset($_GET["q"])) {
    $page = $_GET["q"];
} else {
    $page = "home";
}

Layout::HTMLheader("Fitz JCR Housing Ballot System");
Layout::HTMLnavbar();
$queryString = "SELECT *  FROM `pages` WHERE `name` LIKE '" . $page . "'";
$result = Database::getInstance()->query($queryString);
$row = $result->fetch_assoc();
Layout::HTMLcontent("Fitz JCR Housing Ballot System", $row["content"]);
Layout::HTMLfooter();

// return the buffered content all at once
ob_flush();

?>