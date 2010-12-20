<?php

include_once PAYMENT_DIR."Payment_Data_Kipper.php";

/**
* Class for authorizenet payment system
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:31 $
**/
class Payment_authorizenet extends Payment_Data_Kipper {

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
	"seller_id"		=> "x_login",
	"seller_key"   => "x_tran_key",

	/**
	*	Payment amount.
	*/
	"amount"		=> "x_amount",

	/**
	*	Order id.
	*/
	"order_id"		=> "x_description",

	/**
	*	Test mode.
	*/
	"test_mode"		=> "x_test_request",

	/* Параметры "возврата": */

	/**
	*	Включает режим возврата при установке TRUE.
	*/
	"return_method"	=> "x_relay_response",

	/**
	*	URL возврата
	*/
	"return_url"	=> "x_relay_url",
	"delim_data"	=> "x_delim_data",

	/* Специфические параметры: */

	/**
	*	Параметр указывает как должна проходить оплата: по к.к.
	*	или по электронному чеку "CC,ECHECK"
	*/
	"pay_method"	=> "x_method",

	/**
	*	Параметр указывает что требуеться от шлюза. AUTH_CAPTURE -
	*	указывает, что практически никаких данных о пользователе нет
	*/
	"pay_type"		=> "x_type",

	"currency"		=> "x_currency_code",

	"sequence"		=> "x_fp_sequence",
	"timestamp"		=> "x_fp_timestamp",
	"hash"			=> "x_fp_hash",
	/**
	*	Параметр отвечает за выводимую форму "PAYMENT_FORM"
	*/
	"pay_form"		=> "x_show_form",
	"card_num"		=> "x_card_num",
	"exp_date"		=> "x_exp_date"


	/*** MODIFIED CODE END ***/

	);


	/**
	*	Standard constructor. Nothing should be changed here.
	*
	*	@param int $mode Mode of class funtioning
	*	@param bool $debug Debug mode
	*/
	function Payment_authorizenet($mode, $debug) {
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

		$this->_url = "https://secure.authorize.net/gateway/transact.dll";
		$this->_method = "POST";
		if ($this->_mode == PAYMENT_ENGINE_SEND) {

			$this->makeRequired("seller_id",
								"order_id",
								"amount",
								"sequence",
								"timestamp",
								"hash",
								"currency",
								"pay_method",
								"test_mode",
								"pay_form" );
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
			if (PAYMENT_IGNORE_TEST_PAYMENT) {

				/*** MODIFIED CODE BEGIN ***/

				if (isset($_REQUEST[$this->_arrayField['test_mode']]) &&
				$_REQUEST[$this->_arrayField['test_mode']] == "TRUE") {
					if ($this->_debug) trigger_error("- The payment was processed in demo mode.", E_USER_NOTICE);
					$retVal = false;
				}

				/*** MODIFIED CODE END ***/

			}
			if (PAYMENT_LOG_PAYER_INFO) {

				/*** MODIFIED CODE BEGIN ***/

				$arg_list[0] = "x_first_name: " . $_REQUEST['x_first_name'] . ";\n";
				$arg_list[0] .= "x_last_name: " . $_REQUEST['x_last_name'] . ";\n";
				$arg_list[0] .= "x_company: " . $_REQUEST['x_company'] . ";\n";
				$arg_list[0] .= "x_address: " . $_REQUEST['x_address'] . ";\n";
				$arg_list[0] .= "x_city: " . $_REQUEST['x_city'] . ";\n";
				$arg_list[0] .= "x_state: " . $_REQUEST['x_state'] . ";\n";
				$arg_list[0] .= "x_zip: " . $_REQUEST['x_zip'] . ";\n";
				$arg_list[0] .= "x_country: " . $_REQUEST['x_country'] . ";\n";
				$arg_list[0] .= "x_phone: " . $_REQUEST['x_phone'] . ";\n";
				$arg_list[0] .= "x_email: " . $_REQUEST['x_email'] . ";\n";
				//.............................................................

				/*** MODIFIED CODE END ***/

			}
			if ($this->_verifyData()) {
				/*** MODIFIED CODE BEGIN ***/

				$arg_list[1] = $_REQUEST[$this->_arrayField['order_id']];

				if ($_REQUEST['x_response_code'] != "1") {
					if ($this->_debug) trigger_error("- Transaction failed!", E_USER_NOTICE);
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