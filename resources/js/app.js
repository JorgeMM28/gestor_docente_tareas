/*
|--------------------------------------------------------------------------
| Sistema Docente
|--------------------------------------------------------------------------
| resources/js/app.js
|
| JavaScript general del sistema.
| No requiere librerías externas.
|
*/

document.addEventListener('DOMContentLoaded', () => {
    ThemeManager.init();
    SidebarManager.init();
    PasswordManager.init();
    ModalManager.init();
    DropdownManager.init();
    FileUploadManager.init();
    AlertManager.init();
    FormManager.init();
    FilterManager.init();
});


/*
|--------------------------------------------------------------------------
| 1. TEMA CLARO / OSCURO
|--------------------------------------------------------------------------
*/

const ThemeManager = {
    storageKey: 'sistema-docente-theme',

    init() {
        this.applyInitialTheme();

        document
            .querySelectorAll('[data-theme-toggle]')
            .forEach((button) => {
                button.addEventListener('click', () => {
                    this.toggle();
                });
            });

        this.updateButtons();
    },

    applyInitialTheme() {
        const savedTheme = localStorage.getItem(this.storageKey);

        if (savedTheme === 'light' || savedTheme === 'dark') {
            this.setTheme(savedTheme, false);
            return;
        }

        const prefersDark = window.matchMedia(
            '(prefers-color-scheme: dark)'
        ).matches;

        this.setTheme(
            prefersDark ? 'dark' : 'light',
            false
        );
    },

    toggle() {
        const currentTheme =
            document.documentElement.dataset.theme || 'light';

        const nextTheme =
            currentTheme === 'dark' ? 'light' : 'dark';

        this.setTheme(nextTheme);
    },

    setTheme(theme, persist = true) {
        document.documentElement.dataset.theme = theme;

        if (persist) {
            localStorage.setItem(
                this.storageKey,
                theme
            );
        }

        this.updateButtons();
    },

    updateButtons() {
        const theme =
            document.documentElement.dataset.theme || 'light';

        document
            .querySelectorAll('[data-theme-toggle]')
            .forEach((button) => {
                button.setAttribute(
                    'aria-label',
                    theme === 'dark'
                        ? 'Cambiar a modo claro'
                        : 'Cambiar a modo oscuro'
                );

                button.setAttribute(
                    'title',
                    theme === 'dark'
                        ? 'Modo claro'
                        : 'Modo oscuro'
                );

                const lightIcon =
                    button.querySelector(
                        '[data-theme-icon="light"]'
                    );

                const darkIcon =
                    button.querySelector(
                        '[data-theme-icon="dark"]'
                    );

                if (lightIcon) {
                    lightIcon.classList.toggle(
                        'hidden',
                        theme !== 'dark'
                    );
                }

                if (darkIcon) {
                    darkIcon.classList.toggle(
                        'hidden',
                        theme === 'dark'
                    );
                }
            });
    },
};


/*
|--------------------------------------------------------------------------
| 2. SIDEBAR RESPONSIVE
|--------------------------------------------------------------------------
*/

const SidebarManager = {
    init() {
        const openButtons =
            document.querySelectorAll(
                '[data-sidebar-open]'
            );

        const closeButtons =
            document.querySelectorAll(
                '[data-sidebar-close]'
            );

        openButtons.forEach((button) => {
            button.addEventListener('click', () => {
                this.open();
            });
        });

        closeButtons.forEach((button) => {
            button.addEventListener('click', () => {
                this.close();
            });
        });

        document.addEventListener(
            'keydown',
            (event) => {
                if (event.key === 'Escape') {
                    this.close();
                }
            }
        );

        window.addEventListener(
            'resize',
            () => {
                if (window.innerWidth > 992) {
                    this.close();
                }
            }
        );
    },

    open() {
        document.body.classList.add(
            'sidebar-open'
        );
    },

    close() {
        document.body.classList.remove(
            'sidebar-open'
        );
    },
};


/*
|--------------------------------------------------------------------------
| 3. MOSTRAR / OCULTAR CONTRASEÑA
|--------------------------------------------------------------------------
*/

