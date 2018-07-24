<?php

/**
 * Class Bot
 */
class Bot extends \voinmerk\botapp\BaseBot
{
	public function __construct($config)
	{
		static::$classMap = $config['classes'];

		static::$db = new \voinmerk\botapp\DB($config['db']);

		static::$token = $config['botToken'];
	}
}

spl_autoload_register(['Bot', 'autoload'], true, true);