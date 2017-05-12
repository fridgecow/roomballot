<?php

require_once "php/common.php";

if (auth(true)) {
    $minYear = $db->fetch("allocations", null, "`year` ASC", array(1));
    $minYear = intval($minYear[0]["year"]);
    $maxYear = $db->fetch("allocations", null, "`year` DESC", array(1));
    $maxYear = intval($maxYear[0]["year"]);
    $endYear = max($maxYear, intval(date("Y")));
    // if year not set, or old and no allocations, or new and no allocations and a future year, then redirect to max
    if (!is_numeric($_GET["_yr"]) || (intval($_GET["_yr"]) > intval(date("Y")) && count($db->fetch("allocations", "`year` = " . $_GET["_yr"], null, array(1))) === 0)) {
        header("Location: /" . $maxYear . "#home");
        die();
    }
    $year = intval($_GET["_yr"]);
} else {
    if ($_GET["_yr"]) {
        header("Location: /#home");
        die();
    }
}
?><!DOCTYPE html>
<html>
    <?php header(); ?>

    <?php bodyTop(); ?>

    <h1>Welcome!</h1>
    <p>This site aims to be a definitive source of information on all rooms available for students at Robinson College.</p>
    <p>For help using this site, more details on choosing rooms, or to report any issues, please email <a href="mailto:rooms@rcsa.co.uk">rooms@rcsa.co.uk</a>.
    <h2>Room ballot</h2>
    <p>The balloting process will take place around February, where the order of room choosing is decided.  You'll receive emails later on with the actual times.</p>
    <p>For first years, first decide who you want to choose rooms with (you can just choose on your own, or be in a group of up to 5 people).  Being in a group does not require you all to choose rooms in the same place, it just allows you to choose at the same time.  Note that if you're intending to choose a set (for 2 people) or flat (3 people), everyone involved must be in the same group.</p>
    <p>Write your name, plus anyone else in your group, on a piece of paper, and place it in the ballot box in the Porter's Lodge.  A few days after the ballot box closes, the drawing will take place to decide on the order with the year group.</p>
    <p>As room choosing approaches, a list of names and times will be posted in the JCR.  You must turn up to choose your room at the time given, otherwise you may miss your turn.</p>

    <?php bodyBottom(); ?>

</html>
