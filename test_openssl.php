<?php
echo "OpenSSL Support: " . (extension_loaded('openssl') ? "Enabled" : "Disabled") . "<br>";
echo "OpenSSL CA Path: " . ini_get('openssl.cafile') . "<br>";
?>
