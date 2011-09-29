<?php

  // set this to 1 when you're happy that you've done the configuration
  $CONFIG_IS_LOADED = 1;
  
  // a list of mysql servers to use for read-only operations - round-robin load balancing
  $MYSQL_READONLY_SERVERS = array("localhost");
  $MYSQL_READWRITE_SERVERS = array("localhost");

  // pick a server at random for reading
  $pickone = array_rand($MYSQL_READONLY_SERVERS);
  $MYSQL_READONLY_SERVER = $MYSQL_READONLY_SERVERS[$pickone];
  
  // pick a server at random for reporting
	// $pickone = array_rand($MYSQL_REPORTING_SERVERS);
	// $MYSQL_REPORTING_SERVER = $MYSQL_REPORTING_SERVERS[$pickone];
  
  // pick a server at random for read-write
  $pickone = array_rand($MYSQL_READWRITE_SERVERS);
  $MYSQL_READWRITE_SERVER = $MYSQL_READWRITE_SERVERS[$pickone];
  
  // credentials for connecting to local MySQL read
  $MYSQL_READONLY_USERNAME="root";
  $MYSQL_READONLY_PASSWORD="sqldbmine";
  $MYSQL_READONLY_DATABASE="crisp";
  $MYSQL_READONLY_PORT=3306;
  
  // credentials for connecting to master MySQL server read/write
  $MYSQL_READWRITE_USERNAME="root";
  $MYSQL_READWRITE_PASSWORD="sqldbmine";
  $MYSQL_READWRITE_DATABASE="crisp";    
  $MYSQL_READWRITE_PORT=3306;
 
  
  // a class full of constants
  class CrispConstants {
    
		//config		
		
		//root for html templates
		const TEMPLATE_ROOT = "./templates/";
		//root for crisp website
		const ROOT = "http://crisp/";
		//root for crisp admin pages
		const ADMIN_ROOT = "http://crisp/adminviews/";
		//url for akme apis
		const AKME_API_ROOT = "http://akme.enablemedia.com/api/";
		//key for nettrackz api
		const NETTRACKZ_CRISP_SECRET_KEY = 'DoAndroidsDreamOfElectricSheep?';
		//nettrackz api root
		const NETTRACKZ_API_ROOT = "http://www.nettrackz.co.uk/crisp/";
		//nettrackz root
		const NETTRACKZ_URL = "http://www.nettrackz.co.uk";
		//seotrackz root
		const SEO_TRACKS_DOMAIN = "http://www.seotrackz.co.uk/";
		//seotrackz api root
		const SEO_TRACKS_API_ROOT = "http://www.seotrackz.co.uk/crisp/";
		//seotrackz crisp api key
		const SEO_TRACKS_CRISP_SECRET_KEY = "EthicsAreSoAnnoyingIAvoidThemOnPrinciple";

    // How many keyords/competitors are they permitted?
		const SEO_TRACKS_KEYWORD_COUNT = 10;		
		const SEO_TRACKS_COMPETITOR_COUNT = 2;
		// arlington root
		const ARLINGTON_ROOT = "http://arlingtonqa.scoot.co.uk/";
		
		// cloud address for image uploads
		const CLOUD_ROOT = "uploads/";

	}
	
?>