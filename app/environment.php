<?php

class Environment {

    // app enviromental variables
    public static $app_hostname = "chtj2.user.srcf.net"

    // database enviromental variables
    public static $db_host = "localhost";     // database host machine
    public static $db_user = "";              // database username
    public static $db_pass = "";              // database password
    public static $db_name = "";              // database name
    public static $db_prefix = "";            // table prefix

    // Raven authentication module enviromental variables
    public static $raven_cookieKey = "v225T29kPFaFjQCy754mrVVKVCXG8oCTkBZEAP9ohMz2sM5T5PYAGSGn3zVbZka7";       // this needs to be 64+ random characters

}

?>