<?php
/**
* This file contains functions for validating values in different manner
*/

/**
* Class needed to perform data validating 
*
* class validates user data and create SQL executable strings
* @access private
* @package RealEstate
* @subpackage Include
* @author 
*/
class validator_field
{
	/**
	* @var string name of the field 
	*/
	var $name = '';
	
	/**
	* @var string value of the field 
	*/
	var $field_value_unconverted = '';
	
	/**
	* @var string value of the field cut by length
	*/
	var $field_value = '';
	
	/**
	* @var string value of the field for SQL query
	*/
	var $SQL_value = '';
	
	/**
	* @var boolean flag of field existing in the form 
	*/
	var $unset_field = false;
	
	
	/**
	* Class constructor
	*
	* constructor of the main validating package class
	* @param string $field_name name of the POST field
	* @param string $field_length allowed field length, any length is allowed case of 0 
	* @param string $transmit_method method of variables data transferring POST or GET 
	*/
	function validator_field($field_name, $field_length = 0, $transmit_method = "POST")
	{
		global $_POST;
		global $_GET;
		
		eval("@\$field = \$_".$transmit_method."[".$field_name."];");
		
		if (isset($field))
		{
			$this -> name = $field_name; 
			$this -> field_value_unconverted = $field;
			if (intval($field_length) > 0)
			{
				$this -> field_value = substr($this -> field_value_unconverted, 0, $field_length);
			}
			else
			{
				$this -> field_value = $this -> field_value_unconverted;
			}
			
			$this -> SQL_value = $this -> tosql($this -> field_value, "String");
		}
		else
		{
			// if there are no such field 
			$this -> unset_action();
		}
	}
	
	/**
	* Actions if there is no such field in the data set
	*
	* making some actions if no such field is present, probably hack attempt
	*/
	
	function unset_action()
	{
		$this -> unset_field = true;
		header("Location:" . $_SERVER['PHP_SELF']);
	}
	
	/**
	* Convert string to sql allowed string
	*
	* @param string $value incoming string to be formatted
	* @param string $type 2 types at present time "Number" - for converting to numbers, "String" - for converting to strings 
	* @param boolean $convert if true we use htmlspecialchars for converting some tags
	* @param boolean $quotes if true we enquote the result 
	* @return string formatted string for SQL
	*/
	function tosql($value, $type, $convert = true, $quotes = true)
	{
	  if ($type == "Number")
	  {
		  $SQL_string = doubleval($value);
	  }
	  else
	  {
		  if ($convert)
		  {
		  		$value = htmlspecialchars($value);
		  }	
		  if (get_magic_quotes_gpc() == 0)
		  {
			$value = str_replace("'","''",$value);
			$value = str_replace("\\","\\\\",$value);
		  }
		  else
		  {
			$value = str_replace("\\'","''",$value);
			$value = str_replace("\\\"","\"",$value);
		  }
	
		  if ($quotes)
		  {
		  	$SQL_string = "'" . $value . "'";
	   	  }
		  else
		  {
		  	$SQL_string = $value;
	   	  }
		  
	   }
		return $SQL_string;
	}
	
}

/**
* validator for textarea with HTML tags
*/
class validator_htmlarea_field extends validator_text_field
{
	/**
	* function constructor
	*/
	function validator_htmlarea_field($field_name, $field_length = 0, $transmit_method = "POST")
	{
		global $_POST;
		global $_GET;
		
		eval("@\$field = \$_".$transmit_method."[".$field_name."];");
		
		if (isset($field))
		{
			$this -> name = $field_name; 
			$this -> field_value_unconverted = str_replace('</textarea',"&lt;/textarea",$field);
			if (intval($field_length) > 0)
			{
				$this -> field_value = substr($this -> field_value_unconverted, 0, $field_length);
			}
			else
			{
				$this -> field_value = $this -> field_value_unconverted;
			}
			
			$this -> SQL_value = $this -> tosql(str_replace('&lt;/textarea',"</textarea", $this -> field_value), "String", false);
		}
		else
		{
			// if there are no such field 
			$this -> unset_action();
		}
	}
	
	/**
	* checking length range for the field in the form is empty
	* @param integer $min_length minimal length of the field value
	* @param integer $max_length maximum length of the field value
	* @return boolean
	*/
	function incorrect_length_range($min_length, $max_length)
	{
		$field_length = strlen($this -> field_value_unconverted);
		if ( ($field_length <= $max_length) && ($field_length >= $min_length) )
		{
			return false;
		}
		else
		{
			return true;
		} 
	}
}

