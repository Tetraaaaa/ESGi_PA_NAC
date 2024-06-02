<?php
function loadLanguage($lang) {
    $file = __DIR__ . "/lang/$lang.json";
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true);
    }
    $json = file_get_contents(__DIR__ . "/lang/en.json"); 
    return json_decode($json, true);
}
?>
