@php
    /*
     * Datos estructurados (JSON-LD) construidos a partir del contenido de
     * source/_content: así no hay que mantenerlos por separado.
     */
    $site = $page->content['site'];
    $bio = $page->content['biografia'];
    $faq = $page->content['faq'];
    $servicios = $page->content['servicios'];
    $contacto = $page->content['contacto'];
    $seo = $site['seo'] ?? [];

    $base = rtrim($page->baseUrl, '/');

    // Los datos de contacto se identifican por su icono
    $detalles = [];
    foreach ($contacto['details'] as $detail) {
        $detalles[$detail['icon']] = $detail['value'];
    }

    $redes = [];
    foreach ($site['footer']['social'] as $social) {
        if (($social['href'] ?? '#') !== '#') {
            $redes[] = $social['href'];
        }
    }

    $negocio = array_filter([
        '@type' => 'ProfessionalService',
        '@id' => $base . '/#organizacion',
        'name' => $site['brand']['name'],
        'description' => $page->description,
        'url' => $base . '/',
        'logo' => $base . $site['brand']['logo'],
        'image' => $base . ($seo['og_image'] ?? ''),
        'telephone' => $detalles['phone'] ?? null,
        'email' => $detalles['mail'] ?? null,
        'areaServed' => $detalles['pin'] ?? null,
        'address' => isset($detalles['pin']) ? [
            '@type' => 'PostalAddress',
            'addressLocality' => $detalles['pin'],
        ] : null,
        'sameAs' => $redes ?: null,
        'founder' => [
            '@type' => 'Person',
            '@id' => $base . '/#fundadora',
        ],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => $servicios['title'],
            'itemListElement' => collect($servicios['items'])->map(fn ($item) => [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => $item['title'],
                    'description' => $item['text'],
                ],
            ])->all(),
        ],
    ]);

    $persona = array_filter([
        '@type' => 'Person',
        '@id' => $base . '/#fundadora',
        'name' => $bio['name'],
        'jobTitle' => $bio['role'],
        'image' => $base . $bio['image'],
        'description' => trim(strip_tags($bio['body'])),
        'worksFor' => ['@id' => $base . '/#organizacion'],
        'url' => $base . '/#biografia',
    ]);

    $preguntas = [
        '@type' => 'FAQPage',
        '@id' => $base . '/#preguntas',
        'mainEntity' => collect($faq['items'])->map(fn ($item) => [
            '@type' => 'Question',
            'name' => $item['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['answer'],
            ],
        ])->all(),
    ];

    $sitio = [
        '@type' => 'WebSite',
        '@id' => $base . '/#sitio',
        'url' => $base . '/',
        'name' => $site['brand']['name'],
        'inLanguage' => 'es-MX',
        'publisher' => ['@id' => $base . '/#organizacion'],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@graph' => [$negocio, $persona, $preguntas, $sitio],
    ];
@endphp

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
