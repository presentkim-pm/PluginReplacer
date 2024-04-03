<?php

/**
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author       PresentKim (debe3721@gmail.com)
 * @link         https://github.com/PresentKim
 * @license      https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 *
 * @noinspection PhpUnused
 */

declare(strict_types=1);

namespace kim\present\pluginreplacer;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginDescriptionParseException;
use pocketmine\Server;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Path;

use function copy;
use function file_exists;
use function is_array;
use function is_dir;
use function is_file;
use function mkdir;
use function register_shutdown_function;
use function rename;
use function scandir;
use function str_ends_with;
use function strtolower;
use function unlink;
use function yaml_parse;

final class Main extends PluginBase{


    /** @var string The directory where the replacement plugins are stored */
    private string $replacementsDir;

    protected function onLoad() : void{
        $this->replacementsDir = Path::join($this->getServer()->getDataPath(), "plugin_replace");
        if(!is_dir($this->replacementsDir)){
            mkdir($this->replacementsDir);
        }
    }

    protected function onEnable() : void{
        register_shutdown_function(function() : void{
            // Pass if the replacement directory does not exist
            if(!file_exists($this->replacementsDir) || !is_dir($this->replacementsDir)){
                return;
            }

            $replacementFiles = scandir($this->replacementsDir);
            if(!is_array($replacementFiles)){
                return;
            }

            $pluginsDir = Path::join(Server::getInstance()->getDataPath(), "plugins");
            $oldPluginMap = [];
            foreach(scandir($pluginsDir) as $file){
                if(!str_ends_with($file, ".phar")){
                    continue;
                }

                // Get plugin info of existing plugin
                $path = Path::join($pluginsDir, $file);
                try{
                    $pluginInfo = self::readPluginInfo($path);
                }catch(\Exception){
                    $this->getLogger()->warning("Failed to get plugin info from $path");
                    continue;
                }
                [$pluginName] = $pluginInfo;

                // Store plugin info
                $oldPluginMap[strtolower($pluginName)] = $path;
            }

            foreach($replacementFiles as $file){
                if(!str_ends_with($file, ".phar")){
                    continue;
                }

                // Get plugin info of replacement plugin
                $path = Path::join($this->replacementsDir, $file);
                try{
                    $pluginInfo = self::readPluginInfo($path);
                }catch(\Exception){
                    $this->getLogger()->error("Failed to get plugin info from $path");
                    continue;
                }
                [$pluginName, $pluginVersion] = $pluginInfo;

                // Delete old plugins
                $oldPluginPath = $oldPluginMap[strtolower($pluginName)] ?? null;
                if($oldPluginPath !== null && file_exists($oldPluginPath) && !unlink($oldPluginPath)){
                    $this->getLogger()->error("Failed to delete old plugin $pluginName");
                    continue;
                }

                // Replace new plugins
                $newPluginPath = Path::join($pluginsDir, "{$pluginName}_v$pluginVersion.phar");
                if(!rename($path, $newPluginPath)){
                    if(!copy($path, $newPluginPath)){
                        $this->getLogger()->error("Failed to replace plugin $pluginName");
                        continue;
                    }

                    $this->getLogger()->warning("Failed to replace plugin $pluginName, copied instead");
                }
                $this->getLogger()->info("Replaced plugin $pluginName");
            }
        });
    }

    /**
     * Get plugin name and version from phar file
     *
     * @param string $pharPath
     *
     * @return string[] Returns [plugin name, plugin version] when successfully parse the plugin.yml,
     *                      otherwise returns null
     * @phpstan-return array{string, string}
     *
     * @throws FileNotFoundException
     */
    private static function readPluginInfo(string $pharPath) : array{
        if(!is_file($pharPath)){
            throw new FileNotFoundException("Not found file in $pharPath");
        }
        $phar = new \Phar($pharPath);

        if(!$phar->offsetExists("plugin.yml")){
            throw new FileNotFoundException("Not found plugin.yml in $pharPath/plugin.yml");
        }

        $pluginYml = yaml_parse($phar->offsetGet("plugin.yml")->getContent());
        if(!is_array($pluginYml) || !isset($pluginYml["name"], $pluginYml["version"])){
            throw new PluginDescriptionParseException("Invalid plugin.yml in $pharPath/plugin.yml");
        }
        return [$pluginYml["name"], $pluginYml["version"]];
    }
}
