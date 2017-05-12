<?
require_once "php/common.php";
if (array_key_exists("editRoom", $_GET)) {
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
    reqAuth(true);
    $rooms = $db->fetch("rooms", "`type` <> \"Unavailable\"");
    $allocs = $db->fetch("allocations", "`year` = " . $_GET["_yr"], "room ASC");
    $alloc = 0;
    $out = array();
    foreach ($rooms as $room) {
        if ($allocs[$alloc]["room"] == $room["id"]) {
            $out[$room["id"]] = $allocs[$alloc]["name"];
            $alloc++;
        } else $out[$room["id"]] = null;
    }
    print(json_encode($out));
} elseif (array_key_exists("allocate", $_GET)) {
    reqAuth(true, true);
    $room = $db->fetch("rooms", "`id` = " . $db->escape($_POST["room"]));
    if (count($room)) {
        $alloc = $db->fetch("allocations", "`room` = " . $room[0]["id"] . " AND `year` = " . $_GET["_yr"]);
        $name = trim($_POST["name"]);
        if (count($alloc)) {
            if ($name === "") $db->delete("allocations", "`id` = " . $alloc[0]["id"]);
            elseif ($name !== $alloc["name"]) $db->update("allocations", "`id` = " . $alloc[0]["id"], array("name" => $_POST["name"]));
        } else if ($name) $db->insert("allocations", array("room" => intval($room[0]["id"]), "year" => intval($_GET["_yr"]), "name" => $_POST["name"]));
    } else err(400, "The specified room doesn't exist.");
} else err(404, "An unknown API endpoint was requested.");
