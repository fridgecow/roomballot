<?php

require_once "app/layout.php";

Layout::HTMLheader("Fitz JCR Housing Ballot System");
Layout::HTMLnavbar();
Layout::HTMLcontent("Fitz JCR Housing Ballot System", "Welcome to the Fitzwilliam College JCR's online room and housing ballot system. The system is under active development by the JCR Website Officer, Charlie Jonas, and has not yet reached alpha-phase. Details about how to register for beta testing will be circulated in due course.");
Layout::HTMLcontent("Privacy notice", "For security reasons, we request that you keep information regarding room rents and photographs of College-owned rooms strictly confidential to the members of Fitzwilliam College. If anyone is known to have violated this rule, all information will be withdrawn and the person in question will be referred to the Senior Tutor and the Dean.");
Layout::HTMLcontent("About this site", "If you have any problem with the housing ballot you should contact the JCR Vice President at EMAIL. To report any technical issues with this website you are invited to contact the JCR Website Officer in confidence at EMAIL.");
Layout::HTMLfooter();
?>