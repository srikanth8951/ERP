<?php
namespace App\Libraries\Template;

final class Twig_loader
{

	// private $_Functions = ['helper'];
	// private $_Globals = [];

	// public function setFunctions($Functions)
	// {
	// 	$mergedFunctions = array_merge($this->_Functions, $Functions);
	// 	$this->_Functions = array_unique($mergedFunctions);
	// }

	// public function setGlobals($Globals)
	// {
	// 	$mergedFunctions = array_merge($this->_Globals, $Globals);
	// 	$this->_Globals = array_unique($mergedGlobals);
	// }

	public function render($filename, $args = [], $path = null) 
	{

		$twigConfig = new \Config\Twig();
		if (!$path) {
			$path = APPPATH . 'Views';
		}

		$file = $filename . '.twig';
		
		// initialize Twig environment
		$config = array(
			'autoescape'  => false,
			'debug'       => false,
			'auto_reload' => true,
			'cache'       => WRITEPATH . 'cache/twig'
		);

		try {
			$loader = new \Twig\Loader\FilesystemLoader($path);

			$twig = new \Twig\Environment($loader, $config);

			// functions
			$_functions = $twigConfig->functions;
			foreach ($_functions as $function) {
	            if (function_exists($function)) {
	            	$twigFunction = new \Twig\TwigFunction($function, $function);
	                $twig->addFunction($twigFunction);
	            }
	        }

	        // globals
	        $_globals = $twigConfig->globals;
			foreach ($_globals as $globalz) {
	            $twig->addGlobal($globalz['key'], $globalz['value']);   
	        }
			

			return $twig->render($file, $args);
		} catch (Exception $e) {
			trigger_error('Error: Could not load template ' . $filename . '!');
			exit();
		}	
	}	
}
