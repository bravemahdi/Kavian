<?php
$preferred_lang = isset($_COOKIE['preferred_language']) ? $_COOKIE['preferred_language'] : null;

if ($preferred_lang === 'en') {
    header('Location: english/index.php');
    exit;
}

header('Location: persian/index.php');
exit;
?>