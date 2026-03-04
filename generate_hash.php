<?php
// generate_hash.php
echo "PHP versiyasi: " . phpversion() . "\n\n";
echo "123456 paroli uchun hash:\n";
echo password_hash('123456', PASSWORD_DEFAULT) . "\n";