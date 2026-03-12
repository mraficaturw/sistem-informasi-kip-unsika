<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Form Pendataan Active
    |--------------------------------------------------------------------------
    |
    | Toggle the KHS submission form on the homepage.
    | Set to true to allow students to submit KHS, false to disable.
    | This can be overridden via the .env file: KIP_FORM_PENDATAAN_ACTIVE=true
    |
    */
    'form_pendataan_active' => env('KIP_FORM_PENDATAAN_ACTIVE', true),
];
