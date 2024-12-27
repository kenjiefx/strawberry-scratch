<?php 

namespace Kenjiefx\StrawberryScratch\Services;

use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class PageSyncService {

    private static array $pages = [];

    private static array $typesloc = [
        '/interfaces/strawberry-scratch/pages.ts' => '__AutoPageName'
    ];

    public static function sync(string $dir){
        $files = array_filter(
            scandir($dir), 
            function($file){
                return $file !== '.' && $file !== '..';
            }
        );
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                self::sync($path);
            } else {
                $page = str_replace(
                    ROOT . '/pages/',
                    '',
                    $path
                );
                array_push(
                    static::$pages, 
                    explode('.', $page)[0]
                );
            }
        }
    }

    public static function get(){
        return static::$pages;
    }

    public static function types(){
        $ThemeController = new ThemeController();
        $ThemeController->mount(
            AppSettings::getThemeName()
        );
        $ThemeController->getdir();
        $pages = array_map(function($page){
            return "'" . $page . "'";
        }, static::$pages);
        foreach (static::$typesloc as $location => $name) {
            $path = $ThemeController->getdir() . $location;
            $content = file_get_contents($path);
            $processed = '';
            foreach (explode("\n", $content) as $line) {
                if (!str_contains($line, $name)) {
                    $processed .= trim($line).PHP_EOL;
                    continue;
                }
                $processed .= 'export type ' . $name . ' = '.implode(' | ', $pages);
            }
            file_put_contents($path, $processed);
        }
    }

}