<?php
	$config["server"] = "http://rest";
	$config["site_root"] = "";
	$config["site_path"] = "X:/home/rest/www";

	$config["useoledb"] = 1;
	$config["dbtype"] = "mysql";
	$config["dbhost"] = "localhost";
	$config["dbuname"] = "root";
	$config["dbpass"] = "";
	$config["dbname"] = "rest";

	$config["table_prefix"] = "re_";
		
	$data_const = implode("",file(dirname(__FILE__)."/constants.xml"));
	
	$xml_parser = xml_parser_create();
	xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
	xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
	xml_parse_into_struct($xml_parser, $data_const, $vals, $index);
	xml_parser_free($xml_parser);
	
	foreach ( $vals as $i => $node ) {
		if ($node["type"] == "complete") {
			if (!defined($node["tag"])) {
				define( $node["tag"], $config["table_prefix"].$node["value"] );
			}
		}
	}
	
	unset($data_const, $vals, $index, $node);
?>