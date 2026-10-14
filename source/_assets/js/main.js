import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

Alpine.plugin(collapse);
Alpine.plugin(intersect);

/* ---------------------------------------------------------------------------
 * Encabezado fijo: cambia de estilo al hacer scroll y controla el menú móvil.
 * ------------------------------------------------------------------------ */
Alpine.data('siteHeader', () => ({
    scrolled: false,
    open: false,

    init() {
        this.onScroll();
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
    },

    onScroll() {
        this.scrolled = window.scrollY > 24;
    },

    toggle() {
        this.open = !this.open;
        document.body.style.overflow = this.open ? 'hidden' : '';
    },

    close() {
        this.open = false;
        document.body.style.overflow = '';
    },
}));

/* ---------------------------------------------------------------------------
 * Slider del hero: fundido cruzado, autoplay con barra de progreso,
 * navegación por teclado y gestos táctiles.
 * ------------------------------------------------------------------------ */
Alpine.data('heroSlider', (config = {}) => ({
    count: config.count || 1,
    autoplay: config.autoplay !== false,
    interval: config.interval || 7000,
    active: 0,
    paused: false,   // pausa temporal: puntero encima o foco dentro
    stopped: false,  // pausa explícita del usuario con el botón
    timer: null,
    touchStart: null,

    init() {
        // Respeta la preferencia del sistema de reducir movimiento (WCAG 2.3.3)
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.autoplay = false;
            this.stopped = true;
        }

        this.play();

        this.$watch('paused', () => this.sync());
        this.$watch('stopped', () => this.sync());
    },

    sync() {
        this.paused || this.stopped ? this.stop() : this.play();
    },

    play() {
        if (!this.autoplay || this.stopped || this.paused || this.count < 2) return;
        this.stop();
        this.timer = setInterval(() => this.next(), this.interval);
    },

    stop() {
        if (this.timer) clearInterval(this.timer);
        this.timer = null;
    },

    // Botón de pausa/reproducción: control manual exigido por WCAG 2.2.2
    toggleAutoplay() {
        this.stopped = !this.stopped;
    },

    go(index) {
        this.active = (index + this.count) % this.count;
        this.play();
    },

    next() {
        this.go(this.active + 1);
    },

    prev() {
        this.go(this.active - 1);
    },

    onTouchStart(event) {
        this.touchStart = event.changedTouches[0].clientX;
    },

    onTouchEnd(event) {
        if (this.touchStart === null) return;
        const delta = event.changedTouches[0].clientX - this.touchStart;
        if (Math.abs(delta) > 50) delta < 0 ? this.next() : this.prev();
        this.touchStart = null;
    },
}));

/* ---------------------------------------------------------------------------
 * Slider de tarjetas (testimonios): 2 por vista en escritorio, 1 en móvil.
 * ------------------------------------------------------------------------ */
Alpine.data('cardsSlider', (config = {}) => ({
    count: config.count || 0,
    perView: 2,
    page: 0,
    autoplay: config.autoplay !== false,
    interval: config.interval || 8000,
    paused: false,
    stopped: false,
    timer: null,
    touchStart: null,

    init() {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            this.autoplay = false;
            this.stopped = true;
        }

        this.measure();
        window.addEventListener('resize', () => this.measure(), { passive: true });

        this.play();

        this.$watch('paused', () => this.sync());
        this.$watch('stopped', () => this.sync());
    },

    measure() {
        const perView = window.matchMedia('(min-width: 768px)').matches ? 2 : 1;

        if (perView !== this.perView) {
            this.perView = perView;
            this.page = Math.min(this.page, this.pages - 1);
        }
    },

    get pages() {
        return Math.max(1, Math.ceil(this.count / this.perView));
    },

    get offset() {
        return -(this.page * 100);
    },

    sync() {
        this.paused || this.stopped ? this.stop() : this.play();
    },

    play() {
        if (!this.autoplay || this.stopped || this.paused || this.pages < 2) return;
        this.stop();
        this.timer = setInterval(() => this.next(), this.interval);
    },

    stop() {
        if (this.timer) clearInterval(this.timer);
        this.timer = null;
    },

    toggleAutoplay() {
        this.stopped = !this.stopped;
    },

    go(page) {
        this.page = (page + this.pages) % this.pages;
        this.play();
    },

    next() {
        this.go(this.page + 1);
    },

    prev() {
        this.go(this.page - 1);
    },

    onTouchStart(event) {
        this.touchStart = event.changedTouches[0].clientX;
    },

    onTouchEnd(event) {
        if (this.touchStart === null) return;
        const delta = event.changedTouches[0].clientX - this.touchStart;
        if (Math.abs(delta) > 50) delta < 0 ? this.next() : this.prev();
        this.touchStart = null;
    },
}));

