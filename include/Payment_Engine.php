<?PHP
/**
*	Class used for data exchange with the payment system. It contains 
*		an enterprise of classes. The principles of class functioning depend on the chosen mode of operation.
*
*	Every class created by means of Engine can be easily broaden. Only constructor of the class and class
*		$_arrayField requires modification. It is desirable to make the names of the keys in the array 
*		$_arrayField identical, but this is not obligatory.
*
*	The places in the body of the class used for class modification are underlined
*		by the following comment: \*** MODIFIED CODE ***\. Also the folder 'systems' contains
*		file tmpl_system.php which stands for the template for the class of the payment system.
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

if (!defined('PAYMENT_CONFIG')) {
	die("Чтобы использовать файл " . __FILE__ . " необходимо подключить конфиг!");
}

include_once PAYMENT_DIR."Error_Handler.php";

class Payment_Engine extends Error_Handler {

	/**
	*	Standard cosntructor
	*
	*	@param int $method Method of class operation
	*	@param bool $debug Enabling of debug mode
	*	@param bool $log Enabling logging of the debug system
	*/
	function Payment_Engine($method = PAYMENT_ENGINE_SEND, $debug = false, $log = false) {

		if ($log) {
			$this->Error_Handler(ERROR_HANDLER_MODE_LOG);
		} else {
			$this->Error_Handler();
		}
		//Debug mode is not active
		$this->debug = $debug;
		//Default mode
		$this->switchEngine($method);
	}

	/**
	*	Enterprise of classes. Returns the class identical for a definite payment system
	*
	*	@access public
	*	@param string $type name of the class of the payment system
	*	@return class
	*/
	function &factory($type) {
		$class = "Payment_".$type;
		$file = SYSTEMS_DIR."{$type}.php";
		if (include_once($file)) {
			if ($this->debug) trigger_error("Файл $type.php is present.", E_USER_NOTICE);
			if (class_exists($class)) {
				if ($this->debug) trigger_error("Класс $class exists.", E_USER_NOTICE);
				//создаём объект
				$object = & new $class($this->_method, $this->debug);
				return $object;
			} else {
				trigger_error("Class $class does not exist.", E_USER_ERROR);
			}
		} else {
			trigger_error("File $file does not exist.", E_USER_ERROR);
		}
	}

	/**
	*	Switch of the mode of the sites
	*
	*	@access public
	*	@type const int
	*/
	function switchEngine($method) {
		switch ($method) {
			case PAYMENT_ENGINE_SEND:
			$this->_method = $method;
			if ($this->debug) trigger_error("The mode PAYMENT_ENGINE_SEND is set.", E_USER_NOTICE);
			break;

			case PAYMENT_ENGINE_RECEIVE:
			$this->_method = $method;
			if ($this->debug) trigger_error("The mode PAYMENT_ENGINE_RECEIVE. is set", E_USER_NOTICE);
			break;

			default:
			trigger_error("The chosen mode is not defined.", E_USER_ERROR);
			break;
		}
	}

	/**
	*
	*
	*
	*/
	/*
	function generateForm($action) {
		$form = "<form method=\"post\" name=\"send_form\" action=\"".$action."\">";
		$form .= "<select name=\"payment_systems\">";
                        <option value="Black">Black</option>
                        <option value="Red">Red</option>
                        <option value="Blue">Blue</option>
		$form .= "</select></form>";
		return $form;
	}
	*/
}

?>