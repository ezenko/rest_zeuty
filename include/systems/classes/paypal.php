<?php

include_once PAYMENT_DIR."Payment_Data_Kipper.php";

/**
* Class for paypal payment system
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:31 $
**/
class Payment_paypal extends Payment_Data_Kipper {

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
        *       Merchant id.
        */
        "seller_id"             => "business",

        /**
        *       Payment amount.
        */
        "amount"                => "amount",

        /**
        *       Order id.
        */
        "order_id"              => "custom",

        /**
        *       Test mode.
        */
        "test_mode"             => "test_ipn",

        /* Return settings": */

        /**
        *       Method of data return. If you set 2, it will be POST.
        */
        "return_method" => "rm",

        /**
        *       Return URL
        */
        "return_url"    => "return",
        /**
        *       Notify URL
        */
        "notify_url"    => "notify_url",
        /**
        *       Cancel return
        */
        "cancel_return"    => "cancel_return",

        /* Specific parameters: */

        /**
        *       Action type. _xclick - "buy now" button
        */
        "type"                  => "cmd",

        /* Product information: */

        /**
        *       Product name
        */
        "product_name"  => "item_name",

        "currency"      => "currency_code",

        /**
        *       Product id
        */
        "product_id"    => "item_number"

        /*** MODIFIED CODE END ***/

        );


        /**
        *       Standard constructor. Nothing should be changed here.
        *
        *       @param int $mode Mode of class funtioning
        *       @param bool $debug Debug mode
        */
        function Payment_paypal($mode, $debug) {
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

                $this->_url = "https://www.paypal.com/cgi-bin/webscr";
                $this->_method = "POST";
                if ($this->_mode == PAYMENT_ENGINE_SEND) {
                        $this->makeRequiredField('type',                        '_xclick');
                        $this->makeRequiredField('return_method',       '2');
                        $this->makeRequired('seller_id', 'order_id', 'amount', 'return_url', 'cancel_return');
                        $this->makeOptional('product_name');
                        $this->makeRequired('currency', 'notify_url');
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

                                if (isset($_REQUEST[$this->_arrayField['test_mode']]) &&
                                $_REQUEST[$this->_arrayField['test_mode']] == "1") {
                                        if ($this->_debug) trigger_error("- The payment was processed in demo mode.", E_USER_NOTICE);
                                        $retVal = false;
                                }

                                /*** MODIFIED CODE END ***/

                        }
                        if (PAYMENT_LOG_PAYER_INFO) {

                                /*** MODIFIED CODE BEGIN ***/

                                $arg_list[0] = "payer_id: " . $_REQUEST['payer_id'] . ";\n";
                                $arg_list[0] .= "address_street: " . $_REQUEST['address_street'] . ";\n";
                                $arg_list[0] .= "address_zip: " . $_REQUEST['address_zip'] . ";\n";
                                $arg_list[0] .= "first_name: " . $_REQUEST['first_name'] . ";\n";
                                $arg_list[0] .= "address_name: " . $_REQUEST['address_name'] . ";\n";
                                $arg_list[0] .= "address_country: " . $_REQUEST['address_country'] . ";\n";
                                $arg_list[0] .= "address_city: " . $_REQUEST['address_city'] . ";\n";
                                $arg_list[0] .= "payer_email: " . $_REQUEST['payer_email'] . ";\n";
                                $arg_list[0] .= "address_state: " . $_REQUEST['address_state'] . ";\n";
                                $arg_list[0] .= "payer_business_name: " . $_REQUEST['payer_business_name'] . ";\n";
                                $arg_list[0] .= "last_name: " . $_REQUEST['last_name'] . ";";

                                /*** MODIFIED CODE END ***/

                        }
                        if ($this->_verifyData()) {
                                /*** MODIFIED CODE BEGIN ***/

                                $arg_list[1] = $_REQUEST[$this->_arrayField['order_id']];

                                if ($_REQUEST['payer_status'] != "verified") {
                                        if ($this->_debug) trigger_error("- Payer's account was not verified by PayPal.", E_USER_NOTICE);
                                        $retVal = false;
                                }
                                if ($_REQUEST['address_status'] != "confirmed") {
                                        if ($this->_debug) trigger_error("- Payer's address data were not confirmed by PayPal.", E_USER_NOTICE);
                                        $retVal = false;
                                }
                                if ($_REQUEST['payment_status'] != "Completed") {
                                        if ($this->_debug) trigger_error("- Transaction was not processed.", E_USER_NOTICE);
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

}       // end class Payment_template
?>