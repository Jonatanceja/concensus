<?php

/*
 * En Netlify el dominio llega por variables de entorno:
 * `URL` es el dominio principal y `DEPLOY_PRIME_URL` la previsualización
 * de cada rama o pull request. Si no existen (build local), se usa el
 * dominio definitivo del sitio.
 */
$baseUrl = getenv('DEPLOY_PRIME_URL') ?: getenv('URL') ?: 'https://consensusfamily.com';

return [
    'production' => true,
    'baseUrl' => rtrim($baseUrl, '/'),
];
