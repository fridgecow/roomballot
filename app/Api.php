<?php

require_once "app/common.php";

if (array_key_exists("info", $_GET)) {
    reqAuth(true);
    $room = $db->fetch("rooms", "`id` = " . $db->escape($_POST["room"]));
    if (count($room)) {
        $vals = array();
        foreach (array("desc", "bathroom", "storage", "wifi", "sockets", "view", "facing", "balcony") as $field) {
            $vals[$field] = $_POST[$field];
            if ($vals[$field] === "") $vals[$field] = null;
        }
        $vals["sink"] = ($_POST["sink"] === "" ? null : ($_POST["sink"] === "Yes" ? 1 : 0));
        $db->update("rooms", "`id` = " . $room[0]["id"], $vals);
    } else err(400, "The specified room doesn't exist.");
} elseif (array_key_exists("allocations", $_GET)) {

} elseif (array_key_exists("allocate", $_GET)) {

} else err(404, "An unknown API endpoint was requested.");


switch (gettype($val)) {