const PasswordManager = {
    init() {
        document
            .querySelectorAll(
                '[data-password-toggle]'
            )
            .forEach((button) => {
                button.addEventListener(
                    'click',
                    () => {
                        this.toggle(button);
                    }
                );
            });
    },

    toggle(button) {
        const targetId =
            button.dataset.passwordToggle;

        let input = null;

        if (targetId) {
            input = document.getElementById(
                targetId
            );
        }

        if (!input) {
            input = button
                .closest('.password-wrapper')
                ?.querySelector(
                    'input[type="password"], input[data-password-input]'
                );
        }

        if (!input) {
            return;
        }

        const currentlyHidden =
            input.type === 'password';

        input.type = currentlyHidden
            ? 'text'
            : 'password';

        input.dataset.passwordInput = 'true';

        button.setAttribute(
            'aria-label',
            currentlyHidden
                ? 'Ocultar contraseña'
                : 'Mostrar contraseña'
        );

        button.setAttribute(
            'title',
            currentlyHidden
                ? 'Ocultar contraseña'
                : 'Mostrar contraseña'
        );

        const showIcon =
            button.querySelector(
                '[data-password-icon="show"]'
            );

        const hideIcon =
            button.querySelector(
                '[data-password-icon="hide"]'
            );

        if (showIcon) {
            showIcon.classList.toggle(
                'hidden',
                currentlyHidden
            );
        }

        if (hideIcon) {
            hideIcon.classList.toggle(
                'hidden',
                !currentlyHidden
            );
        }

        input.focus();
    },
};


/*
|--------------------------------------------------------------------------
| 4. MODALES
|--------------------------------------------------------------------------
|
| Abrir:
|
| <button data-modal-open="cancel-task-modal">
|
| Modal:
|
| <div
|     id="cancel-task-modal"
|     class="modal"
|     data-modal
| >
|
| Cerrar:
|
| <button data-modal-close>
|
*/

const ModalManager = {
    activeModal: null,

    init() {
        document
            .querySelectorAll(
                '[data-modal-open]'
            )
            .forEach((button) => {
                button.addEventListener(
                    'click',
                    () => {
                        const modalId =
                            button.dataset.modalOpen;

                        this.open(modalId, button);
                    }
                );
            });

        document
            .querySelectorAll(
                '[data-modal-close]'
            )
            .forEach((button) => {
                button.addEventListener(
                    'click',
                    () => {
                        this.close(
                            button.closest(
                                '[data-modal]'
                            )
                        );
                    }
                );
            });

        document
            .querySelectorAll('[data-modal]')
            .forEach((modal) => {
                modal.addEventListener(
                    'mousedown',
                    (event) => {
                        if (
                            event.target === modal
                        ) {
                            this.close(modal);
                        }
                    }
                );
            });

        document.addEventListener(
            'keydown',
            (event) => {
                if (
                    event.key === 'Escape'
                    && this.activeModal
                ) {
                    this.close(
                        this.activeModal
                    );
                }
            }
        );

        this.initDynamicConfirmations();
    },

    open(modalId, trigger = null) {
        const modal =
            document.getElementById(modalId);

        if (!modal) {
            return;
        }

        this.closeAll();

        /*
         * Permite enviar información desde el botón
         * que abrió el modal.
         *
         * Ejemplo:
         *
         * data-modal-form-action="/tareas/5/cancelar"
         */
        if (trigger) {
            this.configureFromTrigger(
                modal,
                trigger
            );
        }

        modal.classList.add('is-open');

        modal.setAttribute(
            'aria-hidden',
            'false'
        );

        document.body.style.overflow =
            'hidden';

        this.activeModal = modal;

        const focusable =
            modal.querySelector(
                'button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), a[href]'
            );

        if (focusable) {
            setTimeout(() => {
                focusable.focus();
            }, 50);
        }
    },

    close(modal) {
        if (!modal) {
            return;
        }

        modal.classList.remove('is-open');

        modal.setAttribute(
            'aria-hidden',
            'true'
        );

        document.body.style.overflow = '';

        if (this.activeModal === modal) {
            this.activeModal = null;
        }
    },

    closeAll() {
        document
            .querySelectorAll(
                '[data-modal].is-open'
            )
            .forEach((modal) => {
                this.close(modal);
            });
    },

    configureFromTrigger(
        modal,
        trigger
    ) {
        const formAction =
            trigger.dataset.modalFormAction;

        const title =
            trigger.dataset.modalTitle;

        const message =
            trigger.dataset.modalMessage;

        const confirmText =
            trigger.dataset.modalConfirmText;

        const form =
            modal.querySelector(
                '[data-modal-form]'
            );

        const titleElement =
            modal.querySelector(
                '[data-modal-title]'
            );

        const messageElement =
            modal.querySelector(
                '[data-modal-message]'
            );

        const confirmButton =
            modal.querySelector(
                '[data-modal-confirm]'
            );

        if (form && formAction) {
            form.action = formAction;
        }

        if (titleElement && title) {
            titleElement.textContent =
                title;
        }

        if (
            messageElement
            && message
        ) {
            messageElement.textContent =
                message;
        }

        if (
            confirmButton
            && confirmText
        ) {
            confirmButton.textContent =
                confirmText;
        }
    },

    initDynamicConfirmations() {
        document
            .querySelectorAll(
                '[data-confirm]'
            )
            .forEach((element) => {
                element.addEventListener(
                    'click',
                    (event) => {
                        const message =
                            element.dataset.confirm;

                        if (!message) {
                            return;
                        }

                        if (
                            !window.confirm(message)
                        ) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                    }
                );
            });
    },
};


