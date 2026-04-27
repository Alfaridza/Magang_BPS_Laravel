// public/js/admin-modal.js
(function () {
    function $(s) { return document.querySelector(s); }
    function $all(s, ctx) { return (ctx || document).querySelectorAll(s); }

    const overlay = $('#modal-overlay');
    const content = $('#modal-content');
    const closeBtn = $('#modal-close');

    function openModal(html) {
        if (!overlay || !content) return;
        content.innerHTML = html;
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
        attachModalFormHandlers();
    }
    function closeModal() {
        if (!overlay || !content) return;
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        content.innerHTML = '';
    }

    // handle open triggers (delegation) for elements with .open-modal and data-url
    document.addEventListener('click', function (e) {
        const trigger = e.target.closest('.open-modal');
        if (!trigger) return;
        e.preventDefault();
        const url = trigger.dataset.url;
        if (!url) return;
        fetch(url, { credentials: 'same-origin' })
            .then(r => { if (!r.ok) throw r; return r.text(); })
            .then(html => openModal(html))
            .catch(err => openModal('<div class="p-4 text-red-600">Gagal memuat form. Coba lagi.</div>'));
    });

    // close handlers
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });
    }
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    function attachModalFormHandlers() {
        const form = content.querySelector('form');
        if (!form) return;

        // remove previous highlights when user types
        content.addEventListener('input', function (ev) {
            const t = ev.target;
            if (t && t.matches('input,textarea,select')) t.classList.remove('border-red-500');
        });

        form.addEventListener('submit', function (ev) {
            ev.preventDefault();
            let errBox = content.querySelector('.ajax-errors');
            if (errBox) errBox.remove();

            const fd = new FormData(form);
            fetch(form.action, {
                method: form.method || 'POST',
                body: fd,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(async res => {
                    if (res.status === 422) {
                        const data = await res.json();
                        const errors = data.errors || {};
                        errBox = document.createElement('div');
                        errBox.className = 'ajax-errors bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                        let list = '<ul>';
                        Object.values(errors).forEach(arr => arr.forEach(msg => list += `<li class="list-disc ml-5">${msg}</li>`));
                        list += '</ul>';
                        errBox.innerHTML = list;
                        content.prepend(errBox);
                        const firstKey = Object.keys(errors)[0];
                        if (firstKey) {
                            const field = form.querySelector(`[name="${firstKey}"]`);
                            if (field) { field.classList.add('border-red-500'); field.focus(); }
                        }
                    } else if (res.ok) {
                        // Close modal and reload after a short delay
                        setTimeout(() => {
                            closeModal();
                            window.location.reload();
                        }, 1000);
                    } else {
                        errBox = document.createElement('div');
                        errBox.className = 'ajax-errors bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                        errBox.innerHTML = '<div>Terjadi kesalahan. Coba lagi.</div>';
                        content.prepend(errBox);
                    }
                })
                .catch(() => {
                    errBox = document.createElement('div');
                    errBox.className = 'ajax-errors bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4';
                    errBox.innerHTML = '<div>Gagal mengirim data. Periksa koneksi.</div>';
                    content.prepend(errBox);
                });
        }, { once: true });
    }
})();
