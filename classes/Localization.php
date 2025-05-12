<?php
class Localization {
    private $language;
    private $translations = [];

    public function __construct($lang = 'en') {
        $this->setLanguage($lang);
    }

    public function setLanguage($lang) {
        $this->language = $lang;
        $languageFile = __DIR__ . '/../languages/' . $lang . '.php';
        if (file_exists($languageFile)) {
            $this->translations = include $languageFile;
        } else {
            // Fallback to English if the file doesn't exist.
            $this->translations = include __DIR__ . '/../languages/en.php';
        }
    }

    public function translate($key) {
        return isset($this->translations[$key]) ? $this->translations[$key] : $key;
    }
}
?>
