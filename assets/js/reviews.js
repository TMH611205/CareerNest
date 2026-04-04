function renderReviewStars(rating) {
    rating = Number(rating || 0);
    let stars = '';
    for (let i = 0; i < 5; i++) {
        stars += i < rating ? '⭐' : '☆';
    }
    return stars;
}

function formatDate(dateStr) {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return d.toLocaleDateString('vi-VN');
}

function reviewStatusBadge(status) {
    if (status === 'Chờ duyệt') {
        return `<span class="status-badge status-pending">Chờ duyệt</span>`;
    }
    if (status === 'Từ chối') {
        return `<span class="status-badge" style="background:#fee2e2;color:#991b1b;">Từ chối</span>`;
    }
    return `<span class="status-badge status-active">Đã duyệt</span>`;
}

async function loadReviews() {
    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/reviews_admin.php');
    const data = await res.json();

    const tbody = document.querySelector('#table-reviews tbody');
    if (!tbody) return;

    tbody.innerHTML = data.map(r => {
        const safe = encodeURIComponent(JSON.stringify(r));

        return `
      <tr>
        <td>
          <div style="font-weight:600;">${r.FullName || 'Người dùng'}</div>
          <div style="font-size:12px;color:#64748b;">${r.CareerName || 'Không rõ ngành'}</div>
        </td>
        <td>${renderReviewStars(r.Rating)}</td>
        <td>${r.Comment || ''}</td>
        <td>${r.CreatedAt || ''}</td>
        <td>${reviewStatusBadge(r.Status || 'Chờ duyệt')}</td>
        <td>
        <button class="action-btn" title="Xem" onclick="openModal('review', JSON.parse(decodeURIComponent('${safe}')), true)">
            <i data-lucide="eye" style="width:14px"></i>
        </button>
        <button class="action-btn" title="Duyệt nhanh" onclick="updateReviewStatus(${r.ReviewID}, 'Đã duyệt')">
            <i data-lucide="check" style="width:14px"></i>
        </button>
        <button class="action-btn" style="color:#ef4444;" title="Xóa" onclick="deleteReview(${r.ReviewID})">
            <i data-lucide="trash-2" style="width:14px"></i>
        </button>
        </td>
      </tr>
    `;
    }).join('');

    if (window.lucide) lucide.createIcons();
}

function viewReview(review) {
    alert(
        `Người dùng: ${review.FullName || 'Người dùng'}\n` +
        `Ngành: ${review.CareerName || ''}\n` +
        `Số sao: ${review.Rating}\n` +
        `Nội dung: ${review.Comment || ''}\n` +
        `Ngày: ${review.CreatedAt || ''}\n` +
        `Trạng thái: ${review.Status || 'Chờ duyệt'}`
    );
}

async function updateReviewStatus(reviewId, newStatus) {
    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/reviews_admin.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            ReviewID: reviewId,
            Status: newStatus
        })
    });

    const result = await res.json();
    alert(result.message || result.error || 'Cập nhật thành công');
    loadReviews();
}

async function deleteReview(reviewId) {
    if (!confirm("Bạn có chắc muốn xóa đánh giá này?")) return;

    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/reviews_admin.php', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `ReviewID=${reviewId}`
    });

    const result = await res.json();
    alert(result.message || result.error || 'Xóa thành công');
    loadReviews();
}