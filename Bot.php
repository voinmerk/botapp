<?php

namespace voinmerk\botapp;

/**
 * Class Bot
 */
class Bot extends \voinmerk\botapp\BotBase;
{
}

spl_autoload_register(['Bot', 'autoload'], true, true);