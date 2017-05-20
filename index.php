<?php

require_once "app/ErrorHandler.php";
require_once "app/Layout.php";

// buffer the output
ob_start();

Layout::HTMLheader("Fitz JCR Housing Ballot System");
Layout::HTMLnavbar();
Layout::HTMLcontent("Fitz JCR Housing Ballot System", "Welcome to the Fitzwilliam College JCR's online room and housing ballot system. The system is under active development by the JCR Website Officer, Charlie Jonas, and has not yet reached alpha-phase. Details about how to register for beta testing will be circulated in due course.");
Layout::HTMLcontent("Privacy notice", 'For security reasons, we request that you keep information regarding room rents and photographs of College-owned rooms strictly confidential to the members of Fitzwilliam College. You are also warned that exploiting any online vunerabilities in Ballot System will not be tolerated. If anyone is known to have violated these rules, all information will be withdrawn and the person in question will be referred to the Senior Tutor and the Dean.');
Layout::HTMLcontent("About this site", 'If you have any problem with the housing ballot you should contact the JCR Vice President at <a href="mailto:jcr.vice.president@fitz.cam.ac.uk">jcr.vice.president@fitz.cam.ac.uk</a>. To report any technical issues with this website you are invited to contact the JCR Website Officer in confidence at <a href="mailto:jcr.website@fitz.cam.ac.uk">jcr.website@fitz.cam.ac.uk</a>');
Layout::HTMLfooter();

// return the buffered content all at once
ob_flush();

?>