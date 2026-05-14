const items = Array.from(document.querySelectorAll('.gallery-item'));
const lightbox = document.getElementById('lightbox');
const lightboxImg = document.getElementById('lightbox-img');
const lightboxCaption = document.getElementById('lightbox-caption');
const closeBtn = document.getElementById('lightbox-close');
const prevBtn = document.getElementById('lightbox-prev');
const nextBtn = document.getElementById('lightbox-next');

let current = 0;

function openLightbox(index) {
  current = index;
  const item = items[current];
  lightboxImg.src = item.dataset.src;
  lightboxImg.alt = item.querySelector('img').alt;
  lightboxCaption.textContent = item.dataset.caption;
  lightbox.hidden = false;
  document.body.style.overflow = 'hidden';
  closeBtn.focus();
}

function closeLightbox() {
  lightbox.hidden = true;
  document.body.style.overflow = '';
  items[current].focus();
}

function showPrev() {
  current = (current - 1 + items.length) % items.length;
  openLightbox(current);
}

function showNext() {
  current = (current + 1) % items.length;
  openLightbox(current);
}

items.forEach((item, i) => {
  item.addEventListener('click', () => openLightbox(i));
});

closeBtn.addEventListener('click', closeLightbox);
prevBtn.addEventListener('click', showPrev);
nextBtn.addEventListener('click', showNext);

lightbox.addEventListener('click', (e) => {
  if (e.target === lightbox) closeLightbox();
});

document.addEventListener('keydown', (e) => {
  if (lightbox.hidden) return;
  if (e.key === 'Escape') closeLightbox();
  if (e.key === 'ArrowLeft') showPrev();
  if (e.key === 'ArrowRight') showNext();
});