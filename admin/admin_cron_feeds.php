<?php
/**
* Cron file for updating rss feed news on site
*
* @package RealEstate
* @subpackage Admin Mode
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:24 $
**/

error_reporting(~E_ALL);
include "../include/config.php";
include "../common.php";
include "../include/class.news.php";

NewsUpdater();

?>