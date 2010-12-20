<?php

include_once PAYMENT_DIR."Payment_Data_Kipper.php";

/**
* Class for PaySat payment system
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Anton Sultanov <anton@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:31 $
**/
class Payment_paysat extends Payment_Data_Kipper {

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
	"seller_id"		=> "MerchantID",

	/**
	*	Domen ID.
	*/
	"domain_id"		=> "DomainID",

	/**
	*	Payment amount.
	*/
	"amount"		=> "Amount",

	/**
	*	Payment currency.
	*/
	"currency"		=> "Currency",

	/**
	*	Payment description.
	*/
	"order_description"	=> "OrderDescription",

	/**
	*	Order id.
	*/
	"order_id"		=> "OrderID",

	/**
	*	Order id.
	*/
	"secret_word"		=> "SecretWord",

	/*** MODIFIED CODE END ***/

	);


	/**
	*	Standard constructor. Nothing should be changed here.
	*
	*	@param int $mode Mode of class funtioning
	*	@param bool $debug Debug mode
	*/
	function Payment_paysat($mode, $debug) {
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

		$this->_url = "https://secure.paysat.com/secorder/ProShoppingCartOrder.aspx";
		$this->_method = "POST";
		if ($this->_mode == PAYMENT_ENGINE_SEND) {
			//$this->makeRequiredField('pay_form',		'PAYMENT_FORM');
			//$this->makeRequiredField('return_method', 	'TRUE');
			$this->makeRequired('seller_id', 'domain_id','amount','currency','order_description','order_id');
		}
		if ($this->_mode == PAYMENT_ENGINE_RECEIVE) {

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
			if (PAYMENT_LOG_PAYER_INFO) {

				/*** MODIFIED CODE BEGIN ***/

				$arg_list[0] = "OrderID: " . $_REQUEST['OrderID'] . ";\n";
				$arg_list[0] .= "BuyerCity: " . $_REQUEST['BuyerCity'] . ";\n";
				$arg_list[0] .= "Amount: " . $_REQUEST['Amount'] . ";\n";

				$arg_list[0] .= "MerchantOrderID: " . $_REQUEST['MerchantOrderID'] . ";\n";
				$arg_list[0] .= "BuyerState: " . $_REQUEST['BuyerState'] . ";\n";
				$arg_list[0] .= "BuyerCardHolderName: " . $_REQUEST['BuyerCardHolderName'] . ";\n";
				$arg_list[0] .= "BuyerStreetAddress: " . $_REQUEST['BuyerStreetAddress'] . ";\n";
				$arg_list[0] .= "BuyerZipCode: " . $_REQUEST['BuyerZipCode'] . ";\n";
				$arg_list[0] .= "BuyerCountry: " . $_REQUEST['BuyerCountry'] . ";\n";
				$arg_list[0] .= "OrderDescription: " . $_REQUEST['OrderDescription'] . ";\n";
				$arg_list[0] .= "Currency: " . $_REQUEST['Currency'] . ";\n";
				$arg_list[0] .= "BuyerEmail: " . $_REQUEST['BuyerEmail'] . ";\n";
				$arg_list[0] .= "BuyerPhone: " . $_REQUEST['BuyerPhone'] . ";\n";
				$arg_list[0] .= "Processed: " . $_REQUEST['Processed'] . ";\n";
				$arg_list[0] .= "SecretWord: " . $_REQUEST['SecretWord'] . ";\n";
				//.............................................................

				/*** MODIFIED CODE END ***/

			}
			if ($this->_verifyData()) {
				/*** MODIFIED CODE BEGIN ***/

				$arg_list[1] = $_REQUEST[$this->_arrayField['order_id']];
				$this_secret_word = $_REQUEST[$this->_arrayField['secret_word']];

				if ($_REQUEST['Processed'] != "Y" || strtoupper(md5($this->getField('secret_word'))) != strtoupper($this_secret_word)) {
					if ($this->_debug) trigger_error("- Транзакция прошла НЕ успешно.", E_USER_NOTICE);
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