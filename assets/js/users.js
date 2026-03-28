async function loadUsers() {
    const res = await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/users.php');
    const users = await res.json();

    const tbody = document.querySelector('#table-users tbody');

    tbody.innerHTML = users.map(u => `
  <tr>
    <td>${u.FullName}</td>
    <td>${u.Email}</td>
    <td>${u.Role}</td>
    <td>${u.CreatedAt}</td>
    <td>
  <span class="status-badge ${u.Active === 'online' ? 'status-active' : 'status-inactive'}">
    ${u.Active === 'online' ? 'Hoạt động' : 'Offline'}
  </span>
</td>
    <td>
      <button class="action-btn" title="Xem" onclick='openModal("user", ${JSON.stringify(u)}, true)'><i data-lucide="eye" style="width:14px"></i></button>
      <button class="action-btn" title="Sửa" onclick='openModal("user", ${JSON.stringify(u)})'><i data-lucide="edit-2" style="width:14px"></i></button>
      <button class="action-btn" style="color: #ef4444;" title="Xóa" onclick="deleteUser(${u.UserID})"><i data-lucide="trash-2" style="width:14px"></i></button>
    </td>
  </tr>
`).join('');

    lucide.createIcons();
}

async function deleteUser(id) {
    if (!confirm('Xóa người dùng này?')) return;

    await fetch('http://localhost:9999/CareerNest/CareerNest_Backend/api/users.php', {
        method: 'DELETE',
        body: `UserID=${id}`
    });

    loadUsers();
}

