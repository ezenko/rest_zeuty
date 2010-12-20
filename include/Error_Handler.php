<?PHP
/**
* Класс отвечает за обработку ошибок. Он может выводить информацию об
* ошибке на экран, логировать, записывать в базу.
* Значение по умолчанию - вывод на экран.
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author Sergey Bokonyaev <bokal@pilotgroup.net> $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

if (!defined('ERROR_HANDLER_CONFIG')) {
	die("Чтобы использовать файл " . __FILE__ . " необходимо подключить конфиг!");
}

class Error_Handler {

	/**
	*	Режим работы обработчика ошибок. Возможные варианты: логирование, вывод
	*		на экран и запись в базу данных.
	*
	*	@access private
	*	@type int
	*/
	var $_mode;

	/**
	*	Стандартный конструктор
	*
	*	@access public
	*/
	function Error_Handler($mode = ERROR_HANDLER_MODE_DISPLAY) {
		$this->setMode($mode);
	}

	/**
	*	Переключатель режима работы класса
	*
	*	@access public
	*	@return bool Вернёт true в случае выбора поддерживаемо режима
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
			trigger_error("Режим работы с базой не реализован", E_USER_WARNING);
			break;

			default:
			trigger_error("Неверны режим работы", E_USER_WARNING);
			break;
		}
	}

	/**
	*	Обработчик ошибок, который выводит инфу на экран
	*
	*	@access public
	*	@param string $type Тип ошибки
	*	@param string $msg Описание ошибки
	*	@param string $file Имя файла
	*	@param string $line Номер строки
	*	@param mixed $context Контекст ошибки
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
			//игнорируем
			break;
		}
	}

	/**
	*	Обработчик ошибок, который пишет инфу в лог
	*
	*	@access public
	*	@param string $type Тип ошибки
	*	@param string $msg Описание ошибки
	*	@param string $file Имя файла
	*	@param string $line Номер строки
	*	@param mixed $context Контекст ошибки
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
			//игнорируем
			break;
		}
	}

	/**
	*	Обработчик ошибок, который пишет инфу в базу данных
	*
	*	@access public
	*	@param string $type Тип ошибки
	*	@param string $msg Описание ошибки
	*	@param string $file Имя файла
	*	@param string $line Номер строки
	*	@param mixed $context Контекст ошибки
	*/
	function errorHahdlerDatabase($type, $msg, $file, $line, $context) {
	}

	/**
	*	Регистрирует метод класса как глобальный и устанавливает его
	*		обработчиком ошибок
	*
	*	@access private
	*	@param string $callback_func Имя метода текущего класса по обработке
	*/
	function _setHandler($callback_func) {
		//проверяем установлен ли уже обработчик
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
	*	Дубликат функции print_r, лишь с тем отличием, что возвращает
	*		форматированную строку, а не выплёвывает всё на экран
	*
	*	@access private
	*	@param mixed $content Набор переменных или классов
	*	@return string Разобранное значение $content
	*/
	function _print_r($content) {
		return "_print_r() not implemented!!!";
	}

}

?>