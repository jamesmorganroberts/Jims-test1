<?php


  // set this to 1 when you're happy that you've done the configuration
  $CONFIG_IS_LOADED = 1;
  
  // a list of mysql servers to use for read-only operations - round-robin load balancing
  $MYSQL_READONLY_SERVERS = array("localhost");
  $MYSQL_READWRITE_SERVERS = array("localhost");

  // pick a server at random for reading
  $pickone = array_rand($MYSQL_READONLY_SERVERS);
  $MYSQL_READONLY_SERVER = "db374577124.db.1and1.com";
  
  // pick a server at random for reporting
	// $pickone = array_rand($MYSQL_REPORTING_SERVERS);
	// $MYSQL_REPORTING_SERVER = $MYSQL_REPORTING_SERVERS[$pickone];
  
  // pick a server at random for read-write
  $pickone = array_rand($MYSQL_READWRITE_SERVERS);
  $MYSQL_READWRITE_SERVER = $MYSQL_READWRITE_SERVERS[$pickone];
  
  // credentials for connecting to local MySQL read
  $MYSQL_READONLY_USERNAME= "dbo374577124";
  $MYSQL_READONLY_PASSWORD="sidfriend";
  $MYSQL_READONLY_DATABASE="db374577124";
  $MYSQL_READONLY_PORT=3306;
  
  // credentials for connecting to master MySQL server read/write
  $MYSQL_READWRITE_USERNAME= "dbo374577124";
  $MYSQL_READWRITE_PASSWORD="sidfriend";
  $MYSQL_READWRITE_DATABASE="db374577124";    
  $MYSQL_READWRITE_PORT=3306;

//$dbhostname = "db374577124.db.1and1.com";
//$dbusername = "dbo374577124";
//$dbpassword = "sidfriend";
  
  
class GV {

	const ROOT = "http://www.jamesmroberts.co.uk/new/";
	
  
  
  
}
  
  // a class full of constants
  class CrispConstants {
    
 		//config
		
		//root for html templates
		const TEMPLATE_ROOT = "./templates/";
		//root for crisp website
		const ROOT = "http://crisp/";
		//root for crisp admin pages
		const ADMIN_ROOT = "http://crisp/adminviews/";
		//key for akme apis
		const AKME_API_ROOT = "http://akme/api/";
		//key for nettrackz api
		const NETTRACKZ_CRISP_SECRET_KEY = 'DoAndroidsDreamOfElectricSheep?';
		//nettrackz api root
		const NETTRACKZ_API_ROOT = "http://nettrackz/crisp/";
		//nettrackz root
		const NETTRACKZ_URL = "http://nettrackz";
		//seotrackz root
		const SEO_TRACKS_DOMAIN = "http://seotrackz/";
		//seotrackz api root
		const SEO_TRACKS_API_ROOT = "http://seotrackz/crisp/";
		//seotrackz crisp api key
		const SEO_TRACKS_CRISP_SECRET_KEY = "EthicsAreSoAnnoyingIAvoidThemOnPrinciple";

		const SEO_TRACKS_KEYWORD_COUNT = 10;		// How many keyords/competitors are they permitted?
		const SEO_TRACKS_COMPETITOR_COUNT = 2;
		// arlington root
		const ARLINGTON_ROOT = "http://arlingtonqa.scoot.co.uk/";
		
		// cloud address for image uploads
		const CLOUD_ROOT = "uploads/";
		
	}

	
	
?>
