
function shortText(text) {
  if (!text) return '';
  return text.length > 50 ? text.substring(0, 50) + '...' : text;
}

 //    <td>${m.Description ? shortText(m.Description) : ''}</td>
    //   <td>${m.Highlight || ''}</td>
    //   <td>${renderDemand(m.DemandLevel)}</td>
    //   <td>${m.Rating ?? 0} (${m.RatingCount ?? 0})</td>  

async function loadMajors() {
  const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/majors.php');
  const data = await res.json();
  
  const tbody = document.querySelector('#table-majors tbody');

  tbody.innerHTML = data.map(m => {
  const salary = m.SalaryMin && m.SalaryMax
    ? `${Number(m.SalaryMin).toLocaleString()} - ${Number(m.SalaryMax).toLocaleString()} VND`
    : "Chưa cập nhật";

    const safe = encodeURIComponent(JSON.stringify(m));

  return  `
    <tr>
        <td>${m.CareerCode}</td>
        <td>${m.CareerName}</td>
        <td>${m.Category || ''}</td>
        <td>${salary}</td>
      
      <td>${m.Views ?? 0}</td>
    <td>
      <button class="action-btn" title="Xem" onclick="openModal('major', JSON.parse(decodeURIComponent('${safe}')), true)"><i data-lucide="eye" style="width:14px"></i></button>
      <button class="action-btn" title="Sửa" onclick="openModal('major', JSON.parse(decodeURIComponent('${safe}')))"><i data-lucide="edit-2" style="width:14px"></i></button>
      <button class="action-btn" style="color: #ef4444;" title="Xóa" onclick="deleteMajor(${m.CareerID})"><i data-lucide="trash-2" style="width:14px"></i></button>
    </td>
  </tr>
`}).join('');  

    lucide.createIcons();
}

async function deleteMajor(id) {
  if (!confirm("Xóa ngành?")) return;

  await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/majors.php', {
    method: 'DELETE',
    headers: { 'Content-Type': 'application/json' },
    body: `CareerID=${id}`
  });

  loadMajors();
}


