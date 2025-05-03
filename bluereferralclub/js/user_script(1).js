document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("formModal");
  const modalBody = document.getElementById("modalBody");
  const closeModal = document.querySelector(".close-button");
  const accountToggle = document.getElementById("accountToggle");
  const accountDropdown = document.getElementById("accountDropdown");
  const accountMenu = document.getElementById("accountMenu");

  // ✅ Segurança: só adiciona se os elementos existirem
  if (modal && modalBody) {
    document.querySelectorAll('.menu-item').forEach(item => {
      item.addEventListener('click', e => {
        e.preventDefault();
        const src = e.target.getAttribute('data-modal');
        if (src) {
          modalBody.innerHTML = `<iframe src="${src}" style="width:100%;height:300px;border:none;"></iframe>`;
          modal.style.display = 'block';
        }
      });
    });

    if (closeModal) {
      closeModal.onclick = () => modal.style.display = 'none';
    }

    window.onclick = e => {
      if (e.target === modal) modal.style.display = 'none';
    };
  }

  // ✅ Dropdown "Account"
  if (accountToggle && accountDropdown && accountMenu) {
    accountToggle.onclick = () => {
      accountDropdown.style.display =
        accountDropdown.style.display === 'block' ? 'none' : 'block';
    };

    window.addEventListener('click', function (e) {
      if (!accountMenu.contains(e.target) && e.target !== accountToggle) {
        accountDropdown.style.display = 'none';
      }
    });
  }
});