<?php

$config = json_decode(file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'local.json'), true);

return [
    'api_key' => $config['raygun']['api_key'] ?? ''
];
