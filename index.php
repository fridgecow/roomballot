<?php

require_once "app/common.php";

// if (auth(true)) {
//     $minYear = $db->fetch("allocations", null, "`year` ASC", array(1));
//     $minYear = intval($minYear[0]["year"]);
//     $maxYear = $db->fetch("allocations", null, "`year` DESC", array(1));
//     $maxYear = intval($maxYear[0]["year"]);
//     $endYear = max($maxYear, intval(date("Y")));
//     // if year not set, or old and no allocations, or new and no allocations and a future year, then redirect to max
//     if (!is_numeric($_GET["_yr"]) || (intval($_GET["_yr"]) > intval(date("Y")) && count($db->fetch("allocations", "`year` = " . $_GET["_yr"], null, array(1))) === 0)) {
//         header("Location: /" . $maxYear . "#home");
//         die();
//     }
//     $year = intval($_GET["_yr"]);
// } else {
//     if ($_GET["_yr"]) {
//         header("Location: /#home");
//         die();
//     }
// }
?><!DOCTYPE html>
<html>
    <?php head(); ?>

    <?php bodyTop(); ?>

            <h1>Fitz JCR Housing Ballot System</h1>
            <p>For security reasons, we request that you keep information regarding room rents and photographs of College-owned rooms strictly confidential to the members of Fitzwilliam College. If anyone is known to have violated this rule, all information will be withdrawn and the person in question will be referred to the Senior Tutor and the Dean.</p>
            <p>If you have any problem with the housing ballot you should contact the JCR Vice President at <a href="mailto:jcr.vice.president@fitz.cam.ac.uk">jcr.vice.president@fitz.cam.ac.uk</a>. To report any technical issues with this website you are invited to contact the JCR Website Officer in confidence at <a href="mailto:jcr.website@fitz.cam.ac.uk">jcr.website@fitz.cam.ac.uk</a>.</p>

    <?php bodyBottom(); ?>

</html>
