<?
require_once "php/common.php";
if (array_key_exists("logout", $_GET)) {
    session_unset();
    $_SESSION["msg"] = array("info", "You're now logged out.");
} else {
    require_once "php/ucam_webauth.php";
    $webauth = new Ucam_Webauth(array(
        "hostname" => "rooms.rcsa.co.uk",
	    "key_dir" => "/societies/rcsarooms/raven_keys",
	    "cookie_key" => "VrcLskstLT562Tp8ebxLZNnM9tQMzbLy0ocBojchgoqhlQCA"
    ));
    if (!$webauth->authenticate()) exit();
    if ($webauth->success()) {
        $_SESSION["user"] = $webauth->principal();
        #        $group = posix_getgrnam("rcsarooms")
        $group = array("members" => array("dec41", "bt332", "mt674", "cs845"));
        $_SESSION["admin"] = in_array($_SESSION["user"], $group["members"]);
        $curl = curl_init("https://www.lookup.cam.ac.uk/api/v1/person/crsid/" . $_SESSION["user"] . "/insts?format=json");
        curl_setopt($curl, CURLOPT_USERPWD, "anonymous:");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $_SESSION["member"] = false;
        foreach ($res["result"]["institutions"] as $inst) {
            if (in_array($inst["instid"], array("ROBIN", "ROBINUG", "ROBINPG")) && !$inst["cancelled"]) {
                $_SESSION["member"] = true;
                break;
            }
        }
        if ($_SESSION["member"]) {
            $_SESSION["msg"] = array("success", "You're now logged in!");
        } else {
            $_SESSION["msg"] = array("warning", "You're logged in, but you don't appear to be a Robinson College member.  You can still browse the site if you want though...");
        }
    } else {
        $_SESSION["msg"] = array("danger", "Failed to login (" . $webauth->status() . "): " . $webauth->msg());
    }
}
session_write_close();
header("Location: /#home");
