const IMAGE_API_BASE = 'http://localhost:9999/CareerNest/CareerNest_Backend/';

function buildImageUrl(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  return IMAGE_API_BASE + path.replace(/^\/+/, '');
}

async function loadPageImages(pageName) {
  try {
    const res = await fetch(`${IMAGE_API_BASE}api/get_images.php?page=${encodeURIComponent(pageName)}`);
    const json = await res.json();

    if (!json.success || !Array.isArray(json.data)) return;

    json.data.forEach(item => {
      const elements = document.querySelectorAll(`[data-image-position="${item.position}"]`);

      elements.forEach(el => {
        const imageUrl = buildImageUrl(item.url);

        if (el.tagName === 'IMG') {
          el.src = imageUrl;
          if (item.description) el.alt = item.description;
        } else {
          el.style.backgroundImage = `url('${imageUrl}')`;
        }
      });
    });
  } catch (err) {
    console.error('Lỗi loadPageImages:', err);
  }
}