<?PHP
/**
* Class for saving data which is necessary for different payment  
* systems and implementation of standard operations with them. Also it contains
* information about the operating modes of the class functioning.
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

if (!defined('PAYMENT_CONFIG')) {
	die("To use the file " . __FILE__ . " you should setup config!");
}

class Payment_Data_Kipper {

	/**
	*	Debug mode
	*
	*	@access protected
	*	@type bool
	*/
	var $_debug = false;

	/**
	*	Mode of class operation PAYMENT_ENGINE_SEND or PAYMENT_ENGINE_RECEIVE
	*
	*	@access protected
	*	@type int
	*/
	var $_method = PAYMENT_ENGINE_SEND;

	/**
	*	The way the data is sent
	*
	*	@access private
	*	@type string
	*/
	var $_mode = "POST";

	/**
	*	The URL to the payment system site
	*
	*	@access private
	*	@type string
	*/
	var $_url = "";

	/**
	*	Array of the data with parameters
	*
	*	@access protected
	*	@type array
	*/
	var $_options = array();

	/**
	*	The list of obligatory parameters
	*
	*	@access protected
	*	@type array
	*/
	var $_required = array();

	/**
	*	The list of optional parameters
	*
	*	@access protected
	*	@type array
	*/
	var $_optional = array();

	/**
	*	Standard constructor
	*/
	function Payment_Data_Kipper() {

	}

	/**
	*	Retrun the list of parameters.
	*
	*	@access protected
	*	@return array
	*/
	function getFields() {
		return array_keys($this->_options);
	}

	/**
	*	Returns the value of the parameter by key.
	*
	*	@access public
	*	@param string $field Имя параметра
	*	@return mixed returns the value of string if the field exists, otherwise
	*		false will be returned
	*/
	function getField($field) {
		if ($this->fieldExist($field)) {
			return $this->_options[$field];
		}
		return false;
	}

	/**
	*	Check for some parameters existence.
	*
	*	@access protected
	*	@return bool
	*/
	function fieldExist($field) {
		return array_key_exists($field, $this->_options);
	}

	/**
	*	Settings setup from an array. It is necessary for the parameter to already exist.
	*
	*	@acces public
	*	@param array $where assotiative array 'key' => 'value'
	*/
	function setFrom($where) {
        foreach ($this->getFields() as $field) {
            if (isset($where[$field])) {
                $this->_options[$field] = $where[$field];
            }
        }
    }

    /**
    *	Setup of a definite value to a definite  parameter. It is necessary
    *		for the parameter to already exist.
    *
    *	@access public
    *	@return bool will return true if the field exists, otherwise false will be returned
    */
    function set($field, $value) {
        if (!$this->fieldExist($field)) {
            return false;
        }
        $this->_options[$field] = $value;
        return true;
    }

    /**
    *	Sets the parameters obligatory.
    *
    *	@access protected
    *	@param ... Parameters enumeration
    */
    function makeRequired() {
        foreach (func_get_args() as $field) {
            $this->_required[$field] = null;
        }
    }

    /**
    *	Sets the parameters optional.
    *
    *	@access protected
    *	@param ... Parameters enumeration
    */
    function makeOptional() {
        foreach (func_get_args() as $field) {
            $this->_optional[$field] = null;
        }
	}

	/**
	*	Combines obligatory and optional parameters into one list.
	*
	*	@access protected
	*/
	function makeFields() {
		while (list($field, $value) = each($this->_required)) {
			$this->_options[$field] = $value;
		}
		while (list($field, $value) = each($this->_optional)) {
			$this->_options[$field] = $value;
		}
	}

	/**
	*	Adds obligatory parameter.
	*
	*	@access public
	*	@param string $field Name of the parameter
	*	@param string $value Value of the parameter
	*/
	function makeRequiredField($field, $value = null) {
		$this->_required[$field] = $value;
	}

	/**
	*	Adds optional parameter.
	*
	*	@access public
	*	@param string $field Name of the parameter
	*	@param string $value Value of the parameter
	*/
	function makeOptionalField($field, $value = null) {
		$this->_optional[$field] = $value;
	}

	/**
    *	Check whether the parameter is obligatory or not
    *
    *	@access protected
    *	@param string $field the name of the parameter used for checking 
    */
    function isRequired($field) {
        return (isset($this->_required[$field]));
    }

    /**
	*	Data verification for existance.
	*
	*	@access private
	*	@return bool true if all the obligatory fields are set, otherwise false will be returned
	*/
	function _verifyData() {
		while (list($field, $value) = each($this->_required)) {
			if (is_null($this->_options[$field])) {
				return false;
			}
		}
		return true;
	}

	/**
	*	Creation of a form or a string of a request by some definite method
	*		depending on the settings of a definite class of a payment system.
	*
	*	@access protected
	*	@return mixed
	*/
	function formMessage() {

		if ($this->_method === "GET") {
			$redirect = "Location: " . $this->_url . "?";
			foreach ($this->_options as $key => $value) {
				$redirect .= $this->_arrayField[$key] . "=" . $value . "&";
			}
			if ($this->_debug) {
				print $redirect;
			} else {
				header($redirect);
			}
		} elseif ($this->_method === "POST") {
			$retHTML = "<html><body onLoad=\"document.send_form.submit();\">";
			$retHTML .= "<form method=\"post\" name=\"send_form\" action=\"".$this->_url."\">";
			foreach ($this->_options as $key => $value) {
				$retHTML .= "<input type=\"hidden\" name=\"".$this->_arrayField[$key]."\" value=\"".$value."\">";
			}
			$retHTML .= "</form></body></html>";
//die(htmlspecialchars($retHTML));
			if ($this->_debug) {
				print htmlspecialchars($retHTML);
			} else {
				print $retHTML;
			}
		} else {
			return false;
		}
	}
}
?>