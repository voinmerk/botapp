<?php

namespace voinmerk\botapp;

class Application
{
	private $classMap;

	public function __construct($config)
	{
		$this->classMap = $config['classes'];
	}

	public function run()
	{

	}

	public function autoload($className)
	{
		
	}
}