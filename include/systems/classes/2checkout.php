<?php

include_once PAYMENT_DIR."Payment_Data_Kipper.php";

/**
* Class for 2checkout payment system
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:31 $
**/
class Payment_2checkout extends Payment_Data_Kipper {

	/**
	*	Array for setting Payment System parameters.
	*
	*	@access private
	*	@type array
	*/
	var $_arrayField = array(

	/*** MODIFIED CODE BEGIN ***/

	/* General Parameters: */

    /**
    *       Merchant id.
    */
	"seller_id"		=> "sid",

	/**
	*	Payment amount.
	*/
	"amount"		=> "total",

	/**
	*	Order id.
	*/
	"order_id"		=> "cart_order_id",

	/**
	*	Test mode.
	*/
	"test_mode"		=> "demo",

	"secret_word"	=> "key"

	/*** MODIFIED CODE END ***/

	);


	/**
	*	Standard constructor. Nothing should be changed here.
	*
	*	@param int $mode Mode of class funtioning
	*	@param bool $debug Debug mode
	*/
	function Payment_2checkout($mode, $debug) {
		$this->_debug = $debug;
		if ($this->_debug) trigger_error("Reports -> " . get_class($this). " ! Constructor()." , E_USER_NOTICE);

		if ( ($mode == PAYMENT_ENGINE_SEND) || ($mode == PAYMENT_ENGINE_RECEIVE) ) {
			$this->_mode = $mode;
			if ($this->_debug) trigger_error("- Mode set " . $this->_mode . " ." , E_USER_NOTICE);
		} else {
			if ($this->_debug) trigger_error("- Incorrect mode set " . $this->_mode . " !!" , E_USER_ERROR);
			exit();
		}
		$this->_initialization();
		$this->makeFields();
		if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! Constructor()." , E_USER_NOTICE);
	}

	/**
	*	Method of class parameters initialization. It is set while being created.
	*		It is divided into 3 parts: general, receive and send.
	*
	*	@access private
	*/
	function _initialization() {
		if ($this->_debug) trigger_error("Reports -> " . get_class($this). " ! _initialization()" , E_USER_NOTICE);

		/*** MODIFIED CODE BEGIN ***/

		$this->_url = "https://www.2checkout.com/2co/buyer/purchase";
		$this->_method = "GET";
		if ($this->_mode == PAYMENT_ENGINE_SEND) {
			$this->makeRequired('seller_id', 'amount', 'order_id');
			$this->makeOptional('test_mode', 'seller_id');
		}
		if ($this->_mode == PAYMENT_ENGINE_RECEIVE) {
			$this->makeRequired('secret_word');
		}

		/*** MODIFIED CODE END ***/

		if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! _initialization()." , E_USER_NOTICE);
	}

	/**
	*	Method used for sending data to the server of Payment System.
	*		According to the type of Payment System there is either redirect to the site of Payment System(POST),
	*		or the URL is put in the heading.
	*
	*	@access public
	*	@return mixed In case when all the parameters were ser correctly, nothing will be returned,
	*		there will be redirect, otherwise notification will be generated and false will be returned
	*/
	function doPayment() {
		if ($this->_debug) trigger_error("Reports -> " . get_class($this). " ! doPayment()" , E_USER_NOTICE);
		if ($this->_mode == PAYMENT_ENGINE_SEND) {
			if ($this->_verifyData()) {
				$this->formMessage();
				if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! doPayment()." , E_USER_NOTICE);
				exit();
			} else {
				if ($this->_debug) trigger_error("- Parameters set incorrectly.", E_USER_WARNING);
				if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! doPayment()." , E_USER_NOTICE);
				return false;
			}
		} else {
			trigger_error("- In mode PAYMENT_ENGINE_RECEIVE method doPayment() is not available.", E_USER_ERROR);
		}
		if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! doPayment()." , E_USER_NOTICE);
		return false;
	}

	/**
	*	Checks the incoming data to be set correctly in accord with
	*		the parameters set.
	*
	*	@access public
	*	@param ... Parameters enumeration. Transfer by link.
	*	@return bool true- in case all the parameters are set correctly and the payment
	*		was successfully processed, false - in case parameters are set incorrectly or payment failed.
	*/
	function checkPayment() {
		if ($this->_debug) trigger_error("Reports -> " . get_class($this). " ! checkPayment()" , E_USER_NOTICE);
		$retVal = true;
		$arg_list = func_get_args();
		if ($this->_mode == PAYMENT_ENGINE_RECEIVE) {
			if (PAYMENT_IGNORE_TEST_PAYMENT) {

				/*** MODIFIED CODE BEGIN ***/

				if (isset($_REQUEST[$this->_arrayField['test_mode']]) &&
						  $_REQUEST[$this->_arrayField['test_mode']] == "Y") {
					if ($this->_debug) trigger_error("- The payment was processed in demo mode.", E_USER_NOTICE);
					$retVal = false;
				}

				/*** MODIFIED CODE END ***/

			}
			if (PAYMENT_LOG_PAYER_INFO) {

				/*** MODIFIED CODE BEGIN ***/

				$arg_list[0] = "card_holder_name: " . $_REQUEST['card_holder_name'] . ";\n";
				$arg_list[0] .= "email: " . $_REQUEST['email'] . ";\n";
				$arg_list[0] .= "phone: " . $_REQUEST['phone'] . ";\n";
				$arg_list[0] .= "street_address: " . $_REQUEST['street_address'] . ";\n";
				$arg_list[0] .= "city: " . $_REQUEST['city'] . ";\n";
				$arg_list[0] .= "state: " . $_REQUEST['state'] . ";\n";
				$arg_list[0] .= "zip: " . $_REQUEST['zip'] . ";\n";
				$arg_list[0] .= "country: " . $_REQUEST['country'] . ";\n";
				$arg_list[0] .= "ship_name: " . $_REQUEST['ship_name'] . ";\n";
				$arg_list[0] .= "ship_street_address: " . $_REQUEST['ship_street_address'] . ";\n";
				$arg_list[0] .= "ship_city: " . $_REQUEST['ship_city'] . ";\n";
				$arg_list[0] .= "ship_state: " . $_REQUEST['ship_state'] . ";\n";
				$arg_list[0] .= "ship_zip: " . $_REQUEST['ship_zip'] . ";\n";
				$arg_list[0] .= "ship_country: " . $_REQUEST['ship_country'] . ";";

				/*** MODIFIED CODE END ***/

			}
			if ($this->_verifyData()) {

				/*** MODIFIED CODE BEGIN ***/

				$combine = $this->getField('secret_word').$this->getField('seller_id').$_REQUEST['order_number'].$_REQUEST['total'];
				$md5key = md5($combine);
				if (strtoupper($md5key) === strtoupper($_REQUEST[$this->_arrayField['test_mode']])) {
					if ($this->_debug) trigger_error("- The payment was successfully processed.", E_USER_NOTICE);
					$retVal = true;
				} else {
					if ($this->_debug) trigger_error("- The payment failed!", E_USER_NOTICE);
					$retVal = false;
				}

				/*** MODIFIED CODE END ***/

			} else {
				if ($this->_debug) trigger_error("- Some obligatory parameters were not set.", E_USER_ERROR);
				$retVal = false;
			}
		} else {
			trigger_error("- In mode PAYMENT_ENGINE_SEND method verifyIncoming() is not available.", E_USER_ERROR);
			$retVal = false;
		}
		if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! doPayment()." , E_USER_NOTICE);
		return $retVal;
	}

}	// end class Payment_template
?>