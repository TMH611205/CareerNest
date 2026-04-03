function imageFullUrl(path) {
  if (!path) return '';
  return `http://localhost:9999/CareerNest/CareerNest_Backend/${path}`;
}

async function loadImages() {
  try {
    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/images.php');
    const data = await res.json();

    const tbody = document.querySelector('#table-images tbody');
    if (!tbody) return;

    tbody.innerHTML = data.map(img => {
      const safe = encodeURIComponent(JSON.stringify(img));

      return `
        <tr>
          <td>${img.page || ''}</td>
          <td>${img.position || ''}</td>
          <td>
            ${
              img.url
                ? `<img src="${imageFullUrl(img.url)}" alt="image" style="width:80px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">`
                : 'Chưa có ảnh'
            }
          </td>
          <td>${img.description || ''}</td>
          <td>
            <button class="action-btn" title="Xem" onclick="openModal('image', JSON.parse(decodeURIComponent('${safe}')), true)">
              <i data-lucide="eye" style="width:14px"></i>
            </button>
            <button class="action-btn" title="Sửa" onclick="openModal('image', JSON.parse(decodeURIComponent('${safe}')))">
              <i data-lucide="edit-2" style="width:14px"></i>
            </button>
            <button class="action-btn" style="color:#ef4444;" title="Xóa" onclick="deleteImage(${img.id})">
              <i data-lucide="trash-2" style="width:14px"></i>
            </button>
          </td>
        </tr>
      `;
    }).join('');

    lucide.createIcons();
  } catch (err) {
    console.error("Lỗi loadImages:", err);
  }
}

async function deleteImage(id) {
  if (!confirm("Bạn có chắc muốn xóa ảnh này?")) return;

  try {
    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/images.php', {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id=${id}`
    });

    const result = await res.json();
    alert(result.message || 'Đã xóa');

    loadImages();
  } catch (err) {
    console.error("Lỗi deleteImage:", err);
    alert("Xóa ảnh thất bại");
  }
}