document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("formModal");
  const modalBody = document.getElementById("modalBody");
  const closeModal = document.querySelector(".close-button");
  const accountToggle = document.getElementById("accountToggle");
  const accountDropdown = document.getElementById("accountDropdown");
  const accountMenu = document.getElementById("accountMenu");

  // ✅ Modal abrir carregando conteúdo HTML direto (não iframe)
  if (modal && modalBody) {
    document.querySelectorAll('.menu-item').forEach(item => {
      item.addEventListener('click', e => {
        e.preventDefault();
        const src = e.target.getAttribute('data-modal');
        if (src) {
          fetch(src) // <- AQUI USAMOS FETCH!
            .then(response => response.text())
            .then(html => {
              modalBody.innerHTML = html;
              modal.style.display = 'block';
            })
            .catch(error => {
              modalBody.innerHTML = "<p>Failed to load content.</p>";
              modal.style.display = 'block';
            });
        }
      });
    });

    if (closeModal) {
      closeModal.onclick = () => {
        modal.style.display = 'none';
        modalBody.innerHTML = ""; // Limpa o conteúdo quando fecha
      };
    }

    window.onclick = e => {
      if (e.target === modal) {
        modal.style.display = 'none';
        modalBody.innerHTML = "";
      }
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

function togglePassword(el, inputId) {
  const input = document.getElementById(inputId);
  const icon = el.querySelector("img");
  if (input.type === "password") {
    input.type = "text";
    icon.src = "/bluereferralclub/assest/img/eye-off.svg";
  } else {
    input.type = "password";
    icon.src = "/bluereferralclub/assest/img/eye.svg";
  }
}

// ✅ Logout
const btnLogout = document.getElementById('btnLogout');
if (btnLogout) {
  btnLogout.addEventListener('click', function () {
    window.location.href = '/../logout.php'; 
  });
}

document.addEventListener("submit", function(e) {
  const form = e.target;

  if (form.closest('#formModal')) {  // Se o form está dentro do modal
    e.preventDefault(); // Para o comportamento padrão

    const formData = new FormData(form);
    const action = form.getAttribute('action') || window.location.href;

    fetch(action, {
      method: form.method,
      body: formData,
    })
    .then(response => response.text())
    .then(html => {
      document.getElementById('modalBody').innerHTML = html;
    })
    .catch(error => {
      console.error('Erro ao enviar formulário:', error);
      document.getElementById('modalBody').innerHTML = "<p>Ocorreu um erro ao enviar o formulário.</p>";
    });
  }
});

