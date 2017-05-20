<?php

class Environment {

    // app enviromental variables
    private static $app_hostname = "chtj2.user.srcf.net"

    // database enviromental variables
    private static $db_host = "localhost";     // database host machine
    private static $db_user = "";              // database username
    private static $db_pass = "";              // database password
    private static $db_name = "";              // database name
    private static $db_prefix = "";            // table prefix

    // Raven authentication module enviromental variables
    private static $raven_cookieKey = "v225T29kPFaFjQCy754mrVVKVCXG8oCTkBZEAP9ohMz2sM5T5PYAGSGn3zVbZka7";       // this needs to be 64+ random characters

}

?>