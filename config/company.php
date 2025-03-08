<?php

return [
    'name' => env('COMPANY_NAME', 'Marmosys'),
    'cnpj' => env('COMPANY_CNPJ', '00.000.000/0001-00'),
    'address' => env('COMPANY_ADDRESS', 'Endereço da Empresa'),
    'phone' => env('COMPANY_PHONE', '(00) 0000-0000'),
    'email' => env('COMPANY_EMAIL', 'contato@empresa.com'),
    'logo' => env('COMPANY_LOGO', 'images/logo.png'),
    'use_database' => true, // Flag para indicar que usaremos dados do banco
]; 