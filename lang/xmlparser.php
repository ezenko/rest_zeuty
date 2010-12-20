<form action='xmlparser.php' method='POST'>
<input type='file' name='file_name' maxlength='500'><br>
<input type="hidden" name='checking'value='1'>
<input type="submit" name="submit" value="GO"><br>
</form> 
<?php
if ($_POST["checking"] == 1){
	$file_name = $_POST["file_name"];
	echo $file_name."<BR>";
	$file = basename($_POST["file_name"]);
	$new_path = $file_name."_new";
	$handle = fopen($new_path, "w");
//////////////////	
	if (!($parser = xml_parser_create())){
			print("Error");
			exit;
	}
	$data = file_get_contents($file_name);
	xml_parse_into_struct($parser,$data,$struct,$index);
	xml_parser_free($parser);
	$mas = array();
//////////////////////	
	$zag = "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
	$koren = "<data>\n";
	fwrite($handle, $zag);
	fwrite($handle, $koren);
	echo "<pre>";
	print_r($struct);
	echo "</pre>";
	foreach ($struct as $s){
		$s["value"] = trim($s["value"]);
		if ((strlen($s["value"]) > 0) && strtolower($s["tag"])!="data"){
				$string = "<lines name=\"".strtolower($s["tag"])."\" descr=\"\"><![CDATA[".$s["value"]."]]></lines>\n";
				fwrite($handle, $string);
		}
	}
	$kon = "</data>";
	fwrite($handle, $kon);
	echo "File $file Ok";
	fclose($handle);
}
?>