/*
|--------------------------------------------------------------------------
| 5. DROPDOWNS
|--------------------------------------------------------------------------
*/

const DropdownManager = {
    init() {
        document
            .querySelectorAll(
                '[data-dropdown-toggle]'
            )
            .forEach((button) => {
                button.addEventListener(
                    'click',
                    (event) => {
                        event.stopPropagation();

                        const dropdown =
                            button.closest(
                                '[data-dropdown]'
                            );

                        if (!dropdown) {
                            return;
                        }

                        const isOpen =
                            dropdown.classList.contains(
                                'is-open'
                            );

                        this.closeAll();

                        if (!isOpen) {
                            dropdown.classList.add(
                                'is-open'
                            );
                        }
                    }
                );
            });

        document.addEventListener(
            'click',
            () => {
                this.closeAll();
            }
        );

        document.addEventListener(
            'keydown',
            (event) => {
                if (event.key === 'Escape') {
                    this.closeAll();
                }
            }
        );
    },

    closeAll() {
        document
            .querySelectorAll(
                '[data-dropdown].is-open'
            )
            .forEach((dropdown) => {
                dropdown.classList.remove(
                    'is-open'
                );
            });
    },
};


/*
|--------------------------------------------------------------------------
| 6. ARCHIVOS
|--------------------------------------------------------------------------
|
| Funciona tanto para PDF como para CSV.
|
| Ejemplo:
|
| <div data-file-upload>
|     <input
|         type="file"
|         data-file-input
|         data-file-type="pdf"
|         data-max-size="2097152"
|     >
|
|     <span data-file-name></span>
|     <span data-file-size></span>
| </div>
|
*/

