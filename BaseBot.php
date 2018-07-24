<?php

namespace voinmerk\botapp;

defined('BOT_DEBUG') or define('BOT_DEBUG', false);

/**
 * Class BaseBot
 */
class BaseBot
{
	public static $classMap = [];

	public static $aliases = ['@bot' => __DIR__];

	public static $db;

    public static $token;

    public function run()
    {
        return 1;
    }

	public static function autoload($className)
    {
        if (isset(static::$classMap[$className])) {
            $classFile = static::$classMap[$className];
            if ($classFile[0] === '@') {
                $classFile = static::getAlias($classFile);
            }
        } elseif (strpos($className, '\\') !== false) {
            $classFile = static::getAlias('@' . str_replace('\\', '/', $className) . '.php', false);
            if ($classFile === false || !is_file($classFile)) {
                return;
            }
        } else {
            return;
        }

        include $classFile;

        if (BOT_DEBUG && !class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
            throw new \Exception("Unable to find '$className' in file: $classFile. Namespace missing?");
        }
    }

    public static function getAlias($alias, $throwException = true)
    {
        if (strncmp($alias, '@', 1)) {
            // not an alias
            return $alias;
        }

        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);

        if (isset(static::$aliases[$root])) {
            if (is_string(static::$aliases[$root])) {
                return $pos === false ? static::$aliases[$root] : static::$aliases[$root] . substr($alias, $pos);
            }

            foreach (static::$aliases[$root] as $name => $path) {
                if (strpos($alias . '/', $name . '/') === 0) {
                    return $path . substr($alias, strlen($name));
                }
            }
        }

        if ($throwException) {
            throw new \Exception("Invalid path alias: $alias");
        }

        return false;
    }

    public static function getRootAlias($alias)
    {
        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);

        if (isset(static::$aliases[$root])) {
            if (is_string(static::$aliases[$root])) {
                return $root;
            }

            foreach (static::$aliases[$root] as $name => $path) {
                if (strpos($alias . '/', $name . '/') === 0) {
                    return $name;
                }
            }
        }

        return false;
    }

    public static function setAlias($alias, $path)
    {
        if (strncmp($alias, '@', 1)) {
            $alias = '@' . $alias;
        }
        $pos = strpos($alias, '/');
        $root = $pos === false ? $alias : substr($alias, 0, $pos);
        if ($path !== null) {
            $path = strncmp($path, '@', 1) ? rtrim($path, '\\/') : static::getAlias($path);
            if (!isset(static::$aliases[$root])) {
                if ($pos === false) {
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [$alias => $path];
                }
            } elseif (is_string(static::$aliases[$root])) {
                if ($pos === false) {
                    static::$aliases[$root] = $path;
                } else {
                    static::$aliases[$root] = [
                        $alias => $path,
                        $root => static::$aliases[$root],
                    ];
                }
            } else {
                static::$aliases[$root][$alias] = $path;
                krsort(static::$aliases[$root]);
            }
        } elseif (isset(static::$aliases[$root])) {
            if (is_array(static::$aliases[$root])) {
                unset(static::$aliases[$root][$alias]);
            } elseif ($pos === false) {
                unset(static::$aliases[$root]);
            }
        }
    }

    public static function log($update, $time)
    {
        if(!empty($update["message"]))
        {
            if(!empty($update["message"][text]))
            {
                if($update[message][text][0] == '/')
                {
                    $this->addLog($update[message][from][id],'info', $update[message][text], json_encode($update), $time, 'command');
                }
                else
                {
                    $this->addLog($update[message][from][id],'info', $update[message][text], json_encode($update), $time, 'text message');
                }
            }
            elseif(!empty($update["message"][audio]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][audio][file_id], json_encode($update), $time, 'audio message');
            }
            elseif(!empty($update["message"][document]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][document][file_id], json_encode($update), $time, 'document message');
            }
            elseif(!empty($update["message"][video]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][video][file_id], json_encode($update), $time, 'video message');
            }
            elseif(!empty($update["message"][video_note]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][videonote][file_id], json_encode($update), $time, 'videonote message');
            }
            elseif(!empty($update["message"][contact]))
            {
                $this->addLog($update[message][from][id],'info', 'contact', json_encode($update), $time, 'contact message');
            }
            elseif(!empty($update["message"][location]))
            {
                $this->addLog($update[message][from][id],'info', 'location', json_encode($update), $time, 'location message');
            }
            elseif(!empty($update["message"][venue]))
            {
                $this->addLog($update[message][from][id],'info', 'venue', json_encode($update), $time, 'venue message');
            }
            elseif(!empty($update["message"][game]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][game][title], json_encode($update), $time, 'game message');
            }
            elseif(!empty($update["message"][sticker]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][sticker][file_id], json_encode($update), $time, 'sticker message');
            }
            elseif(!empty($update["message"][voice]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][voice][file_id], json_encode($update), $time, 'voice message');
            }
            elseif(!empty($update["message"][photo]))
            {
                $this->addLog($update[message][from][id],'info', $update[message][photo][file_id], json_encode($update), $time, 'photo message');
            }
            else
            {
                $this->addLog($update[message][from][id],'warning', 'unknown message', json_encode($update), $time, 'unknown message');
            }
        }
        elseif(!empty($update["edited_message"]))
        {
            //handle_message($update["edited_message"], $website);
        }
        elseif(!empty($update["callback_query"]))
        {
            $this->addLog($update[callback_query][from][id],'info', $update[callback_query][data], json_encode($update), $time, 'callback_query');
        }
        else
        {
            $this->addLog(0,'warning', 'unknown', json_encode($update), $time, 'unknown');
        }
    }

    private function addLog($chatId,$status, $msg, $source, $lead_time, $type)
    {
        static::$db->query("INSERT INTO log (user_id, text, status, source, lead_time, type) VALUES (
            $chatId, '$msg', '$status', '$source', $lead_time, '$type'
        )");

        return true;
    }

}