/* ---------------------------------------------------------------------------
 * Acordeón de preguntas frecuentes (una abierta a la vez).
 * ------------------------------------------------------------------------ */
Alpine.data('faqAccordion', (opened = null) => ({
    active: opened,

    toggle(index) {
        this.active = this.active === index ? null : index;
    },

    isOpen(index) {
        return this.active === index;
    },
}));

/* ---------------------------------------------------------------------------
 * Formulario de contacto: validación ligera y estado de envío.
 * Si `action` no apunta a un endpoint real, se muestra el mensaje de éxito
 * sin recargar la página.
 * ------------------------------------------------------------------------ */
Alpine.data('contactForm', () => ({
    sending: false,
    sent: false,
    error: '',

    submit(event) {
        const form = event.target;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const action = form.getAttribute('action');

        if (!action || action === '#' || action === '') {
            event.preventDefault();
            this.sending = true;
            this.error = '';

            setTimeout(() => {
                this.sending = false;
                this.sent = true;
                form.reset();
            }, 700);
        }
    },
}));

/* ---------------------------------------------------------------------------
 * Termómetro (quiz en popup): una pregunta por pantalla, progreso y captura de
 * datos al final. Se envía igual que el formulario de contacto: si la acción es
 * "#" se simula; con Netlify se manda por fetch para no recargar la página.
 * ------------------------------------------------------------------------ */
Alpine.data('termometro', (config = {}) => ({
    questions: config.questions || [],
    results: config.results || [],
    action: config.action || '#',
    netlify: !!config.netlify,
    formName: config.formName || 'termometro',
    isOpen: false,
    step: 0,
    answers: [],
    sending: false,
    sent: false,
    error: '',
    lastFocus: null,

    init() {
        this.answers = this.questions.map(() => null);
    },

    get total() {
        return this.questions.length;
    },

    get score() {
        return this.answers.filter((a) => a === true).length;
    },

    get progress() {
        // Mide el riesgo: solo sube con cada "Sí pasa"; "No pasa" avanza de pregunta sin sumar
        return this.total ? (this.score / this.total) * 100 : 0;
    },

    get result() {
        return this.results.find((r) => this.score <= r.max) || this.results[this.results.length - 1] || { title: '', text: '' };
    },

    get summary() {
        return this.questions.map((q, i) => `${i + 1}. ${q} → ${this.answers[i] ? 'Sí' : 'No'}`).join('\n');
    },

    open() {
        this.lastFocus = document.activeElement;
        if (this.sent) this.reset();
        this.isOpen = true;
        document.body.style.overflow = 'hidden';
        this.$nextTick(() => this.$refs.panel && this.$refs.panel.focus());
    },

    close() {
        this.isOpen = false;
        document.body.style.overflow = '';
        if (this.lastFocus && this.lastFocus.focus) this.lastFocus.focus();
    },

    reset() {
        this.step = 0;
        this.answers = this.questions.map(() => null);
        this.sent = false;
        this.error = '';
    },

    answer(value) {
        this.answers[this.step] = value;
        // Pequeña pausa para que se vea la selección antes de avanzar
        setTimeout(() => {
            this.step++;
            if (this.$refs.panel) this.$refs.panel.focus();
        }, 220);
    },

    back() {
        if (this.step > 0) this.step--;
    },

    async submit(event) {
        const form = event.target;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        this.sending = true;
        this.error = '';

        try {
            if (this.action !== '#' && this.action !== '') {
                const body = new URLSearchParams(new FormData(form)).toString();
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body,
                });
                if (!response.ok) throw new Error('send failed');
            } else {
                await new Promise((resolve) => setTimeout(resolve, 700));
            }

            this.sent = true;
            form.reset();
        } catch (e) {
            this.error = 'No pudimos enviar tus datos. Inténtalo de nuevo en un momento.';
        } finally {
            this.sending = false;
        }
    },
}));

window.Alpine = Alpine;
Alpine.start();