/**
* validator for arrays
*/
class validator_array_field extends validator_field
{
	/**
	* function constructor
	*/
	function validator_array_field($field_name, $field_length = 0, $transmit_method = "POST")
	{
		global $_POST;
		global $_GET;
		
		eval("@\$field = \$_".$transmit_method."[".$field_name."];");
		
		if (isset($field))
		{
			if (is_array($field))
			{
				$this -> name = $field_name; 
				$this -> field_value_unconverted = $field;
				$this -> field_value = array();
				$this -> SQL_value = array();
				foreach ($this -> field_value_unconverted as $key => $value)
				{
					if (intval($field_length) > 0)
					{
						$this -> field_value[$key] = htmlspecialchars($value);
					}
					else
					{
						$this -> field_value[$key] = $value;
					}
					array_push($this -> SQL_value, tosql($value, "String"));
				}
				
			}
			else
			{
				$this -> unset_action();
			}
		}
		else
		{
			// if there are no such field 
			$this -> unset_action();
		}
	}
	
	
	/**
	* making no actions and resetting values as empty
	*/
	
	function unset_action()
	{
		$this -> field_value = array();
		$this -> SQL_value = array();
	}
}

/**
* validator for checkboxes
*/
class validator_checkbox_field extends validator_field
{
	/**
	* making no actions and resetting values as empty
	*/
		
	function unset_action()
	{
		$this -> field_value = '';
		$this -> SQL_value = 'NULL';
	}
}

/**
* validator for simple text fields
*/
class validator_text_field extends validator_field
{
	/**
	* checking if field in the form is empty
	* @return boolean
	*/
	function is_empty()
	{
		if (strlen($this -> field_value))
		{
			$empty = false;
		}
		else
		{
			$empty = true;
		} 
		return $empty;
	}
	
	/**
	* checking if field in the form is a path (its last character must be "/")
	* @return boolean
	*/
	function is_path(){
		if (substr($this -> field_value, -1) == "/")
			$path = true;
		else 
			$path = false;
					
		return $path;
	}
	
	/**
	* checking if field in the form is a path to the file with special extension
	* (its last characters (extension) must be the same, as $extension)
	* @return boolean
	*/
	function is_path_to_file($extension){
		if (substr($this -> field_value, -(strlen($extension)+1) ) == ".".$extension)
			return true;
		else 
			return false;
	}
		
	/**
	* checking length range for the field in the form is empty
	* @param integer $min_length minimal length of the field value
	* @param integer $max_length maximum length of the field value
	* @return boolean
	*/
	function incorrect_length_range($min_length, $max_length)
	{
		$field_length = strlen($this -> field_value_unconverted);
		if ( ($field_length <= $max_length) && ($field_length >= $min_length) )
		{
			return false;
		}
		else
		{
			return true;
		} 
	}
	
	/**
	* checking field in the form for compatability with PREG pattern
	* @param string $pattern string pattern for comparison
	* @return boolean
	*/
	function incorrect_pattern($pattern)
	{
		if(preg_match($pattern, $this -> field_value))
		{	
			return false;
		}	
		else
		{
			return true;
		}
	}
}

/**
* validator for email address fields
*/
class validator_email_field extends validator_text_field
{
	/**
	* checking email address field format
	* @return boolean
	*/
	
	function incorrect_email_address()
	{
		if ($this -> is_empty())
		{
			return true;
		}
		else
		{
			$pattern = "/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)+/";
			if(preg_match($pattern, $this -> field_value))
			{	
				return false;
			}	
			else
			{
				return true;
			}
			
		} 
	}
	
	/**
	* checking length range for the field in the form is empty
	* @param integer $min_length minimal length of the field value
	* @param integer $max_length maximum length of the field value
	* @return boolean
	*/
	function incorrect_length_range($min_length, $max_length)
	{
		$field_length = strlen($this -> field_value_unconverted);
		if ( ($field_length <= $max_length) && ($field_length >= $min_length) )
		{
			return false;
		}
		else
		{
			return true;
		} 
	}
}

/**
* validator for URL fields
*/
class validator_url_field extends validator_text_field
{
	/**
	* checking url address field format
	* @return boolean
	*/
	
	/**
	* @var array of strings - regular expression rules for parsing URL and exracting its parts
	*/
	var $URL_pattern = array(
		'protocol' => '((http|https|ftp)://)',
		'access' => '(([a-z0-9_]+):([a-z0-9-_]*)@)?',
		'sub_domain' => '(([a-z0-9_-]+\.)*)',
		'domain' => '(([a-z0-9-]{3,})\.)',
		'tld' =>'(com|net|org|edu|gov|mil|int|arpa|aero|biz|coop|info|museum|name|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cf|cd|cg|ch|ci|ck|cl|cm|cn|co|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|eh|er|es|et|fi|fj|fk|fm|fo|fr|fx|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zr|zw)',
		'port'=>'(:(\d+))?',
		'path'=>'((/[a-z0-9-_.%~]*)*)?',
		'query'=>'(\?[^? ]*)?'
		);


