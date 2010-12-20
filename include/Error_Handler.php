<?PHP
/**
* ����� �������� �� ��������� ������. �� ����� �������� ���������� ��
* ������ �� �����, ����������, ���������� � ����.
* �������� �� ��������� - ����� �� �����.
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

if (!defined('ERROR_HANDLER_CONFIG')) {
	die("����� ������������ ���� " . __FILE__ . " ���������� ���������� ������!");
}

class Error_Handler {

	/**
	*	����� ������ ����������� ������. ��������� ��������: �����������, �����
	*		�� ����� � ������ � ���� ������.
	*
	*	@access private
	*	@type int
	*/
	var $_mode;

	/**
	*	����������� �����������
	*
	*	@access public
	*/
	function Error_Handler($mode = ERROR_HANDLER_MODE_DISPLAY) {
		$this->setMode($mode);
	}

	/**
	*	������������� ������ ������ ������
	*
	*	@access public
	*	@return bool ����� true � ������ ������ ������������� ������
	*/
	function setMode($mode) {
		switch ($mode) {
			case ERROR_HANDLER_MODE_LOG:
			$this->_mode = $mode;
			$this->_setHandler("errorHahdlerLog");
			break;

			case ERROR_HANDLER_MODE_DISPLAY:
			$this->_mode = $mode;
			$this->_setHandler("errorHandlerDisplay");
			break;

			case ERROR_HANDLER_MODE_DATABASE:
			trigger_error("����� ������ � ����� �� ����������", E_USER_WARNING);
			break;

			default:
			trigger_error("������� ����� ������", E_USER_WARNING);
			break;
		}
	}

	/**
	*	���������� ������, ������� ������� ���� �� �����
	*
	*	@access public
	*	@param string $type ��� ������
	*	@param string $msg �������� ������
	*	@param string $file ��� �����
	*	@param string $line ����� ������
	*	@param mixed $context �������� ������
	*/
	function errorHandlerDisplay($type, $msg, $file, $line, $context) {
		switch($type) {
			case E_USER_ERROR:
			echo "<hr>";
			echo "ERROR!<br>At line $line of file $file.<br>Message:<br><b>$msg</b><br>";
			echo "<font color=red><i>Script	terminated!!!</i></font><br>";
			echo "Context:<pre>";
			print_r($context);
			echo "</pre><hr>";
			die();
			break;

			case E_USER_WARNING:
			echo "<hr>";
			echo "<font color=blue>WARNING!</font>";
			echo " At line $line of file $file.<br>";
			echo "<font color=blue>Message:</font> <b><font color=red>$msg</font></b><br>";
			echo "<font color=blue>Context:</font><pre>";
			print_r($context);
			echo "</pre><hr>";
			break;

			case E_USER_NOTICE:
			echo "<font color=gray>NOTICE! </font><b>$msg</b><br>";
			break;

			default:
			//����������
			break;
		}
	}

	/**
	*	���������� ������, ������� ����� ���� � ���
	*
	*	@access public
	*	@param string $type ��� ������
	*	@param string $msg �������� ������
	*	@param string $file ��� �����
	*	@param string $line ����� ������
	*	@param mixed $context �������� ������
	*/
	function errorHahdlerLog($type, $msg, $file, $line, $context) {
		switch($type) {
			case E_USER_ERROR:
			error_log( "<hr>", 3, "log.html");
			error_log( "ERROR!<br>At line $line of file $file.<br>Message:<br><b>$msg</b><br>", 3, "log.html");
			error_log( "<font color=red><i>Script	terminated!!!</i></font><br>", 3, "log.html");
			//error_log( "Context:<pre>", 3, "log.html");
			//print_r($context);
			//error_log( "</pre><hr>", 3, "log.html");
			die();
			break;

			case E_USER_WARNING:
			error_log( "<hr>", 3, "log.html");
			error_log( "<font color=blue>WARNING!</font>", 3, "log.html");
			error_log( " At line $line of file $file.<br>", 3, "log.html");
			error_log( "<font color=blue>Message:</font> <b><font color=red>$msg</font></b><br>", 3, "log.html");
			//error_log( "<font color=blue>Context:</font><pre>", 3, "log.html");
			//error_log( print_r($context), 3, "log.html");
			//error_log( "</pre><hr>", 3, "log.html");
			break;

			case E_USER_NOTICE:
			error_log( "<font color=gray>NOTICE! </font><b>$msg</b><br>", 3, "log.html");
			break;

			default:
			//����������
			break;
		}
	}

	/**
	*	���������� ������, ������� ����� ���� � ���� ������
	*
	*	@access public
	*	@param string $type ��� ������
	*	@param string $msg �������� ������
	*	@param string $file ��� �����
	*	@param string $line ����� ������
	*	@param mixed $context �������� ������
	*/
	function errorHahdlerDatabase($type, $msg, $file, $line, $context) {
	}

	/**
	*	������������ ����� ������ ��� ���������� � ������������� ���
	*		������������ ������
	*
	*	@access private
	*	@param string $callback_func ��� ������ �������� ������ �� ���������
	*/
	function _setHandler($callback_func) {
		//��������� ���������� �� ��� ����������
		if (!isset($GLOBALS['_ERROR_HANDLER_METHOD']) || $GLOBALS['_ERROR_HANDLER_METHOD'] != $callback_func) {
			$GLOBALS['_ERROR_HANDLER_OBJECT'] = &$this;
			$GLOBALS['_ERROR_HANDLER_METHOD'] = $callback_func;
			function error_handler_passthru($type, $msg, $file, $line, $context) {
				$GLOBALS['_ERROR_HANDLER_OBJECT']->
				$GLOBALS['_ERROR_HANDLER_METHOD']($type, $msg, $file, $line, $context);
			}
			set_error_handler('error_handler_passthru');
		}
	}

	/**
	*	�������� ������� print_r, ���� � ��� ��������, ��� ����������
	*		��������������� ������, � �� ���������� �� �� �����
	*
	*	@access private
	*	@param mixed $content ����� ���������� ��� �������
	*	@return string ����������� �������� $content
	*/
	function _print_r($content) {
		return "_print_r() not implemented!!!";
	}

}

?>