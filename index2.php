<?
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
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="Richard Allitt">
        <meta name="title" content="RCSA Rooms Database">
        <meta name="description" content="A definitive source of information on all rooms available for students at Robinson College.">
<?
if (!array_key_exists("desktop", $_GET)) {
?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<?
}
?>
        <title>RCSA Rooms Database</title>
        <link href="/img/icon.ico" rel="shortcut icon">
        <link href="/css/bootstrap.min.css" rel="stylesheet">
        <link href="/css/bootstrap-theme.min.css" rel="stylesheet">
        <link href="/css/style.css" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#nav-collapse">
                        <span class="sr-only">Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="/img/logo.png"> Rooms</a>
                </div>
                <div id="nav-collapse" class="collapse navbar-collapse">
                    <ul id="pages" class="nav navbar-nav navbar-right" role="menu">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#overview">Overview</a></li>
                        <li><a href="#available">Rooms Available</a></li>
                        <li><a href="#maps">Maps</a></li>
                        <li><a href="#allocated">Allocated</a></li>
<?
if (auth()) {
    if (auth(true)) {
        $yearStr = $year . "-" . substr($year + 1, 2);
?>
                        <li class="dropdown">
                            <a href class="dropdown-toggle" data-toggle="dropdown"><?=$yearStr?> <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
<?
        for ($i = $endYear; $i >= $minYear; $i--) {
?>
                                <li><a href="/<?=$i?>#home"><?=$i?> &ndash; <?=($i + 1)?></a></li>
<?
        }
?>
                            </ul>
                        </li>
<?
    }
    $tag = ($_SESSION["admin"] ? " [Admin]" : ($_SESSION["member"] ? "" : " [Guest]"));
?>
                        <p class="navbar-text"><?=$_SESSION["user"]?><?=$tag?></p>
                        <li><a href="/logout">Logout</a></li>
<?
} else {
?>
                        <li><a href="/login">Login with Raven</a></li>
<?
}
?>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="loading" class="container-fluid">
            <div class="alert alert-info">
                <p>Just a moment...</p>
            </div>
        </div>
        <div id="home" class="container-fluid page">
<?
if ($_SESSION["msg"]) {
?>
            <div class="alert alert-<?=$_SESSION["msg"][0]?>">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <p><?=$_SESSION["msg"][1]?></p>
            </div>
<?
    $_SESSION["msg"] = null;
}
?>
            <h1>Welcome!</h1>
            <p>This site aims to be a definitive source of information on all rooms available for students at Robinson College.</p>
            <p>For help using this site, more details on choosing rooms, or to report any issues, please email <a href="mailto:rooms@rcsa.co.uk">rooms@rcsa.co.uk</a>.
            <h2>Room ballot</h2>
            <p>The balloting process will take place around February, where the order of room choosing is decided.  You'll receive emails later on with the actual times.</p>
            <p>For first years, first decide who you want to choose rooms with (you can just choose on your own, or be in a group of up to 5 people).  Being in a group does not require you all to choose rooms in the same place, it just allows you to choose at the same time.  Note that if you're intending to choose a set (for 2 people) or flat (3 people), everyone involved must be in the same group.</p>
            <p>Write your name, plus anyone else in your group, on a piece of paper, and place it in the ballot box in the Porter's Lodge.  A few days after the ballot box closes, the drawing will take place to decide on the order with the year group.</p>
            <p>As room choosing approaches, a list of names and times will be posted in the JCR.  You must turn up to choose your room at the time given, otherwise you may miss your turn.</p>
