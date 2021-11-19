<?php

namespace Framework\View;

use \Framework\Application;
use \MatthiasMullie\Minify;

class View {
    public static function render($view, $data=[])
    {
        $app = Application::$instance;
        $app_config = $app->get('app');
        $loader = new \Twig\Loader\FilesystemLoader($app_config['views_dir']);
        $twig = new \Twig\Environment($loader, [
            'cache' => $app_config['cache'] ? $app_config['cache_dir'] : false,
        ]);
        $twig->addFunction(new \Twig\TwigFunction('asset', function($asset) {
            $extension = explode('.', basename($asset));
            $asset_path = __ROOT__.'/assets/'.$asset;
            if(!isset($extension[1]) || !file_exists($asset_path)) return 'NULL';
            $bundled_name = substr(md5($asset), 0, 16).'.'.$extension[1];
            $bundled_path = __ROOT__.'/bundled/'.$bundled_name;
            if(!file_exists($bundled_path) || filemtime($bundled_path) < filemtime($asset_path))
            {
                switch($extension[1]) {
                    case 'css':
                        $minifier = new Minify\CSS($asset_path);
                        $minifier->minify($bundled_path);
                        break;
                    case 'js':
                        $minifier = new Minify\JS($asset_path);
                        $minifier->minify($bundled_path);
                        break;
                    default:
                        copy($asset_path, $bundled_path);
                }
            }
            return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/bundled/'.$bundled_name;
        }));
        return $twig->render($view, $data);
    }
}