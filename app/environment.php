<?php

class Environment {

    // app enviromental variables
    public const $app_hostname = "chtj2.user.srcf.net"

    // database enviromental variables
    public const $db_host = "localhost";     // database host machine
    public const $db_user = "";              // database username
    public const $db_pass = "";              // database password
    public const $db_name = "";              // database name
    public const $db_prefix = "";            // table prefix

    // Raven authentication module enviromental variables
    public const $raven_cookieKey = "v225T29kPFaFjQCy754mrVVKVCXG8oCTkBZEAP9ohMz2sM5T5PYAGSGn3zVbZka7";       // this needs to be 64+ random characters

}

?>