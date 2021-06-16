<?php

namespace Core;

class View {
    private $name, $data, $layout;

    public function __construct($name, $data=[], $layout="base")
    {
        $this->name = $name;
        $this->data = $data;
        $this->layout = $layout;
    }

    public function render()
    {
        $view_path = "views/pages/{$this->name}.html";
        $layout_path = "views/{$this->layout}.html";
        if(!file_exists($view_path) || !file_exists($layout_path)) return;
        extract($this->data);
        $hash = md5($view_path);
        $cache_path = "cache/$hash";
        if(file_exists($cache_path) && filemtime($cache_path) > filemtime($view_path) && filemtime($cache_path) > filemtime($layout_path))
        {
            ob_start();
            require($cache_path);
            $result = ob_get_clean();
            if(defined('APP_NOCACHE') && file_exists($cache_path)) { unlink($cache_path); }
            return $result;
        } else {
            $data = file_get_contents($layout_path);
            $data = str_replace("{{ -BODY- }}", file_get_contents($view_path), $data);
            $data = preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $$1; ?>', $data);

            file_put_contents($cache_path, $data);
            return $this->render();
        }
    }
}