const FileUploadManager = {
    MAX_PDF_SIZE: 2 * 1024 * 1024,

    init() {
        document
            .querySelectorAll(
                '[data-file-upload]'
            )
            .forEach((container) => {
                this.initUpload(container);
            });
    },

    initUpload(container) {
        const input =
            container.querySelector(
                '[data-file-input]'
            );

        if (!input) {
            return;
        }

        input.addEventListener(
            'change',
            () => {
                this.handleFile(
                    container,
                    input
                );
            }
        );

        container.addEventListener(
            'dragenter',
            () => {
                container.classList.add(
                    'dragover'
                );
            }
        );

        container.addEventListener(
            'dragover',
            (event) => {
                event.preventDefault();

                container.classList.add(
                    'dragover'
                );
            }
        );

        container.addEventListener(
            'dragleave',
            (event) => {
                if (
                    !container.contains(
                        event.relatedTarget
                    )
                ) {
                    container.classList.remove(
                        'dragover'
                    );
                }
            }
        );

        container.addEventListener(
            'drop',
            () => {
                container.classList.remove(
                    'dragover'
                );
            }
        );
    },

    handleFile(container, input) {
        this.clearError(container);

        const file =
            input.files?.[0];

        if (!file) {
            this.clearPreview(container);
            return;
        }

        const fileType =
            input.dataset.fileType
            || this.detectExpectedType(input);

        const maxSize =
            Number(
                input.dataset.maxSize
            )
            || (
                fileType === 'pdf'
                    ? this.MAX_PDF_SIZE
                    : 2 * 1024 * 1024
            );

        if (
            fileType === 'pdf'
            && !this.isValidPdf(file)
        ) {
            this.rejectFile(
                container,
                input,
                'Solo se permiten archivos PDF.'
            );

            return;
        }

        if (
            fileType === 'csv'
            && !this.isValidCsv(file)
        ) {
            this.rejectFile(
                container,
                input,
                'Solo se permiten archivos CSV.'
            );

            return;
        }

        if (file.size > maxSize) {
            this.rejectFile(
                container,
                input,
                `El archivo supera el tamaño máximo de ${this.formatBytes(maxSize)}.`
            );

            return;
        }

        this.showPreview(
            container,
            file
        );
    },

    detectExpectedType(input) {
        const accept =
            (
                input.getAttribute(
                    'accept'
                )
                || ''
            ).toLowerCase();

        if (accept.includes('pdf')) {
            return 'pdf';
        }

        if (
            accept.includes('csv')
            || accept.includes('.csv')
        ) {
            return 'csv';
        }

        return 'file';
    },

    isValidPdf(file) {
        const extension =
            file.name
                .split('.')
                .pop()
                ?.toLowerCase();

        const mimeValid =
            file.type === 'application/pdf'
            || file.type === '';

        return (
            extension === 'pdf'
            && mimeValid
        );
    },

    isValidCsv(file) {
        const extension =
            file.name
                .split('.')
                .pop()
                ?.toLowerCase();

        const allowedMimeTypes = [
            'text/csv',
            'text/plain',
            'application/csv',
            'application/vnd.ms-excel',
            '',
        ];

        return (
            extension === 'csv'
            && allowedMimeTypes.includes(
                file.type
            )
        );
    },

    rejectFile(
        container,
        input,
        message
    ) {
        input.value = '';

        this.clearPreview(container);

        this.showError(
            container,
            message
        );
    },

    showPreview(container, file) {
        const preview =
            this.findPreview(container);

        if (!preview) {
            return;
        }

        preview.classList.remove(
            'hidden'
        );

        const nameElement =
            preview.querySelector(
                '[data-file-name]'
            );

        const sizeElement =
            preview.querySelector(
                '[data-file-size]'
            );

        if (nameElement) {
            nameElement.textContent =
                file.name;
        }

        if (sizeElement) {
            sizeElement.textContent =
                this.formatBytes(
                    file.size
                );
        }
    },

    clearPreview(container) {
        const preview =
            this.findPreview(container);

        if (!preview) {
            return;
        }

        preview.classList.add('hidden');

        const nameElement =
            preview.querySelector(
                '[data-file-name]'
            );

        const sizeElement =
            preview.querySelector(
                '[data-file-size]'
            );

        if (nameElement) {
            nameElement.textContent = '';
        }

        if (sizeElement) {
            sizeElement.textContent = '';
        }
    },

    findPreview(container) {
        const previewId =
            container.dataset.filePreview;

        if (previewId) {
            return document.getElementById(
                previewId
            );
        }

        return container
            .parentElement
            ?.querySelector(
                '[data-file-preview]'
            );
    },

    showError(container, message) {
        let errorElement =
            container
                .parentElement
                ?.querySelector(
                    '[data-file-error]'
                );

        if (!errorElement) {
            errorElement =
                document.createElement(
                    'div'
                );

            errorElement.className =
                'form-error';

            errorElement.dataset.fileError =
                'true';

            container.insertAdjacentElement(
                'afterend',
                errorElement
            );
        }

        errorElement.textContent =
            message;
    },

    clearError(container) {
        const errorElement =
            container
                .parentElement
                ?.querySelector(
                    '[data-file-error]'
                );

        if (errorElement) {
            errorElement.remove();
        }
    },

    formatBytes(bytes) {
        if (
            bytes === 0
            || Number.isNaN(bytes)
        ) {
            return '0 B';
        }

        const units = [
            'B',
            'KB',
            'MB',
            'GB',
        ];

        const index =
            Math.floor(
                Math.log(bytes)
                / Math.log(1024)
            );

        const safeIndex =
            Math.min(
                index,
                units.length - 1
            );

        const value =
            bytes
            / Math.pow(
                1024,
                safeIndex
            );

        return `${value.toFixed(
            safeIndex === 0 ? 0 : 2
        )} ${units[safeIndex]}`;
    },
};


