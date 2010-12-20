<?php

include_once PAYMENT_DIR."Payment_Data_Kipper.php";

/**
* Class for SmsCoin payment system
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:31 $
**/
class Payment_smscoin extends Payment_Data_Kipper {

        /**
        *       Array for setting Payment System parameters.
        *
        *       @access private
        *       @type array
        */
        var $_arrayField = array(

        /*** MODIFIED CODE BEGIN ***/

        /* General Parameters: */

        /**
        *       Purse id.
        */
        "purse_id"             => "s_purse",

        /**
        *       Order id.
        */
        "order_id"              => "s_order_id",

        /**
        *       Payment amount.
        */
        "amount"                => "s_amount",

        /* Specific parameters: */

        /**
         * 		Billing algorithm
         */
        "clear_amount"	=> "s_clear_amount",

        /**
         * 		Signature - Hash string
         */
        "sign" => "s_sign",

        /* Product information: */

        /**
        *       Product name
        */
        "product_name"  => "s_description",


        /*** MODIFIED CODE END ***/

        );


        /**
        *       Standard constructor. Nothing should be changed here.
        *
        *       @param int $mode Mode of class funtioning
        *       @param bool $debug Debug mode
        */
        function Payment_smscoin($mode, $debug) {
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
        *       Method of class parameters initialization. It is set while being created.
        *               It is divided into 3 parts: general, receive and send.
        *
        *       @access private
        */
        function _initialization() {
                if ($this->_debug) trigger_error("Reports -> " . get_class($this). " ! _initialization()" , E_USER_NOTICE);

                /*** MODIFIED CODE BEGIN ***/

                $this->_url = "http://service.smscoin.com/bank/";
                $this->_method = "POST";
                if ($this->_mode == PAYMENT_ENGINE_SEND) {
                        $this->makeRequired('purse_id', 'order_id', 'amount', 'clear_amount', 'sign');
                        $this->makeOptional('product_name');
                }
                if ($this->_mode == PAYMENT_ENGINE_RECEIVE) {

                }

                /*** MODIFIED CODE END ***/

                if ($this->_debug) trigger_error("Processed <- " . get_class($this). " ! _initialization()." , E_USER_NOTICE);
        }

        /**
        *       Method used for sending data to the server of Payment System.
        *       According to the type of Payment System there is either redirect to the site of Payment System(POST),
        *                               or the URL is put in the heading.
        *       @access public
        *       @return mixed In case when all the parameters were ser correctly, nothing will be returned,
        *               there will be redirect, otherwise notification will be generated and false will be returned
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
        *       Checks the incoming data to be set correctly in accord with
        *               the parameters set.
        *
        *       @access public
        *       @param ... Parameters enumeration. Transfer by link.
        *       @return bool true - in case all the parameters are set correctly and the payment
        *               was successfully processed, false - in case parameters are set incorrectly or payment failed.
        */
        function checkPayment() {
                if ($this->_debug) trigger_error("Reports -> " . get_class($this). " ! checkPayment()" , E_USER_NOTICE);
                $retVal = true;
                $arg_list = func_get_args();
                if ($this->_mode == PAYMENT_ENGINE_RECEIVE) {
                        if (PAYMENT_IGNORE_TEST_PAYMENT) {

                                /*** MODIFIED CODE BEGIN ***/
								/**
								 * There are no test mode on SmsCoin
								 */
                                /*** MODIFIED CODE END ***/

                        }
                        if (PAYMENT_LOG_PAYER_INFO) {

                                /*** MODIFIED CODE BEGIN ***/
                            	// filtering junk off acquired parameters
								foreach($_REQUEST as $request_key => $request_value) {
									$_REQUEST[$request_key] = substr(strip_tags(trim($request_value)), 0, 250);
								}
                                $arg_list[0] = "s_purse: " . $_REQUEST['s_purse'] . ";\n";  // sms:bank id
                                $arg_list[0] = "s_order_id: " . $_REQUEST['s_order_id'] . ";\n"; // operation id
                                $arg_list[0] = "s_amount: " . $_REQUEST['s_amount'] . ";\n";
                                $arg_list[0] = "s_clear_amount: " . $_REQUEST['s_clear_amount'] . ";\n"; // billing algorithm
                                $arg_list[0] = "s_inv: " . $_REQUEST['s_inv'] . ";\n"; // operation number
                                $arg_list[0] = "s_phone: " . $_REQUEST['s_phone'] . ";\n";
                                $arg_list[0] = "s_sign_v2: " . $_REQUEST['s_sign_v2'] . ";"; // signature

                                /*** MODIFIED CODE END ***/

                        }
                        if ($this->_verifyData()) {

                                /*** MODIFIED CODE BEGIN ***/
								/**
								 * Verify payment in functions for SmsCoin
								 */
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

}       // end class Payment_template
?>