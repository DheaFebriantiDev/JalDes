<?php

return [
    'paths' => ['*'],
    'allowed_methods' => ['*'],
    
    // GANTI BARIS INI JADI BINTANG SEMUA
    'allowed_origins' => ['*'], 
    
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    
    // GANTI INI JADI FALSE (Karena pakai bintang *)
    'supports_credentials' => false, 
];