	function is_url()
	{
		if ($this -> is_empty())
		{
			return false;
		}
		else
		{
			$pattern = "`^"
				.$this -> URL_pattern['protocol']
				.$this -> URL_pattern['access']
				.$this -> URL_pattern['sub_domain']
				.$this -> URL_pattern['domain']
				.$this -> URL_pattern['tld']
				.$this -> URL_pattern['port']
				.$this -> URL_pattern['path']
				.$this -> URL_pattern['query']
				."$`iU";
			if (preg_match($pattern, $this -> field_value))
			{	
				return true;
			}	
			else
			{
				return false;
			}
		} 
	}
	
}


/**
* validator for phone/fax fields
*/
class validator_phone_field extends validator_text_field
{
	/**
	* checking phone field format
	* @return boolean
	*/
	
	function incorrect_phone_format()
	{
		$pattern = "/^([\.\-0-9+()\s]+)$/";
		if(preg_match($pattern, $this -> field_value))
		{	
			return false;
		}	
		else
		{
			return true;
		}
	}
	
}

/**
* validator for numeric fields
*/
class validator_numeric_field extends validator_field
{
	/**
	* @var mixed numerical representation of the field
	*/
	var $field_numeric_value = 0;
	
	/**
	* Constructor for numeric fields
	* 
	* constructor of the main validating package class
	* @param string $field_name name of the POST field
	* @param string $field_length allowed field length, any length is allowed case of 0 
	*/
	function validator_numeric_field($field_name, $field_length = 0, $transmit_method = "POST")
	{
		$this -> validator_field($field_name, $field_length, $transmit_method);
		$this -> field_numeric_value = doubleval($this -> field_value);
		$this -> SQL_value = $this -> tosql($this -> field_value, "Number");
	}
		
	/**
	* checking if field in the form is empty
	* @return boolean
	*/
	function is_empty()
	{
		if (strlen($this -> field_value))
		{
			return false;
		}
		else
		{
			return true;
		} 
	}
	
	/**
	* checking if field in the form is an unsigned integer
	* @return boolean
	*/
	function is_insigned_integer()
	{
		$pattern = "/^([0-9]+)$/";
		if(preg_match($pattern, $this -> field_value))
		{	
			return true;
		}	
		else
		{
			return false;
		}
	}
	
	/**
	* checking if field in the form is an unsigned double
	* @return boolean
	*/
	function is_insigned_float()
	{
		$pattern = "/^([0-9]+.)$/";
		if(preg_match($pattern, $this -> field_value))
		{	
			return true;
		}	
		else
		{
			return false;
		}
	}
	
	/**
	* checking if field value belongs to the special range
	* @param mixed $start - range start number 
	* @param mixed $finish - range finish number
	* @param boolean $strict comparison criteria
	* @return boolean 
	*/
	function is_in_range($start = 0, $finish = 0, $strict = false)
	{
		if ( (!$strict) && ($this -> field_numeric_value >= $start) && ($this -> field_numeric_value <= $finish))
		{
			return true;
		}
		elseif ( ($strict) && ($this -> field_numeric_value > $start) && ($this -> field_numeric_value < $finish))
		{
			return true;
		}
		else
		{
			return false;
		} 
	}
	
	/**
	* checking if field value more than minimal value
	* @param mixed - minimal
	* @return boolean 
	*/
	function is_more_than($start = 0)
	{
		if ($this -> field_numeric_value >= $start)
		{
			return true;
		}
		else
		{
			return false;
		} 
	}

	/**
	* checking if field in the form is a percentage value
	* @param integer $precision number of digits in after point
	* @return boolean
	*/
	function is_decimal($precision = 0)
	{
		$pattern = "/^(\d+)(.(\d{0,".$precision."}))?$/";
		if(preg_match($pattern, $this -> field_value))
		{	
			return true;
		}	
		else
		{
			return false;
		}
	}
	
	/**
	* checking if field in the form is a percentage value
	* @param integer $precision number of digits in after point
	* @return boolean
	*/
	function is_percentage_field($precision = 0)
	{
		if ( ($this -> is_decimal($precision)) && ($this -> is_in_range(0, 100, true)) )
		{	
			return true;
		}	
		else
		{
			return false;
		}
	}
}



/**
* Class needed to perform uploaded file validation 
*
* class validates uploaded files
* @access private
* @package common
* @author Dmitry Evdokimov
*/
class validator_file
{
	/**
	* @var mixed file itself
	*/
	var $file_object = '';
	/**
	* @var string save name for the file
	*/
	
	var $save_name = '';
	/**
	* @var boolean flag of file being successfully uploaded
	*/
	
	var $file_uploaded = false;
	var $unset_field = false;
	
