# Consensus Family — Landing

Sitio estático construido con **Jigsaw** (Blade), **Tailwind CSS 4**, **Alpine.js** y **Vite**.

## Comandos

```bash
npm run dev      # Servidor de desarrollo con recarga en caliente (build_local)
npm run build    # Compila assets y genera el sitio en build_production
```

## Cómo editar el contenido

Todo el texto de la landing vive en `source/_content/*.md`. **No hace falta tocar el HTML.**

| Archivo | Sección |
|---|---|
| `site.md` | Marca, menú de navegación, botón principal y pie de página |
| `01-hero.md` | Hero con slider (añada o quite slides libremente) |
| `02-introduccion.md` | Introducción: título, texto, recuadros con icono y CTA sutil |
| `03-biografia.md` | Biografía (el texto largo se escribe en Markdown bajo el front matter) |
| `04-faq.md` | Preguntas frecuentes (acordeón) |
| `05-servicios.md` | Servicios: título, texto y recuadros |
| `06-testimonios.md` | Testimonios en slider (2 por vista en escritorio, 1 en móvil) |
| `07-cta.md` | Llamado a la acción de ancho completo |
| `08-contacto.md` | Datos de contacto y campos del formulario |

Cada archivo tiene dos partes:

```markdown
---
title: Datos estructurados en YAML   # tarjetas, slides, preguntas, campos…
---

Texto largo opcional en **Markdown** (se convierte a HTML y se expone como `body`).
```

El archivo `bootstrap.php` lee esa carpeta durante el build y deja los datos
disponibles en las vistas como `$page->content['hero']`, `$page->content['faq']`, etc.
El nombre de la clave es el del archivo sin el prefijo numérico.

> Los valores YAML que contengan `: ` (dos puntos + espacio) deben ir entre comillas.

## Estructura de las vistas

```
source/
├── _content/                 # ← contenido editable (Markdown + YAML)
├── _layouts/main.blade.php   # documento base, fuentes y assets
├── _partials/
│   ├── header.blade.php
│   ├── footer.blade.php
│   ├── logo.blade.php
│   ├── icon.blade.php        # set de iconos SVG en línea
│   └── sections/             # ← un partial por sección
│       ├── hero.blade.php
│       ├── introduccion.blade.php
│       ├── biografia.blade.php
│       ├── faq.blade.php
│       ├── servicios.blade.php
│       ├── testimonios.blade.php
│       ├── cta.blade.php
│       └── contacto.blade.php
├── _assets/css/main.css      # tokens de diseño y componentes (Tailwind 4)
├── _assets/js/main.js        # componentes de Alpine (sliders, acordeón, formulario)
├── assets/images/            # imágenes del sitio
└── index.blade.php           # orden de las secciones
```

Para reordenar, duplicar o quitar secciones, edite `source/index.blade.php`.

## Imágenes

El logotipo oficial está en `source/assets/images/logo-concensus.svg` (a color, para
fondos claros) y `logo-concensus-white.svg` (variante blanca para el hero y el pie de
página, generada del original cambiando los rellenos `#424450` y `#888787`). Ambas rutas
se declaran en `site.md` (`brand.logo` y `brand.logo_light`).

`source/assets/images/` contiene además **imágenes de ejemplo** en SVG (`hero-1..3`, `kathya`).
Sustitúyalas por fotografías reales (JPG/WebP) y actualice la ruta en el `.md`
correspondiente. Tamaños recomendados: hero 1920×1080, retrato 800×1000.

## Iconos

Los recuadros usan los nombres definidos en `source/_partials/icon.blade.php`:
`building`, `gears`, `trending`, `shield`, `succession`, `board`, `scale`,
`growth`, `legacy`, `phone`, `mail`, `pin`, `clock`. Para añadir uno nuevo,
agregue un `@case` con el SVG.

## Favicon y Open Graph

Los iconos se generaron a partir de `source/assets/images/favicon.jpg` (el isotipo):

- `source/favicon.ico` — multitamaño 16/32/48, se sirve en la raíz
- `source/assets/images/favicon-32.png`, `favicon-192.png`, `favicon-512.png`
- `source/assets/images/apple-touch-icon.png` (180×180)

Si cambia el isotipo, vuelva a generarlos recortando el original a un cuadrado y
exportando esos tamaños; los `<link>` viven en `source/_layouts/main.blade.php`.

La tarjeta para compartir en redes se configura en el bloque `seo` de
`source/_content/site.md` (imagen, texto alternativo, idioma y tipo de tarjeta).
La imagen `open-graph.jpg` mide 1200×630, el tamaño que piden WhatsApp, LinkedIn,
Facebook y X.

> Open Graph exige URLs absolutas: se arman con el `baseUrl` de
> `config.production.php`. **Ajústelo al dominio real antes de publicar**, o las
> previsualizaciones apuntarán a `https://consensusfamily.com`.

## Formulario de contacto

Los campos se definen en `08-contacto.md`. Mientras `form.action` sea `#`,
el envío se simula en el navegador y muestra el mensaje de confirmación.
Apunte `action` a su endpoint (Formspree, un script PHP, etc.) para recibir
los mensajes; en ese caso el formulario se envía de forma nativa.

## Despliegue en Netlify

El repositorio ya trae `netlify.toml`, así que basta con conectar el repo en
Netlify: detecta la configuración y no hay que tocar nada en el panel.

```toml
command = "composer install --no-dev ... && npm run build"
publish = "build_production"
```

- El build corre en Netlify: Composer instala Jigsaw (PHP 8.3) y `npm run build`
  compila los assets con Vite y genera el sitio de producción.
- `config.production.php` toma el dominio de las variables de entorno de Netlify
  (`URL`, y `DEPLOY_PRIME_URL` en las previsualizaciones de ramas y pull
  requests), de modo que el canonical y las etiquetas Open Graph siempre apuntan
  al dominio correcto. Al conectar el dominio propio no hay que cambiar nada;
  para un build local sin esas variables se usa `https://consensusfamily.com`.
- Los assets con hash se cachean un año; las imágenes, una semana.
- Cualquier ruta desconocida muestra la landing con código 404.

> Si el build fallara por la versión de PHP, ajuste `PHP_VERSION` en
> `netlify.toml`. Alternativa sin build en CI: ejecute `npm run build` en local,
> quite `/build_production/` del `.gitignore` y publique esa carpeta.

### Formulario con Netlify Forms

Por defecto el formulario es solo maqueta (simula el envío en el navegador).
Para recibir los mensajes en Netlify, en `source/_content/08-contacto.md`:

```yaml
form:
  action: "/"
  netlify: true
  netlify_name: contacto
```

Los mensajes llegan a **Forms** en el panel de Netlify y pueden notificarse por
correo. El plan gratuito incluye 100 envíos al mes. Si prefiere otro servicio
(Formspree, un endpoint propio), deje `netlify: false` y apunte `action` a él.

## Producción (otros hosts)

`config.production.php` define `baseUrl`. Ajústelo al dominio real antes de
publicar y suba el contenido de `build_production/`.