<?
if (auth(true, true)) {
    $group = posix_getgrnam("rcsarooms");
    $members = implode("</code>, <code>", $group["members"]);
?>
            <h2>Administration</h2>
            <p>The current admins are: <code><?=$members?></code>.</p>
            <p>To add more admins, you will need to email <a href="mailto:soc-srcf-admin@lists.cam.ac.uk">the SRCF sysadmins</a> with their CRSids, so they can be added to the <code>rcsarooms</code> society account.  They will first need to sign up for a <a href="https://www.srcf.net/signup/">personal SRCF account</a> if they don't have one already.</p>
            <p>This will also provide access to the code for this website, which is at <code>/societies/rcsarooms/public_html</code> on the SRCF filespace.  Database administration can be done from <a href="https://www.srcf.net/phpmyadmin/">phpMyAdmin</a> &ndash; login with your own MySQL username and password, and you should have access to the <code>rcsarooms</code> tables.</p>
            <p>The list of admins (ie. those who can change the allocated list hard coded in auth.php. You still have to do it the SRCF way if you want to be able to edit the code</p>
<?
}
?>
        </div>
        <div id="overview" class="container-fluid page">
            <h1>Rooms overview</h1>
            <p>This table lists all rooms available to undergrads, along with their location, current occupant (if logged in as a Robinson member) and various properties.  Note that most of these are opinion-based &ndash; the data comes from previous occupants and people's views may differ from one another!</p>
            <p>Fields with a "&mdash;" haven't been filled in yet &ndash; if you're in that room, or have been there before, maybe you can help describe it?</p>
<?
$desktop = (array_key_exists("desktop", $_GET) ? "" : '  If you\'re on a phone or something with a small screen, you can also try <a href="?desktop#overview">forcing desktop mode</a>.');
?>
            <p id="overview-prompt">Some fields have been hidden to avoid horizontal scrolling.  You can <a href>show all of them</a> if you wish, but it may make the page difficult to navigate.<?=$desktop?></p>
            <table class="table table-hover overview-responsive">
<?
function disp($x, $yn=false) {
    return is_null($x) ? "&mdash;" : ($yn ? ($x ? "Yes" : "No") : htmlspecialchars($x));
}
$span = (auth(true) ? 12 : 11);
foreach ($db->fetch("groups") as $group) {
?>
                <tr class="group">
                    <th colspan=<?=$span?>>
                        <h2><?=$group["name"]?> <small><?=$group["location"]?></small></h2>
                    </th>
                </tr>
                <tr class="headings">
                    <th>Room</th>
                    <th>Type</th>
                    <th>Floor</th>
                    <th>Bathroom</th>
                    <th>Sink</th>
                    <th>Storage</th>
                    <th>Wi-Fi</th>
                    <th>Sockets</th>
                    <th>View</th>
                    <th>Facing</th>
                    <th>Balcony</th>
<?
    if (auth(true)) {
?>
                     <th>Occupant</th>
<?
    }
?>
                </tr>
<?
    foreach ($db->fetch("rooms", "`group` = " . $group["id"]) as $room) {
        $trClass = ($room["type"] === "Unavailable" ? "unavailable" : "room");
        $type = $room["type"] . ($room["special"] === "None" ? "" : " (" . $room["special"] . ")");
?>
                <tr class="<?=$trClass?>" data-room="<?=$room["id"]?>" data-desc="<?=disp($room["desc"])?>">
                    <td><?=disp($room["name"])?></td>
                    <td><?=disp($type)?></td>
                    <td><?=disp($room["floor"])?></td>
                    <td><?=disp($room["bathroom"])?></td>
                    <td><?=disp($room["sink"], true)?></td>
                    <td><?=disp($room["storage"])?></td>
                    <td><?=disp($room["wifi"])?></td>
                    <td><?=disp($room["sockets"])?></td>
                    <td><?=disp($room["view"])?></td>
                    <td><?=disp($room["facing"])?></td>
                    <td><?=disp($room["balcony"])?></td>
<?
        if (auth(true)) {
            $alloc = $db->fetch("allocations", "`room` = " . $room["id"] . " AND `year` = " . $year);
?>
                    <td><?=disp($alloc[0]["name"])?></td>
<?
        }
?>
                </tr>
<?
    }
?>
<?
}
?>
            </table>
        </div>
        <div id="available" class="container-fluid page">
            <h1>Rooms available</h1>
            <p>This table lists all rooms available to undergrads, along with their location, current occupant (if logged in as a Robinson member) and various properties.  Note that most of these are opinion-based &ndash; the data comes from previous occupants and people's views may differ from one another!</p>
            <p>Fields with a "&mdash;" haven't been filled in yet &ndash; if you're in that room, or have been there before, maybe you can help describe it?</p>
<?
$desktop = (array_key_exists("desktop", $_GET) ? "" : '  If you\'re on a phone or something with a small screen, you can also try <a href="?desktop#overview">forcing desktop mode</a>.');
?>
            <p id="overview-prompt">Some fields have been hidden to avoid horizontal scrolling.  You can <a href>show all of them</a> if you wish, but it may make the page difficult to navigate.<?=$desktop?></p>
            <table class="table table-hover overview-responsive">
<?
foreach ($db->fetch("groups") as $group) {
?>
                <tr class="group">
                    <th colspan=12>
                        <h2><?=$group["name"]?> <small><?=$group["location"]?></small></h2>
                    </th>
                </tr>
                <tr class="headings">
                    <th>Room</th>
                    <th>Type</th>
                    <th>Floor</th>
                    <th>Bathroom</th>
                    <th>Sink</th>
                    <th>Storage</th>
                    <th>Wi-Fi</th>
                    <th>Sockets</th>
                    <th>View</th>
                    <th>Facing</th>
                    <th>Balcony</th>
                </tr>
<?
    foreach ($db->fetch("rooms", "`group` = " . $group["id"]) as $room) {
        $trClass = ($room["type"] === "Unavailable" ? "unavailable" : "room");
        $type = $room["type"] . ($room["special"] === "None" ? "" : " (" . $room["special"] . ")");
        $alloc = $db->fetch("allocations", "`room` = " . $room["id"] . " AND `year` = " . $year);

        if (is_null($alloc[0]["name"]) && $room["type"] != "Unavailable") {
?>
                <tr class="<?=$trClass?>" data-room="<?=$room["id"]?>" data-desc="<?=disp($room["desc"])?>">
                    <td><?=disp($room["name"])?></td>
                    <td><?=disp($type)?></td>
                    <td><?=disp($room["floor"])?></td>
                    <td><?=disp($room["bathroom"])?></td>
                    <td><?=disp($room["sink"], true)?></td>
                    <td><?=disp($room["storage"])?></td>
                    <td><?=disp($room["wifi"])?></td>
                    <td><?=disp($room["sockets"])?></td>
                    <td><?=disp($room["view"])?></td>
                    <td><?=disp($room["facing"])?></td>
                    <td><?=disp($room["balcony"])?></td>
               </tr>
<?
        }
    }
}
?>
            </table>
        </div>
 
        <div id="popup" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
<?
if (auth(true)) {
?>
                    <div class="modal-body">
                        <div role="tabpanel">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a data-target="#popup-display" role="tab" data-toggle="tab">View</a></li>
                                <li role="presentation"><a data-target="#popup-edit" role="tab" data-toggle="tab">Edit</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="popup-display" class="tab-pane active" role="tabpanel">
                                    <div id="popup-desc"></div>
                                    <h3>Basics</h3>
                                    <dl class="dl-horizontal">
                                        <dt>Type</dt>
                                        <dd id="popup-type"></dd>
                                        <dt>Floor</dt>
                                        <dd id="popup-floor"></dd>
                                        <dt>Bathroom</dt>
                                        <dd id="popup-bathroom"></dd>
                                    </dl>
                                    <h3>Opinions</h3>
                                    <dl class="dl-horizontal">
                                        <dt>Lockable storage</dt>
                                        <dd id="popup-storage"></dd>
                                        <dt>Wi-Fi strength</dt>
                                        <dd id="popup-wifi"></dd>
                                        <dt>Number of sockets</dt>
                                        <dd id="popup-sockets"></dd>
                                    </dl>
                                    <h3>Features</h3>
                                    <dl class="dl-horizontal">
                                        <dt>View</dt>
                                        <dd id="popup-view"></dd>
                                        <dt>Facing</dt>
                                        <dd id="popup-facing"></dd>
                                        <dt>Balcony</dt>
                                        <dd id="popup-balcony"></dd>
                                    </dl>
                                </div>
                                <form id="popup-edit" class="tab-pane" role="tabpanel">
                                    <textarea id="popup-edit-desc" class="form-control" rows="4" placeholder="Description"></textarea>
                                    <p class="help-block">Markdown formatting available (<a id="popup-md">show help</a>).</p>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Bathroom</label>
                                            <select id="popup-edit-bathroom" class="form-control">
                                                <option>&mdash;</option>
                                                <option>Ensuite</option>
                                                <option>Between 2</option>
                                                <option>Between 3</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Sink</label>
                                            <select id="popup-edit-sink" class="form-control">
                                                <option>&mdash;</option>
                                                <option>Yes</option>
                                                <option>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Storage</label>
                                            <select id="popup-edit-storage" class="form-control">
                                                <option>&mdash;</option>
                                                <option>Large</option>
                                                <option>Small</option>
                                                <option>None</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Wi-Fi</label>
                                            <select id="popup-edit-wifi" class="form-control">
                                                <option>&mdash;</option>
                                                <option>Strong</option>
                                                <option>Average</option>
                                                <option>Weak</option>
                                                <option>None</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Sockets</label>
                                            <select id="popup-edit-sockets" class="form-control">
                                                <option>&mdash;</option>
                                                <option>Lots</option>
                                                <option>Some</option>
                                                <option>Few</option>
                                                <option>None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>View</label>
                                            <select id="popup-edit-view" class="form-control">
                                                <option>&mdash;</option>
                                                <option>College</option>
                                                <option>Gardens</option>
                                                <option>Roadside</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Facing</label>
                                            <select id="popup-edit-facing" class="form-control">
                                                <option>&mdash;</option>
                                                <option value="N">North</option>
                                                <option value="NE">North-East</option>
                                                <option value="E">East</option>
                                                <option value="SE">South-East</option>
                                                <option value="S">South</option>
                                                <option value="SW">South-West</option>
                                                <option value="W">West</option>
                                                <option value="NW">North-West</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Balcony</label>
                                            <select id="popup-edit-balcony" class="form-control">
                                                <option>&mdash;</option>
                                                <option>Private</option>
                                                <option>Between 2</option>
                                                <option>Open</option>
                                                <option>None</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <input type="submit" class="btn btn-primary" value="Save">
                                        <input type="reset" class="btn btn-default" value="Cancel">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
<?
} else {
?>
                    <div class="modal-body">
                        <div id="popup-desc"></div>
                        <h3>Basics</h3>
                        <dl class="dl-horizontal">
                            <dt>Type</dt>
                            <dd id="popup-type"></dd>
                            <dt>Floor</dt>
                            <dd id="popup-floor"></dd>
                            <dt>Bathroom</dt>
                            <dd id="popup-bathroom"></dd>
                        </dl>
                        <h3>Opinions</h3>
                        <dl class="dl-horizontal">
                            <dt>Lockable storage</dt>
                            <dd id="popup-storage"></dd>
                            <dt>Wi-Fi strength</dt>
                            <dd id="popup-wifi"></dd>
                            <dt>Number of sockets</dt>
                            <dd id="popup-sockets"></dd>
                        </dl>
                        <h3>Features</h3>
                        <dl class="dl-horizontal">
                            <dt>View</dt>
                            <dd id="popup-view"></dd>
                            <dt>Facing</dt>
                            <dd id="popup-facing"></dd>
                            <dt>Balcony</dt>
                            <dd id="popup-balcony"></dd>
                        </dl>
                    </div>
<?
}
?>
                </div>
            </div>
        </div>
        <div id="maps" class="container-fluid page">
            <h1>Maps of college</h1>
            <h2>Main college building</h2>
            <a href="/img/plan.png"><img class="img-responsive delay" data-src="/img/plan.png"></a>
            <h2>Rooms by area</h2>
            <a href="/img/map.png"><img class="img-responsive delay" data-src="/img/map.png"></a>
            <h2>Open balcony access</h2>
            <p>Bright colours represent different balconies and the staircases where they can be accessed.  Pale green is ground level.</p>
            <a href="/img/balconies.png"><img class="img-responsive delay" data-src="/img/balconies.png"></a>
        </div>
        <div id="allocated" class="container-fluid page">
<?
if (auth(true)) {
?>
            <h1>Room allocations <small id="alloc-hover"></small></h1>
            <div id="allocs" class="text-justify">
<?
    $rooms = $db->fetch("rooms", null, "`id` ASC");
    foreach ($db->fetch("allocations", "`year` = " . $year, "`room` ASC") as $alloc) {
        $rooms[$alloc["room"] - 1]["alloc"] = $alloc;
    }
    foreach ($rooms as $room) {
        $class = ($room["type"] === "Unavailable" ? "unav" : ($room["alloc"] ? "taken" : "free"));
        $occupant = ($room["alloc"] ? ' data-occupant="' . $room["alloc"]["name"] . '"' : "");
?>
                <span class="<?=$class?>" data-room="<?=$room["id"]?>"<?=$occupant?>><?=$room["name"]?></span>
<?
    }
?>
            </div>
<?
} elseif (auth()) {
?>
            <h1>Room allocations</h1>
            <p>You need to be a member of Robinson College to see this.</p>
<?
} else {
?>
            <h1>Room allocations</h1>
            <p>You need to be <a href="/login">logged in</a> to see this.</p>
<?
}
?>
        </div>
<?
if (auth(true, true)) {
?>
        <div id="allocate" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <form id="allocate-form">
                        <div class="modal-body">
                            <input id="allocate-room" type="hidden">
                            <input id="allocate-name" type="text" class="form-control" placeholder="Occupant">
                        </div>
                        <div class="modal-footer">
                            <div class="pull-right">
                                <input type="submit" class="btn btn-primary" value="Save">
                                <input type="reset" class="btn btn-default" data-dismiss="modal" value="Cancel">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?
}
$yearJS = ($year ? "?yr=" . $year : "");
?>
        <script src="/js/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/markdown.js"></script>
        <script src="/js/script.js.php<?=$yearJS?>"></script>
    </body>
</html>