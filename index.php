<?php

require_once "app/ErrorHandler.php";
require_once "app/Layout.php";

// buffer the output
ob_start();

Layout::HTMLheader("Fitz JCR Housing Ballot System");
Layout::HTMLnavbar();
Layout::HTMLcontent("Fitz JCR Housing Ballot System", "

Welcome to Fitzwilliam College JCR's online room and housing ballot system. The system is under active development by the JCR Website Officer, Charlie Jonas, and has not yet reached the testing phase. Details about how to register for beta testing will be circulated in due course.

This project is open source, so if you'd like to help with development:

* [Fork the codebase on GitHub](https://github.com/CHTJonas/roomballot).
* Run ```git clone https://github.com/YOUR_USERNAME/roomballot.git``` in a terminal.
* Make some local changes!
* ```git push```
* Submit a merge request, sit back and wait for your code to be pulled into the master branch.

### Privacy notice

For security reasons, we request that you keep information regarding room rents and photographs of College-owned rooms strictly confidential to the members of Fitzwilliam College. You are also warned that exploiting any online vunerabilities in Ballot System will not be tolerated. If anyone is known to have violated these rules, all information will be withdrawn and the person in question will be referred to the Senior Tutor and the Dean.

### About this site
If you have any problem with the housing ballot you should contact the JCR Vice President at [jcr.vice.president@fitz.cam.ac.uk](mailto:jcr.vice.president@fitz.cam.ac.uk). To report any technical issues with this website you are invited to contact the JCR Website Officer in confidence at [jcr.website@fitz.cam.ac.uk](mailto:jcr.website@fitz.cam.ac.uk).

");
Layout::HTMLfooter();

// return the buffered content all at once
ob_flush();

?>