<?php
function _t($key) {
    global $localization;
    if (!$localization) {
        $localization = new Localization('en');
    }
    return $localization->translate($key);
}
?>
