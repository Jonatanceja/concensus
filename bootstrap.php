<?php

use Illuminate\Container\Container;
use League\CommonMark\CommonMarkConverter;
use Symfony\Component\Yaml\Yaml;
use TightenCo\Jigsaw\Events\EventBus;
use TightenCo\Jigsaw\Jigsaw;

/** @var Container $container */
/** @var EventBus $events */

/*
 * Todo el contenido editable de la landing vive en archivos Markdown dentro de
 * `source/_content`. Cada archivo representa una sección: el front matter YAML
 * guarda los datos estructurados (tarjetas, slides, preguntas…) y el cuerpo
 * Markdown se convierte a HTML y queda disponible como `body`.
 *
 * Los datos quedan accesibles en las vistas como `$page->content['hero']`, etc.
 */
$events->beforeBuild(function (Jigsaw $jigsaw) {
    $directory = $jigsaw->getSourcePath() . '/_content';

    if (! is_dir($directory)) {
        return;
    }

    $markdown = new CommonMarkConverter([
        'html_input' => 'allow',
        'allow_unsafe_links' => false,
    ]);

    $sections = [];

    foreach (glob($directory . '/*.md') as $file) {
        $raw = file_get_contents($file);
        $data = [];
        $body = $raw;

        if (preg_match('/\A---\s*\R(.*?)\R---\s*\R?(.*)\z/s', $raw, $matches)) {
            $data = Yaml::parse($matches[1]) ?: [];
            $body = $matches[2];
        }

        // `01-hero.md` => `hero`
        $key = preg_replace('/^\d+[-_]/', '', pathinfo($file, PATHINFO_FILENAME));

        $data['body'] = trim($body) === '' ? '' : $markdown->convert($body)->getContent();

        $sections[$key] = $data;
    }

    $jigsaw->setConfig('content', $sections);
});

/*
 * Sitemap. Se genera después del build para poder usar el baseUrl del entorno
 * (en Netlify llega por variable de entorno). Es un sitio de una sola página,
 * así que basta con la raíz.
 */
$events->afterBuild(function (Jigsaw $jigsaw) {
    $baseUrl = rtrim($jigsaw->getConfig('baseUrl') ?: '', '/');

    $sitemap = implode("\n", [
        '<?xml version="1.0" encoding="UTF-8"?>',
        '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        '    <url>',
        '        <loc>' . $baseUrl . '/</loc>',
        '        <lastmod>' . date('Y-m-d') . '</lastmod>',
        '        <changefreq>monthly</changefreq>',
        '        <priority>1.0</priority>',
        '    </url>',
        '</urlset>',
        '',
    ]);

    $jigsaw->writeOutputFile('sitemap.xml', $sitemap);
});
