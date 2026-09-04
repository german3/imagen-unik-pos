/**
 * notifications.js - Modern Popup Modal & Toast Notification System for IMAGEN UNIK POS
 * Zero external dependencies. Works 100% offline & online.
 * Replaces native JavaScript alert() & confirm() with modern, responsive popup windows.
 */

(function() {
    'use strict';

    // ── Icons SVG definitions ──────────────────────────────────────────────────
    const ICONS = {
        success: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="22" fill="#E6F4EA" stroke="#34A853" stroke-width="3"/>
            <path d="M15 24.5L21 30.5L33 17.5" stroke="#34A853" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`,
        error: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="22" fill="#FCE8E6" stroke="#EA4335" stroke-width="3"/>
            <path d="M17 17L31 31M31 17L17 31" stroke="#EA4335" stroke-width="3.5" stroke-linecap="round"/>
        </svg>`,
        warning: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="22" fill="#FEF7E0" stroke="#FBBC04" stroke-width="3"/>
            <path d="M24 14V26M24 32V34" stroke="#D97706" stroke-width="3.5" stroke-linecap="round"/>
        </svg>`,
        info: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="22" fill="#E8F0FE" stroke="#1A73E8" stroke-width="3"/>
            <path d="M24 16V17M24 22V32" stroke="#1A73E8" stroke-width="3.5" stroke-linecap="round"/>
        </svg>`,
        question: `<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="22" fill="#EEF2FF" stroke="#4F46E5" stroke-width="3"/>
            <path d="M19 19C19 16.2386 21.2386 14 24 14C26.7614 14 29 16.2386 29 19C29 21.084 27.7249 22.8703 25.9189 23.6136C24.7777 24.0833 24 25.2046 24 26.4389V28M24 33V34" stroke="#4F46E5" stroke-width="3.2" stroke-linecap="round"/>
        </svg>`
    };

    // ── Dynamic Styles Injection ───────────────────────────────────────────────
    function injectStyles() {
        if (document.getElementById('unik-popup-styles')) return;

        const css = `
            /* ══ UNIK POPUP & TOAST NOTIFICATION STYLES ══ */
            .unik-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(8px);
                -webkit-backdrop-filter: blur(8px);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 99999;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.25s;
                padding: 1.25rem;
            }

            .unik-modal-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .unik-modal-card {
                background: #ffffff;
                border-radius: 20px;
                width: 100%;
                max-width: 440px;
                padding: 2rem 1.75rem 1.5rem;
                box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(0, 0, 0, 0.05);
                transform: scale(0.92) translateY(12px);
                transition: transform 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
                text-align: center;
                position: relative;
                overflow: hidden;
            }

            .unik-modal-overlay.active .unik-modal-card {
                transform: scale(1) translateY(0);
            }

            .unik-modal-icon {
                margin: 0 auto 1.25rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                animation: unikIconPop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }

            @keyframes unikIconPop {
                0% { transform: scale(0.4); opacity: 0; }
                100% { transform: scale(1); opacity: 1; }
            }

            .unik-modal-title {
                font-size: 1.3rem;
                font-weight: 700;
                color: #1e293b;
                margin: 0 0 0.6rem;
                line-height: 1.3;
                letter-spacing: -0.3px;
            }

            .unik-modal-message {
                font-size: 0.95rem;
                color: #475569;
                margin: 0 0 1.75rem;
                line-height: 1.55;
                white-space: pre-line;
                word-break: break-word;
            }

            .unik-modal-actions {
                display: flex;
                gap: 0.75rem;
                justify-content: center;
            }

            .unik-modal-btn {
                flex: 1;
                padding: 0.75rem 1.25rem;
                border-radius: 12px;
                font-size: 0.95rem;
                font-weight: 600;
                font-family: inherit;
                cursor: pointer;
                border: none;
                transition: all 0.2s ease;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                outline: none;
            }

            .unik-modal-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
            }

            .unik-modal-btn:active {
                transform: translateY(0);
            }

            .unik-modal-btn-primary {
                background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(26, 115, 232, 0.35);
            }

            .unik-modal-btn-primary:hover {
                background: linear-gradient(135deg, #1865c9 0%, #0d47a1 100%);
                box-shadow: 0 6px 18px rgba(26, 115, 232, 0.45);
            }

            .unik-modal-btn-success {
                background: linear-gradient(135deg, #34a853 0%, #2d8d46 100%);
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(52, 168, 83, 0.35);
            }

            .unik-modal-btn-danger {
                background: linear-gradient(135deg, #ea4335 0%, #c5221f 100%);
                color: #ffffff;
                box-shadow: 0 4px 14px rgba(234, 67, 53, 0.35);
            }

            .unik-modal-btn-secondary {
                background: #f1f5f9;
                color: #475569;
                border: 1px solid #e2e8f0;
            }

            .unik-modal-btn-secondary:hover {
                background: #e2e8f0;
                color: #1e293b;
            }

            /* ── Toast Container ── */
            .unik-toast-container {
                position: fixed;
                top: 1.25rem;
                right: 1.25rem;
                z-index: 100000;
                display: flex;
                flex-direction: column;
                gap: 0.65rem;
                pointer-events: none;
                max-width: 380px;
                width: calc(100% - 2.5rem);
            }

            .unik-toast {
                pointer-events: auto;
                background: #ffffff;
                border-radius: 14px;
                padding: 0.9rem 1.15rem;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
                display: flex;
                align-items: center;
                gap: 0.85rem;
                transform: translateX(120%);
                opacity: 0;
                transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
                position: relative;
                overflow: hidden;
            }

            .unik-toast.active {
                transform: translateX(0);
                opacity: 1;
            }

            .unik-toast-icon {
                flex-shrink: 0;
                width: 28px;
                height: 28px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .unik-toast-icon svg {
                width: 28px;
                height: 28px;
            }

            .unik-toast-content {
                flex: 1;
                font-size: 0.92rem;
                font-weight: 500;
                color: #1e293b;
                line-height: 1.4;
            }

            .unik-toast-close {
                background: none;
                border: none;
                color: #94a3b8;
                font-size: 1.1rem;
                cursor: pointer;
                padding: 0 0.2rem;
                line-height: 1;
                transition: color 0.15s;
            }

            .unik-toast-close:hover {
                color: #475569;
            }

            .unik-toast-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                width: 100%;
                background: rgba(0,0,0,0.06);
            }

            .unik-toast-progress-bar {
                height: 100%;
                width: 100%;
                background: #1a73e8;
                transform-origin: left;
            }

            .unik-toast-success .unik-toast-progress-bar { background: #34a853; }
            .unik-toast-error .unik-toast-progress-bar { background: #ea4335; }
            .unik-toast-warning .unik-toast-progress-bar { background: #fbbc04; }
            .unik-toast-info .unik-toast-progress-bar { background: #1a73e8; }
        `;

        const styleTag = document.createElement('style');
        styleTag.id = 'unik-popup-styles';
        styleTag.textContent = css;
        document.head.appendChild(styleTag);
    }

    // Initialize styles on load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectStyles);
    } else {
        injectStyles();
    }

    // ── Helper Clean Message ───────────────────────────────────────────────────
    function sanitize(text) {
        return String(text || '').replace(/^[\u{1F300}-\u{1F9FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}\u{FE0F}✅❌⚠️🔒📋🛒📥🖨️💾✏️🗑️\s]+/u, '').trim();
    }

    // ── Toast Notification Function ───────────────────────────────────────────
    function showToast(message, type = 'success', duration = 3000) {
        injectStyles();
        let container = document.querySelector('.unik-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'unik-toast-container';
            document.body.appendChild(container);
        }

        let iconKey = type;
        if (type === 'danger') iconKey = 'error';
        if (!ICONS[iconKey]) iconKey = 'info';

        const toast = document.createElement('div');
        toast.className = `unik-toast unik-toast-${iconKey}`;
        toast.innerHTML = `
            <div class="unik-toast-icon">${ICONS[iconKey]}</div>
            <div class="unik-toast-content">${sanitize(message) || message}</div>
            <button class="unik-toast-close" title="Cerrar">✕</button>
            <div class="unik-toast-progress">
                <div class="unik-toast-progress-bar"></div>
            </div>
        `;

        container.appendChild(toast);

        // Force reflow for animation
        toast.offsetHeight;
        toast.classList.add('active');

        // Progress bar animation
        const progressBar = toast.querySelector('.unik-toast-progress-bar');
        progressBar.style.transition = `transform ${duration}ms linear`;
        progressBar.style.transform = 'scaleX(0)';

        let timer = null;
        let isClosing = false;

        function close() {
            if (isClosing) return;
            isClosing = true;
            toast.classList.remove('active');
            setTimeout(() => {
                toast.remove();
                if (container.children.length === 0) container.remove();
            }, 300);
        }

        timer = setTimeout(close, duration);

        toast.querySelector('.unik-toast-close').addEventListener('click', () => {
            clearTimeout(timer);
            close();
        });

        toast.addEventListener('mouseenter', () => {
            clearTimeout(timer);
            progressBar.style.transition = 'none';
        });

        toast.addEventListener('mouseleave', () => {
            timer = setTimeout(close, 1500);
        });
    }

    // ── Modal Alert Popup Function ────────────────────────────────────────────
    function showAlert(message, type = 'info', title = '', callback = null) {
        injectStyles();
        return new Promise((resolve) => {
            const raw = String(message || '');
            const cleanMsg = sanitize(raw) || raw;

            // Auto-detect type & title if not specified
            let detectedType = type;
            let defaultTitle = 'Notificación';

            if (raw.includes('✅') || raw.toLowerCase().includes('éxito') || raw.toLowerCase().includes('exitosamente') || raw.toLowerCase().includes('guardada') || raw.toLowerCase().includes('guardado') || raw.toLowerCase().includes('registrada') || type === 'success') {
                detectedType = 'success';
                defaultTitle = '¡Operación Exitosa!';
            } else if (raw.includes('❌') || raw.toLowerCase().includes('error') || raw.toLowerCase().includes('incorrecta') || raw.toLowerCase().includes('denegado') || type === 'error' || type === 'danger') {
                detectedType = 'error';
                defaultTitle = 'Atención';
            } else if (raw.includes('⚠️') || raw.toLowerCase().includes('advertencia') || raw.toLowerCase().includes('agregue') || raw.toLowerCase().includes('selecciona') || raw.toLowerCase().includes('ingresa') || type === 'warning') {
                detectedType = 'warning';
                defaultTitle = 'Advertencia';
            }

            const finalTitle = title || defaultTitle;
            let iconKey = detectedType;
            if (detectedType === 'danger') iconKey = 'error';
            if (!ICONS[iconKey]) iconKey = 'info';

            let btnClass = 'unik-modal-btn-primary';
            if (iconKey === 'success') btnClass = 'unik-modal-btn-success';
            if (iconKey === 'error') btnClass = 'unik-modal-btn-danger';

            const overlay = document.createElement('div');
            overlay.className = 'unik-modal-overlay';
            overlay.innerHTML = `
                <div class="unik-modal-card">
                    <div class="unik-modal-icon">${ICONS[iconKey]}</div>
                    <h3 class="unik-modal-title">${finalTitle}</h3>
                    <p class="unik-modal-message">${cleanMsg}</p>
                    <div class="unik-modal-actions">
                        <button type="button" class="unik-modal-btn ${btnClass}" id="unik-modal-ok-btn">Aceptar</button>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);

            // Trigger animation
            overlay.offsetHeight;
            overlay.classList.add('active');

            const okBtn = overlay.querySelector('#unik-modal-ok-btn');
            setTimeout(() => okBtn.focus(), 50);

            function handleClose() {
                overlay.classList.remove('active');
                document.removeEventListener('keydown', keyHandler);
                setTimeout(() => {
                    overlay.remove();
                    if (typeof callback === 'function') callback();
                    resolve(true);
                }, 250);
            }

            function keyHandler(e) {
                if (e.key === 'Enter' || e.key === 'Escape') {
                    e.preventDefault();
                    handleClose();
                }
            }

            okBtn.addEventListener('click', handleClose);
            document.addEventListener('keydown', keyHandler);
        });
    }

    // ── Confirmation Modal Popup Function ─────────────────────────────────────
    function showConfirm(title, text, onConfirm = null, onCancel = null, options = {}) {
        injectStyles();
        return new Promise((resolve) => {
            const confirmText = options.confirmText || 'Confirmar';
            const cancelText = options.cancelText || 'Cancelar';
            const iconKey = options.icon || 'question';
            const isDestructive = options.danger || false;

            const btnConfirmClass = isDestructive ? 'unik-modal-btn-danger' : 'unik-modal-btn-primary';

            const overlay = document.createElement('div');
            overlay.className = 'unik-modal-overlay';
            overlay.innerHTML = `
                <div class="unik-modal-card">
                    <div class="unik-modal-icon">${ICONS[iconKey] || ICONS.question}</div>
                    <h3 class="unik-modal-title">${title}</h3>
                    <p class="unik-modal-message">${text}</p>
                    <div class="unik-modal-actions">
                        <button type="button" class="unik-modal-btn unik-modal-btn-secondary" id="unik-confirm-cancel-btn">${cancelText}</button>
                        <button type="button" class="unik-modal-btn ${btnConfirmClass}" id="unik-confirm-ok-btn">${confirmText}</button>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);

            overlay.offsetHeight;
            overlay.classList.add('active');

            const okBtn = overlay.querySelector('#unik-confirm-ok-btn');
            const cancelBtn = overlay.querySelector('#unik-confirm-cancel-btn');
            setTimeout(() => okBtn.focus(), 50);

            function handleConfirm() {
                overlay.classList.remove('active');
                cleanup();
                setTimeout(() => {
                    overlay.remove();
                    if (typeof onConfirm === 'function') onConfirm();
                    resolve(true);
                }, 250);
            }

            function handleCancel() {
                overlay.classList.remove('active');
                cleanup();
                setTimeout(() => {
                    overlay.remove();
                    if (typeof onCancel === 'function') onCancel();
                    resolve(false);
                }, 250);
            }

            function keyHandler(e) {
                if (e.key === 'Escape') {
                    e.preventDefault();
                    handleCancel();
                }
            }

            function cleanup() {
                document.removeEventListener('keydown', keyHandler);
            }

            okBtn.addEventListener('click', handleConfirm);
            cancelBtn.addEventListener('click', handleCancel);
            document.addEventListener('keydown', keyHandler);
        });
    }

    // ── Global Native Alert & Confirm Overrides ────────────────────────────────
    window.alert = function(message) {
        return showAlert(message);
    };

    window.confirm = function(message) {
        // Since native confirm is synchronous in legacy scripts, we log and provide showConfirm
        return showConfirm('Confirmación', message);
    };

    // ── Expose to Global Scope ────────────────────────────────────────────────
    window.showToast = showToast;
    window.showAlert = showAlert;
    window.showConfirm = showConfirm;

})();
