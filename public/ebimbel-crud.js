document.addEventListener('DOMContentLoaded', () => {
  // Persist active Bootstrap tab per-page so after POST/PUT redirect-back
  // the user returns to the same tab (e.g. "Laporan harian").
  const tabStorageKey = `eb.activeTab:${window.location.pathname}`;
  const allTabTriggers = document.querySelectorAll('[data-bs-toggle="tab"]');

  if (allTabTriggers.length) {
    allTabTriggers.forEach((el) => {
      el.addEventListener('shown.bs.tab', () => {
        const target = el.getAttribute('data-bs-target') || el.getAttribute('href');
        if (target) localStorage.setItem(tabStorageKey, target);
      });
    });

    const savedTarget = localStorage.getItem(tabStorageKey);
    if (savedTarget) {
      const trigger = document.querySelector(`[data-bs-toggle="tab"][data-bs-target="${savedTarget}"], [data-bs-toggle="tab"][href="${savedTarget}"]`);
      if (trigger) {
        bootstrap.Tab.getOrCreateInstance(trigger).show();
      }
    }
  }

  document.querySelectorAll('[data-eb-edit]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const modal = document.getElementById(btn.dataset.ebModal);
      if (!modal) return;

      const form = modal.querySelector('form');
      if (!form) return;

      const data = JSON.parse(btn.dataset.ebEdit);

      form.action = btn.dataset.ebAction;

      let methodField = form.querySelector('input[name="_method"]');
      if (!methodField) {
        methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        form.appendChild(methodField);
      }
      methodField.value = 'PUT';

      Object.entries(data).forEach(([name, value]) => {
        if (name === 'permissions' && Array.isArray(value)) {
          form.querySelectorAll('input[name="permissions[]"]').forEach((checkbox) => {
            checkbox.checked = value.includes(checkbox.value);
          });
          return;
        }

        const field = form.querySelector(`[name="${name}"]`);
        if (!field) return;
        field.value = value ?? '';
      });

      const title = modal.querySelector('.modal-title');
      if (title && btn.dataset.ebTitle) {
        title.textContent = btn.dataset.ebTitle;
      }

      bootstrap.Modal.getOrCreateInstance(modal).show();
    });
  });

  document.querySelectorAll('.eb-modal').forEach((modal) => {
    modal.addEventListener('hidden.bs.modal', () => {
      const form = modal.querySelector('form');
      if (!form || !form.dataset.storeAction) return;

      form.action = form.dataset.storeAction;
      form.reset();

      form.querySelectorAll('input[name="permissions[]"]').forEach((checkbox) => {
        checkbox.checked = false;
      });

      const methodField = form.querySelector('input[name="_method"]');
      if (methodField) methodField.remove();

      const title = modal.querySelector('.modal-title');
      if (title && modal.dataset.defaultTitle) {
        title.textContent = modal.dataset.defaultTitle;
      }
    });
  });
});
