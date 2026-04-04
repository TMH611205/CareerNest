let allMajors = [];

// ================= LOAD DATA =================
async function loadMajorsFrontend() {
  try {
    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/majors.php');
    const data = await res.json();

    allMajors = data;
    renderMajors(data);

  } catch (err) {
    console.error("Lỗi load majors:", err);
  }
}

// ================= RENDER =================
function renderMajors(data) {
  const container = document.getElementById("majors-grid");

  if (!data.length) {
    container.innerHTML = `<p>Không có ngành nào</p>`;
    return;
  }

  container.innerHTML = data.map(m => {
    const salary = `${formatMoney(m.SalaryMin)} - ${formatMoney(m.SalaryMax)} VNĐ`;
    const img = m.ImageURL
  ? `http://localhost:9999/CareerNest/CareerNest_Backend/${m.ImageURL}`
  : 'assets/img/default.jpg';

    // rating
    const rating = Math.round(m.Rating || 0);

    const rating10 = (m.Rating ? m.Rating * 2 : 0).toFixed(1);

    // render sao
    let stars = '';
    for (let i = 0; i < 5; i++) {
      stars += `<i data-lucide="star" style="width: 12px; ${i < rating ? 'fill:#f59e0b;color:#f59e0b;' : ''}"></i>`;
    }
    return `
      <div class="major-card" data-category="${mapCategory(m.Category)}" data-title="${m.CareerName}">
            <div style="position: relative;"> 
          <img src="${img}" class="major-img" alt="${m.CareerName}" referrerPolicy="no-referrer">
              <div
                style="position: absolute; bottom: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 4px 12px; border-radius: 50px; font-size: 10px; font-weight: 700; color: var(--color-brand-blue);">
                ${rating10}/10 Hài lòng </div>
            </div>
            <div class="major-content">
              <div class="flex justify-between items-center mb-4">
                <span class="major-tag">${m.Category || 'Khác'}</span>
                <div class="flex gap-1">
                  <i data-lucide="star" style="width: 12px; fill: #f59e0b; color: #f59e0b;"></i>
                    <span style="font-size: 12px; color: #555;">${rating}.0</span>
                </div>
              </div>
              <h3 class="major-title">${m.CareerName}</h3>
              <p class="major-desc">${m.Description}</p>
              <div class="major-stats">
                 <div class="stat-item">
              <i data-lucide="zap" style="width: 14px;"></i>
              ${m.Highlight || 'Chưa có dữ liệu'}
            </div>
                <div class="stat-item"><i data-lucide="dollar-sign" style="width: 14px;"></i> 15M - 50M VNĐ</div>
              </div>
              <div class="flex gap-2 mt-6">
                <button class="btn"
                  onclick="if(window.askCareerAI) window.askCareerAI('Tư vấn về ngành ${m.CareerName}.');"
                  style="flex: 1; border: 1px solid var(--color-brand-blue); color: var(--color-brand-blue); font-size: 12px; padding: 8px;">
                  <i data-lucide="sparkles" style="width: 14px;"></i> Tư vấn AI
                </button>
                <button class="btn"
                  style="background: var(--color-brand-light-blue); color: var(--color-brand-blue); padding: 8px 12px;"><i
                    data-lucide="plus" style="width: 16px;"></i></button>
              </div>
            </div>
          </div>
    `;
  }).join('');

  lucide.createIcons();
}

// ================= FORMAT MONEY =================
function formatMoney(num) {
  if (!num) return "0";
  return Number(num).toLocaleString('vi-VN');
}

// ================= CATEGORY MAP =================
function mapCategory(category) {
  if (!category) return 'all';

  category = category.toLowerCase();

  if (category.includes('công nghệ') || category.includes('kỹ thuật')) return 'tech';
  if (category.includes('kinh tế') || category.includes('quản lý')) return 'business';
  if (category.includes('y')) return 'health';
  if (category.includes('nghệ thuật')) return 'art';
  if (category.includes('xã hội') || category.includes('sư phạm')) return 'social';

  return 'all';
}

// ================= SEARCH =================
document.getElementById("major-search-input").addEventListener("input", function () {
  const keyword = this.value.toLowerCase();

  const filtered = allMajors.filter(m =>
    m.CareerName.toLowerCase().includes(keyword)
  );

  renderMajors(filtered);
});

// ================= FILTER =================
document.querySelectorAll(".filter-chip").forEach(btn => {
  btn.addEventListener("click", function () {

    // active UI
    document.querySelectorAll(".filter-chip").forEach(b => b.classList.remove("active"));
    this.classList.add("active");

    const category = this.dataset.category;

    if (category === "all") {
      renderMajors(allMajors);
      return;
    }

    const filtered = allMajors.filter(m =>
      mapCategory(m.Category) === category
    );

    renderMajors(filtered);
  });
});

// ================= INIT =================
document.addEventListener("DOMContentLoaded", () => {
  loadMajorsFrontend();
});