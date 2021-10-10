<?php

require_once("config/config.php");

class View
// Reference: https://stackoverflow.com/a/32543211
{
    /**
     * @var string Path to template file.
     */
    public function render(string $template, array $data)
    {
        // add full path to template, so it
        // is easier to load
        $template = sprintf("%s/%s", VIEWS_ROOT, $template);
        
        if (!is_file($template)) {
            throw new RuntimeException('Template not found: ' . $template);
        }

        // define a closure with a scope for the variable extraction
        $result = function($file, array $data = array()) {
            ob_start();
            extract($data, EXTR_SKIP);
            try {
                include $file;
            } catch (Exception $e) {
                ob_end_clean();
                throw $e;
            }
            return ob_get_clean();
        };

        // call the closure
        echo $result($template, $data);
    }
}