/*
|--------------------------------------------------------------------------
| 7. ALERTAS
|--------------------------------------------------------------------------
*/

const AlertManager = {
    defaultTimeout: 6000,

    init() {
        document
            .querySelectorAll(
                '[data-alert]'
            )
            .forEach((alert) => {
                this.initAlert(alert);
            });
    },

    initAlert(alert) {
        const closeButton =
            alert.querySelector(
                '[data-alert-close]'
            );

        if (closeButton) {
            closeButton.addEventListener(
                'click',
                () => {
                    this.remove(alert);
                }
            );
        }

        /*
         * Las alertas de error no desaparecen
         * automáticamente para que el usuario
         * pueda leerlas.
         */
        if (
            alert.classList.contains(
                'alert-danger'
            )
        ) {
            return;
        }

        if (
            alert.dataset.alertPersistent
            === 'true'
        ) {
            return;
        }

        const timeout =
            Number(
                alert.dataset.alertTimeout
            )
            || this.defaultTimeout;

        setTimeout(() => {
            this.remove(alert);
        }, timeout);
    },

    remove(alert) {
        if (!alert?.isConnected) {
            return;
        }

        alert.style.transition =
            'opacity 180ms ease, transform 180ms ease';

        alert.style.opacity = '0';

        alert.style.transform =
            'translateY(-4px)';

        setTimeout(() => {
            alert.remove();
        }, 190);
    },
};


/*
|--------------------------------------------------------------------------
| 8. FORMULARIOS
|--------------------------------------------------------------------------
*/

