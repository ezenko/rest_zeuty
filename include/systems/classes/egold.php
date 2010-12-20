<?php

include_once PAYMENT_DIR."Payment_Data_Kipper.php";

/**
* Class for e-gold payment system
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:31 $
**/
class Payment_egold extends Payment_Data_Kipper {

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
	"seller_id"		=> "PAYEE_ACCOUNT",

	/**
	*	Payment amount.
	*/
	"amount"		=> "PAYMENT_AMOUNT",

	/**
	*	Order id.
	*/
	"order_id"		=> "PAYMENT_ID",

	/**
	*	Merchant name
	*/
	"seller_name"	=> "PAYEE_NAME",

	/**
	*	Method for returned data. POST, GET, LINK.
	*/
	"return_good_method"	=> "PAYMENT_URL_METHOD",

	/**
	*	Method for returned data. POST, GET, LINK.
	*/
	"return_bad_method"		=> "NOPAYMENT_URL_METHOD",

	/**
	*	Return URL
	*/
	"return_good_url"	=> "PAYMENT_URL",

	/**
	*	Return URL
	*/
	"return_bad_url"	=> "NOPAYMENT_URL",

	/**
	*	Currency. 1 - USD
	*/
	"currency"		=> "PAYMENT_UNITS",

	/**
	*	What metal: Gold - 1, Silver - 2, Platinum - 3, Palladium - 4
	*/
	"metal_id"		=> "PAYMENT_METAL_ID",

	/**
	*	Additional field
	*/
	"information"	=>"BAGGAGE_FIELDS",

	/**
	*	Secret word
	*/
	"secret_word"	=> ""

	/*** MODIFIED CODE END ***/

	);


	/**
	*	Standard constructor. Nothing should be changed here.
	*
	*	@param int $mode Mode of class funtioning
	*	@param bool $debug Debug mode
	*/
	function Payment_egold($mode, $debug) {
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

		$this->_url = "https://www.e-gold.com/sci_asp/payments.asp";
		$this->_method = "POST";
		if ($this->_mode == PAYMENT_ENGINE_SEND) {
		//	$this->makeRequiredField('information',	"\"\"");
			$this->makeRequiredField('return_good_method', 'POST');
			$this->makeRequiredField('return_bad_method', 'POST');
			$this->makeRequired('seller_id', 'amount', 'order_id', 'seller_name', 'return_good_url', 'return_bad_url', 'currency', 'metal_id', 'information');
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

				// !!! There are no test mode on e-gold

				/*if (isset($_REQUEST[$this->_arrayField['test_mode']]) &&
						  $_REQUEST[$this->_arrayField['test_mode']] == "1") {
					if ($this->_debug) trigger_error("- The payment was processed in demo mode.", E_USER_NOTICE);
					$retVal = false;
				}*/

				/*** MODIFIED CODE END ***/

			}
			if (PAYMENT_LOG_PAYER_INFO) {

				/*** MODIFIED CODE BEGIN ***/

				// !!! Users' data not came to e-gold

				/*** MODIFIED CODE END ***/

			}
			if ($this->_verifyData()) {
				/*** MODIFIED CODE BEGIN ***/

				$arg_list[0] = $_REQUEST[$this->_arrayField['order_id']];

				$AlternateMerchantPassphraseHash = md5($this->getField('secret_word'));

				$combined = $_REQUEST['PAYMENT_ID'] . ":";
				$combined .= $_REQUEST['PAYEE_ACCOUNT'] . ":";
				$combined .= $_REQUEST['PAYMENT_AMOUNT'] . ":";
				$combined .= $_REQUEST['PAYMENT_UNITS'] . ":";
				$combined .= $_REQUEST['PAYMENT_METAL_ID'] . ":";
				$combined .= $_REQUEST['PAYMENT_BATCH_NUM'] . ":";
				$combined .= $AlternateMerchantPassphraseHash . ":";
				$combined .= $_REQUEST['ACTUAL_PAYMENT_OUNCES'] . ":";
				$combined .= $_REQUEST['USD_PER_OUNCE'] . ":";
				$combined .= $_REQUEST['FEEWEIGHT'] . ":";
				$combined .= $_REQUEST['TIMESTAMPGMT'];

				$rezultHash = strtoupper(md5($combined));

				if ($_REQUEST['V2_HASH'] == $rezultHash) {
					if ($this->_debug) trigger_error("- md5 hash is correct.", E_USER_NOTICE);
					$retVal = true;
				} else {
					if ($this->_debug) trigger_error("- md5 hash is incorrect!", E_USER_NOTICE);
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