	/**
	* Constructor for file validator
	* @param string file_field name of the POST file field
	*/
	function validator_file($file_field)
	{
		if (is_uploaded_file($_FILES[$file_field]['tmp_name']))
		{
			$this -> file_object = $_FILES[$file_field]; 
			$this -> file_uploaded = true;
		}
		
	}
	
	/**
	* Actions if there is no such field in the data set
	*
	* making some actions if no such field is present, probably hack attempt
	*/
	function unset_action()
	{
		$this -> unset_field = true;
		header("Location:" . $_SERVER['PHP_SELF']);
	}
	
	/**
	* checking if file size in bytes lie in the range
	* @param mixed minimal file size in bytes 
	* @param mixed maximum file size in bytes
	* @return boolean 
	*/
	function is_correct_size($min_size = 0, $max_size)
	{
		if (!$this -> file_uploaded)
			return false;
		
		if ( ($this -> file_object['size'] <= $max_size) && ($this -> file_object['size'] >= $min_size) )
		{
			return true;
		}
		else
		{
			return false;
		} 
	}
	
	/**
	* checking if file size in bytes less than certain value
	* @param mixed minimal file size in bytes 
	* @return boolean 
	*/
	function is_size_lower($max_size)
	{
		if (!$this -> file_uploaded)
			return false;
		
		if ( ($this -> file_object['size'] <= $max_size) )
		{
			return true;
		}
		else
		{
			return false;
		} 
	}
	
	
	/**
	* compare file for allowed extensions
	* @param mixed array of strings or single string containing extension mask  
	* @return boolean 
	*/
	function incorrect_extension($extensions)
	{
		if (!$this -> file_uploaded)
			return true;
			
		if (count($extensions) > 0)
		{
			foreach ($extensions as $value)
			{
				if ( strncmp(substr( $this -> file_object['name'], -1 * strlen($value) ),  $value, strlen($value) ) == 0)
				{
					return false;
				}
			}
		}
		
		return true;
	}
}

/**
* validating image files using gd module
*/

class validator_image_file extends validator_file
{
	/**
	* @var here we receive image parameters with gd
	*/
	var $image_params = array(4);
	
	/**
	* extended constructor
	*/
	function validator_image_file($file_field)
	{
		$this -> validator_file($file_field);
		if (($this -> file_uploaded) && (extension_loaded('gd')))
		{
			$this -> image_params = getimagesize( $this -> file_object['tmp_name'] , $temp);
		}
	}
	
	/**
	* checking image parameters like height and width with gd 
	* @param string $min_width minimal allowed width
	* @param string $max_width maximum allowed width
	* @param string $min_height minimal allowed height
	* @param string $max_height maximum allowed height  
	* @return boolean 
	*/
	function incorrect_image_height_width($min_width = 0, $min_height = 0, $max_width = 0 , $max_height = 0 )
	{
		if ((!$this -> file_uploaded) || (!extension_loaded('gd')))
			return true;
		
		if ( ( ($this -> image_params[0] >= $min_width) && ($this -> image_params[0] <= $max_width) ) || ( ($this -> image_params[1] >= $min_height) && ($this -> image_params[1] <= $max_height) ) )
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	/**
	* checking extension with gd
	*/
	function incorrect_extension($extensions)
	{
		if (!$this -> file_uploaded)
			return true;
		
		if (!extension_loaded('gd'))
		{
			parent::incorrect_extension($extensions);
		}
		elseif (count($extensions) > 0)
		{
			foreach ($extensions as $value)
			{
				if ( $this -> image_params[2] == $value )
				{
					return false;
				}
			}
		}
		
		return true;
	}
	/**
	 * return new image width and height
	 *
	 * @param string $image_file
	 * @param integer $maxx
	 * @param integer $maxy
	 * @return string
	 */
	function GetNewImageSize($image_file,$maxx,$maxy)
	{
		$size = @GetImageSize($image_file);		
	    if ($size)
	    {
	        $x = $size[0]; $y = $size[1];
	        $newx = $x; $newy = $y;
	        if (($x > $maxx)&&($y <= $maxy)
	            || ($x > $maxx)&&($y > $maxy)
	//		&&(abs($x-$maxx)>abs($y-$maxy))
		   )
	        {
	            $newx = $maxx;
	            $newy = round($newx*$y/$x);
	        }
	        elseif (($y > $maxy)&&($x <= $maxx)
	            || ($y > $maxy)&&($x > $maxx)
	//		&&(abs($y-$maxy)>abs($x-$maxx))
		    )
	        {
	            $newy = $maxy;
	            $newx = round($newy*$x/$y);
	        }
	        return " WIDTH='".$newx."' HEIGHT='".$newy."'";	 	
	    }
	    return " WIDTH='".$maxx."' HEIGHT='".$maxy."'";
	}

}

?>