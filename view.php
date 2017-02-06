<?php

class View {
    public function render($template, $params = []) {
        ob_start();
        extract($params);
        include $template;
        $out = ob_get_clean();
        return $out;
    }
}
?>