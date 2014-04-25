<?php
require_once("_config.php");
// ---------------------------------------------------------------------------

$filepath = $_POST["filepath"];
$r = false;

if ( preg_match("/data\/$g4[cheditor4]\/[0-9]{4}\/[0-9a-z_]+\.(gif|png|jpe?g)$/i", $filepath) ) {
    if (file_exists($filepath)) {
        $r = unlink($filepath);
        if ($r) {
            $thumbPath = dirname($filepath) . DIRECTORY_SEPARATOR . "thumb_" . basename($filepath);
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }
    }
}

echo $r ? true : false;
?>