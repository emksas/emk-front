import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

const TYPE_TITLES = {
    success: 'Success',
    error: 'Error',
    warning: 'Warning',
    info: 'Information',
};

const DEFAULT_CONFIRM_OPTIONS = {
    icon: 'warning',
    title: 'Are you sure?',
    text: 'This action cannot be undone.',
    confirmButtonText: 'Continue',
    cancelButtonText: 'Cancel',
};

const DEFAULT_DELETE_OPTIONS = {
    ...DEFAULT_CONFIRM_OPTIONS,
    title: 'Delete record?',
    text: 'This action will permanently delete the selected record.',
    confirmButtonText: 'Delete',
};

const baseOptions = {
    buttonsStyling: false,
    reverseButtons: true,
    focusCancel: true,
    background: 'var(--app-alert-surface)',
    color: 'var(--app-alert-text)',
    showClass: {
        popup: 'swal2-show app-alert-show',
    },
    hideClass: {
        popup: 'swal2-hide app-alert-hide',
    },
    customClass: {
        container: 'app-alert-container',
        popup: 'app-alert-popup',
        icon: 'app-alert-icon',
        title: 'app-alert-title',
        htmlContainer: 'app-alert-content',
        actions: 'app-alert-actions',
        confirmButton: 'app-alert-button app-alert-button-primary',
        cancelButton: 'app-alert-button app-alert-button-secondary',
        denyButton: 'app-alert-button app-alert-button-danger',
        loader: 'app-alert-loader',
    },
};

const toastOptions = {
    ...baseOptions,
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4500,
    timerProgressBar: true,
    showCloseButton: true,
    customClass: {
        ...baseOptions.customClass,
        popup: 'app-alert-popup app-alert-toast',
    },
};

function fire(options = {}) {
    return Swal.fire({
        ...baseOptions,
        ...options,
    });
}

function showToast(icon, message, title = TYPE_TITLES[icon]) {
    if (!message) {
        return Promise.resolve();
    }

    return Swal.fire({
        ...toastOptions,
        icon,
        title,
        text: message,
    });
}

function getElementOptions(element, defaults = {}) {
    return {
        ...defaults,
        title: element.dataset.confirmTitle || defaults.title,
        text: element.dataset.confirmMessage || defaults.text,
        icon: element.dataset.confirmIcon || defaults.icon,
        confirmButtonText: element.dataset.confirmText || defaults.confirmButtonText,
        cancelButtonText: element.dataset.cancelText || defaults.cancelButtonText,
        loadingTitle: element.dataset.loadingTitle,
        loadingMessage: element.dataset.loadingMessage,
    };
}

function disableSubmitter(submitter) {
    if (!submitter) {
        return;
    }

    submitter.dataset.originalDisabled = submitter.disabled ? 'true' : 'false';
    submitter.disabled = true;
}

function showElementLoading(element) {
    if (!element.dataset.loading) {
        return;
    }

    AppAlert.showLoading({
        title: element.dataset.loadingTitle || 'Processing request',
        text: element.dataset.loadingMessage || 'Please wait while the request finishes.',
    });
}

function handleLoadingSubmit(event) {
    const form = event.target;

    if (!form.matches('form[data-loading]') || form.matches('form[data-confirm], form[data-confirm-delete]')) {
        return;
    }

    disableSubmitter(event.submitter);
    showElementLoading(form);
}

function handleConfirmableSubmit(event) {
    const form = event.target;

    if (!form.matches('form[data-confirm], form[data-confirm-delete]') || form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();

    const submitter = event.submitter;
    const defaults = form.dataset.confirmDelete !== undefined ? DEFAULT_DELETE_OPTIONS : DEFAULT_CONFIRM_OPTIONS;
    const confirm = form.dataset.confirmDelete !== undefined ? AppAlert.confirmDelete : AppAlert.confirmAction;

    confirm(getElementOptions(form, defaults)).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        form.dataset.confirmed = 'true';
        showElementLoading(form);
        form.requestSubmit(submitter || undefined);
        disableSubmitter(submitter);
    });
}

function handleConfirmableClick(event) {
    const trigger = event.target.closest('[data-confirm-redirect]');

    if (!trigger || trigger.dataset.confirmed === 'true') {
        return;
    }

    const url = trigger.dataset.confirmRedirect || trigger.href;

    if (!url) {
        return;
    }

    event.preventDefault();

    AppAlert.confirmRedirect(url, getElementOptions(trigger, DEFAULT_CONFIRM_OPTIONS));
}

function handleLoadingClick(event) {
    const trigger = event.target.closest('a[data-loading]:not([data-confirm-redirect])');

    if (!trigger || !trigger.href || trigger.target === '_blank' || event.metaKey || event.ctrlKey || event.shiftKey) {
        return;
    }

    showElementLoading(trigger);
}

function showFlashMessages() {
    const messages = window.AppFlashMessages || {};
    const flashTypes = {
        success: 'Success',
        error: 'Error',
        warning: 'Warning',
        info: 'Info',
        status: 'Info',
    };

    Object.entries(messages).forEach(([type, message]) => {
        const method = `show${flashTypes[type]}`;

        if (!message || typeof AppAlert[method] !== 'function') {
            return;
        }

        AppAlert[method](message);
    });
}

function registerLivewireNotifications() {
    document.addEventListener('livewire:init', () => {
        if (!window.Livewire) {
            return;
        }

        window.Livewire.on('saved', () => {
            AppAlert.showSuccess('Changes saved successfully.');
        });

        window.Livewire.on('loggedOut', () => {
            AppAlert.showSuccess('Other browser sessions have been logged out.');
        });
    });
}

const AppAlert = {
    fire,
    showSuccess(message, title = TYPE_TITLES.success) {
        return showToast('success', message, title);
    },
    showError(message, title = TYPE_TITLES.error) {
        return showToast('error', message, title);
    },
    showWarning(message, title = TYPE_TITLES.warning) {
        return showToast('warning', message, title);
    },
    showInfo(message, title = TYPE_TITLES.info) {
        return showToast('info', message, title);
    },
    showLoading({ title = 'Loading', text = 'Please wait.' } = {}) {
        return Swal.fire({
            ...baseOptions,
            title,
            text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    },
    closeLoading() {
        Swal.close();
    },
    confirmDelete(options = {}) {
        return fire({
            ...DEFAULT_DELETE_OPTIONS,
            ...options,
            showCancelButton: true,
            confirmButtonColor: undefined,
            customClass: {
                ...baseOptions.customClass,
                ...(options.customClass || {}),
                confirmButton: 'app-alert-button app-alert-button-danger',
                cancelButton: 'app-alert-button app-alert-button-secondary',
            },
        });
    },
    confirmAction(options = {}) {
        return fire({
            ...DEFAULT_CONFIRM_OPTIONS,
            ...options,
            showCancelButton: true,
        });
    },
    confirmRedirect(url, options = {}) {
        return this.confirmAction(options).then((result) => {
            if (!result.isConfirmed) {
                return result;
            }

            this.showLoading({
                title: options.loadingTitle || 'Opening page',
                text: options.loadingMessage || 'Please wait while we redirect you.',
            });

            window.location.assign(url);
            return result;
        });
    },
    bind() {
        document.addEventListener('submit', handleConfirmableSubmit);
        document.addEventListener('submit', handleLoadingSubmit);
        document.addEventListener('click', handleConfirmableClick);
        document.addEventListener('click', handleLoadingClick);
        registerLivewireNotifications();

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showFlashMessages, { once: true });
            return;
        }

        showFlashMessages();
    },
};

window.AppAlert = AppAlert;

export default AppAlert;