const FormManager = {
    init() {
        this.preventDoubleSubmit();
        this.initUppercaseFields();
        this.initTrimFields();
        this.initScoreInputs();
        this.initDateInputs();
    },

    /*
    |--------------------------------------------------------------------------
    | Evitar doble envío
    |--------------------------------------------------------------------------
    |
    | Se aplica únicamente después de que el navegador
    | determina que el formulario es válido.
    |
    */

    preventDoubleSubmit() {
        document
            .querySelectorAll(
                'form[data-prevent-double-submit]'
            )
            .forEach((form) => {
                form.addEventListener(
                    'submit',
                    (event) => {
                        if (
                            !form.checkValidity()
                        ) {
                            return;
                        }

                        if (
                            form.dataset.submitting
                            === 'true'
                        ) {
                            event.preventDefault();
                            return;
                        }

                        form.dataset.submitting =
                            'true';

                        const submitButtons =
                            form.querySelectorAll(
                                'button[type="submit"], input[type="submit"]'
                            );

                        submitButtons.forEach(
                            (button) => {
                                button.disabled =
                                    true;

                                if (
                                    button.tagName
                                    === 'BUTTON'
                                ) {
                                    button.classList.add(
                                        'is-loading'
                                    );
                                }
                            }
                        );
                    }
                );
            });
    },

    /*
    |--------------------------------------------------------------------------
    | Campos que deben escribirse en mayúsculas
    |--------------------------------------------------------------------------
    |
    | Útil si posteriormente queremos usar:
    |
    | data-uppercase
    |
    | en matrículas.
    |
    */

    initUppercaseFields() {
        document
            .querySelectorAll(
                '[data-uppercase]'
            )
            .forEach((input) => {
                input.addEventListener(
                    'input',
                    () => {
                        const start =
                            input.selectionStart;

                        const end =
                            input.selectionEnd;

                        input.value =
                            input.value.toUpperCase();

                        if (
                            start !== null
                            && end !== null
                        ) {
                            input.setSelectionRange(
                                start,
                                end
                            );
                        }
                    }
                );
            });
    },

    /*
    |--------------------------------------------------------------------------
    | Trim al perder foco
    |--------------------------------------------------------------------------
    */

    initTrimFields() {
        document
            .querySelectorAll(
                '[data-trim]'
            )
            .forEach((input) => {
                input.addEventListener(
                    'blur',
                    () => {
                        input.value =
                            input.value.trim();
                    }
                );
            });
    },

    /*
    |--------------------------------------------------------------------------
    | Calificaciones 0 - 10
    |--------------------------------------------------------------------------
    */

    initScoreInputs() {
        document
            .querySelectorAll(
                '[data-score-input]'
            )
            .forEach((input) => {
                input.addEventListener(
                    'input',
                    () => {
                        this.normalizeScore(
                            input
                        );
                    }
                );

                input.addEventListener(
                    'blur',
                    () => {
                        this.formatScore(
                            input
                        );
                    }
                );
            });
    },

    normalizeScore(input) {
        if (input.value === '') {
            return;
        }

        let value =
            Number(input.value);

        if (Number.isNaN(value)) {
            return;
        }

        if (value < 0) {
            value = 0;
        }

        if (value > 10) {
            value = 10;
        }

        input.value = value;
    },

    formatScore(input) {
        if (input.value === '') {
            return;
        }

        const value =
            Number(input.value);

        if (Number.isNaN(value)) {
            return;
        }

        input.value =
            Math.min(
                10,
                Math.max(0, value)
            ).toFixed(2);
    },

    /*
    |--------------------------------------------------------------------------
    | Fecha mínima
    |--------------------------------------------------------------------------
    |
    | Los inputs que tengan data-min-today
    | no permitirán seleccionar visualmente
    | una fecha anterior al día actual.
    |
    | Laravel vuelve a validarlo en servidor.
    |
    */

    initDateInputs() {
        document
            .querySelectorAll(
                'input[type="date"][data-min-today]'
            )
            .forEach((input) => {
                const today =
                    this.getLocalDate();

                input.min = today;
            });
    },

    getLocalDate() {
        const now = new Date();

        const year =
            now.getFullYear();

        const month =
            String(
                now.getMonth() + 1
            ).padStart(2, '0');

        const day =
            String(
                now.getDate()
            ).padStart(2, '0');

        return `${year}-${month}-${day}`;
    },
};


/*
|--------------------------------------------------------------------------
| 9. FILTROS AUTOMÁTICOS
|--------------------------------------------------------------------------
|
| Para selects:
|
| <select data-auto-submit>
|
| Buscar:
|
| El buscador NO se envía automáticamente mientras
| se escribe. El usuario puede pulsar Enter o utilizar
| el botón Buscar.
|
*/

const FilterManager = {
    init() {
        document
            .querySelectorAll(
                '[data-auto-submit]'
            )
            .forEach((element) => {
                element.addEventListener(
                    'change',
                    () => {
                        const form =
                            element.closest(
                                'form'
                            );

                        if (form) {
                            form.submit();
                        }
                    }
                );
            });

        document
            .querySelectorAll(
                '[data-clear-input]'
            )
            .forEach((button) => {
                button.addEventListener(
                    'click',
                    () => {
                        const target =
                            button.dataset.clearInput;

                        const input =
                            document.getElementById(
                                target
                            );

                        if (!input) {
                            return;
                        }

                        input.value = '';
                        input.focus();
                    }
                );
            });
    },
};


/*
|--------------------------------------------------------------------------
| 10. FUNCIONES GLOBALES AUXILIARES
|--------------------------------------------------------------------------
*/

window.SistemaDocente = {
    theme: ThemeManager,
    sidebar: SidebarManager,
    modal: ModalManager,
    dropdown: DropdownManager,

    formatBytes(bytes) {
        return FileUploadManager.formatBytes(
            bytes
        );
    },
};