const form = document.getElementById('booking-form');
const success = document.getElementById('form-success');

const fields = {
  'first-name': { el: null, validate: v => v.trim().length >= 2 },
  'last-name':  { el: null, validate: v => v.trim().length >= 2 },
  'email':      { el: null, validate: v => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
  'phone':      { el: null, validate: v => /^(\+?61|0)[2-9]\d{8}$/.test(v.replace(/\s/g,'')) },
  'dob':        { el: null, validate: v => v !== '' },
  'service':    { el: null, validate: v => v !== '' },
  'preferred-date': { el: null, validate: v => {
    if (!v) return false;
    return new Date(v) >= new Date(new Date().toDateString());
  }},
  'preferred-time': { el: null, validate: v => v !== '' },
  'privacy':    { el: null, validate: (v, el) => el.checked }
};

Object.keys(fields).forEach(id => {
  fields[id].el = document.getElementById(id);
});

function validateField(id) {
  const field = fields[id];
  const el = field.el;
  const errorEl = document.getElementById(id + '-error') ||
                  document.getElementById(id.replace('preferred-', '') + '-error') ||
                  document.getElementById('privacy-error');
  const valid = field.validate(el.value, el);

  if (!valid) {
    el.classList.add('error');
    if (errorEl) errorEl.classList.add('visible');
  } else {
    el.classList.remove('error');
    if (errorEl) errorEl.classList.remove('visible');
  }
  return valid;
}

Object.keys(fields).forEach(id => {
  const el = fields[id].el;
  if (!el) return;
  el.addEventListener('blur', () => validateField(id));
  el.addEventListener('input', () => {
    if (el.classList.contains('error')) validateField(id);
  });
});

form.addEventListener('submit', (e) => {
  e.preventDefault();
  const allValid = Object.keys(fields).map(id => validateField(id)).every(Boolean);
  if (allValid) {
    form.hidden = true;
    success.hidden = false;
    success.focus();
    window.scrollTo({ top: success.offsetTop - 100, behavior: 'smooth' });
  } else {
    const firstError = form.querySelector('.error');
    if (firstError) firstError.focus();
  }
});