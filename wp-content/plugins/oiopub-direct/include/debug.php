<?php

class oiopub_debug {

	var $log = array();
	
	function __construct($ticks=false) {
		//set error level
		error_reporting(E_ALL);
		//set timer
		if(function_exists('microtime')) {
			define('OIOPUB_TIME_START', microtime(true));
		}
		//set mem usage
		if(function_exists('memory_get_usage')) {
			define('OIOPUB_MEM_START', memory_get_usage());
		}
		//set shutdown function
		register_shutdown_function(array( &$this, 'log' ));
		//register ticks?
		if($ticks && function_exists('register_tick_function')) {
			declare(ticks=1);
			register_tick_function(array( &$this, 'tick' ));
		}
	}

	function log($at_shutdown=true) {
		global $oiopub_set;
		//no settings?
		if(!$oiopub_set) {
			$oiopub_set = new stdClass;
			$oiopub_set->request_uri = $_SERVER['REQUEST_URI'];
			$oiopub_set->folder_dir = dirname(dirname(__FILE__));
		}
		//setup data
		$data  = "Type: " . ($at_shutdown ? 'shutdown' : 'index.php') . "\n";
		$data .= 'Uri: ' . $oiopub_set->request_uri . "\n";
		$data .= 'Timestamp: ' . date('Y-m-d H:i:s', time()) . "\n";
		//add execution time?
		if(defined('OIOPUB_TIME_START')) {
			$data .= 'Execution time: ' . number_format((microtime(true) - OIOPUB_TIME_START), 5) . 's' . "\n";
		}
		//add memory time?
		if(defined('OIOPUB_MEM_START')) {
			$data .= 'Memory used: ' . number_format(memory_get_usage() - OIOPUB_MEM_START, 0, '.', ',') . "\n";
		}
		//add log data?
		if($this->log && $at_shutdown) {
			//loop through log
			foreach($this->log as $line) {
				$data .= $line['time'] . 's - ' . $line['memory'] . 'b - ' . str_replace(dirname($oiopub_set->folder_dir), '', $line['backtrace']) . "\n";
			}
		}
		//write to file?
		if($fp = fopen($oiopub_set->folder_dir . '/cache/DEBUG.txt', 'a')) {
			fwrite($fp, $data . "\n");
			fclose($fp);
		}
	}

	function tick() {
		//set vars
		$args = array();
		$tmp = debug_backtrace();
		//chekc output
		while($tmp) {
			//external class?
			if(!isset($tmp[0]['class']) || $tmp[0]['class'] !== __CLASS__) {
				break;
			}
			//delete
			array_shift($tmp);
		}
		//continue?
		if(isset($tmp[0]) && $tmp[0]) {
			//backtrace has args?
			if(isset($tmp[0]['args']) && $tmp[0]['args']) {
				//loop through args
				foreach($tmp[0]['args'] as $arg) {
					if(is_array($arg)) {
						$args[] = 'Array';
					} elseif(is_object($arg)) {
						$args[] = get_class($arg);
					} else {
						$args[] = (string) $arg;
					}
				}
			}
			//add to log
			$this->log[] = array(
				'time' => defined('OIOPUB_TIME_START') ? number_format((microtime(true) - OIOPUB_TIME_START), 5) : 0,
				'memory' => defined('OIOPUB_MEM_START') ? number_format(memory_get_usage() - OIOPUB_MEM_START, 0, '.', ',') : 0,
				'backtrace' => "Caller: " . (isset($tmp[0]['file']) ? $tmp[0]['file'] . " (" . $tmp[0]['line'] . ")" : "n/a") . " | Calling: " . (isset($tmp[0]['class']) ? $tmp[0]['class'] . '::' : '') . $tmp[0]['function'] . "(" . ($args ? implode(', ', $args) : "") . ")",
			);
		}
	}

}