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
          fetch(src)
            .then(response => response.text())
            .then(html => {
              modalBody.innerHTML = html;
              modal.style.display = 'block';

              // ⚡ Detectar qual modal foi carregado
              if (src.includes('profile.php')) {
                initializeProfileModal();
              } else if (src.includes('bank.php')) {
                initializeBankModal();
              }
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

// ✅ Função para mostrar/esconder senha (opcional)
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

// ✅ Captura envios de qualquer formulário dentro do modal
document.addEventListener("submit", function(e) {
  const form = e.target;

  if (form.closest('#formModal')) {
    e.preventDefault(); 

    const formData = new FormData(form);
    const action = form.getAttribute('action') || window.location.href;

    fetch(action, {
      method: form.method,
      body: formData,
    })
    .then(response => response.text())
    .then(html => {
      document.getElementById('modalBody').innerHTML = html;

      // ⚡ Detectar novamente qual formulário foi salvo e reexecutar inicialização correta
      if (action.includes('profile.php')) {
        initializeProfileModal();
      } else if (action.includes('bank.php')) {
        initializeBankModal();
      }
    })
    .catch(error => {
      console.error('Erro ao enviar formulário:', error);
      document.getElementById('modalBody').innerHTML = "<p>Ocorreu um erro ao enviar o formulário.</p>";
    });
  }
});

// ✅ Função que inicializa os botões "Edit/Save" do formulário de perfil
function initializeProfileModal() {
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');
  const profileForm = document.getElementById('profileForm');

  if (!editBtn || !saveBtn || !profileForm) {
    console.warn("Profile modal elements not found.");
    return;
  }

  editBtn.addEventListener('click', function() {
    document.getElementById('email').disabled = false;
    document.getElementById('mobile').disabled = false;
    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-block';
  });

  profileForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(profileForm);

    fetch('/bluereferralclub/header_component/profile.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      document.getElementById('responseMessage').innerHTML = data;

      document.getElementById('email').disabled = true;
      document.getElementById('mobile').disabled = true;
      editBtn.style.display = 'inline-block';
      saveBtn.style.display = 'none';
    })
    .catch(error => {
      console.error('❌ Error saving profile:', error);
      document.getElementById('responseMessage').innerHTML = "<p style='color:red;'>❌ Error saving profile.</p>";
    });
  }, { once: true });
}

// ✅ Função corrigida que inicializa os botões "Edit/Save" do formulário de banco
function initializeBankModal() {
  const editBtn = document.getElementById('editBtn');
  const saveBtn = document.getElementById('saveBtn');
  const bankForm = document.getElementById('bankForm');

  if (!editBtn || !saveBtn || !bankForm) {
    console.warn("Bank modal elements not found.");
    return;
  }

  editBtn.addEventListener('click', function () {
    ['bankName', 'agency', 'bsb', 'accountNumber', 'abnNumber'].forEach(id => {
      const field = document.getElementById(id);
      if (field) {
        field.disabled = false;
      }
    });

    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-block';
  });

  bankForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(bankForm);

    fetch('/bluereferralclub/header_component/bank.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      const responseDiv = document.getElementById('responseMessage');
      if (responseDiv) {
        responseDiv.innerHTML = data;
      }

      // Desabilita os campos após salvar
      ['bankName', 'agency', 'bsb', 'accountNumber', 'abnNumber'].forEach(id => {
        const field = document.getElementById(id);
        if (field) {
          field.disabled = true;
        }
      });

      // Alterna botões
      editBtn.style.display = 'inline-block';
      saveBtn.style.display = 'none';

      // ⏱️ Fecha o modal automaticamente após 1.5s
      setTimeout(() => {
        const modal = document.getElementById('formModal');
        const modalBody = document.getElementById('modalBody');
        if (modal && modalBody) {
          modal.style.display = 'none';
          modalBody.innerHTML = '';
        }
      }, 1500);
    })
    .catch(error => {
      console.error('❌ Error saving bank details:', error);
      const responseDiv = document.getElementById('responseMessage');
      if (responseDiv) {
        responseDiv.innerHTML = "<p style='color:red;'>❌ Error saving bank details.</p>";
      }
    });
  }, { once: true });
}







document.getElementById('btnShareReferral').addEventListener('click', function() {
    // ✅ Captura o referral_code já existente na página
    const referralCode = document.getElementById('referral_code').value.trim();
    
    if (!referralCode) {
        alert('Referral code not found.');
        return;
    }

    // ✅ Monta o link
    const referralLink = `https://bluefacilityservices.com.au/bluereferralclub/quote.php?referral=${encodeURIComponent(referralCode)}`;

    // ✅ Preenche o input do modal
    document.getElementById('referralLink').value = referralLink;

    // ✅ Atualiza os links de compartilhamento
    document.getElementById('whatsappShare').href = `https://wa.me/?text=${encodeURIComponent('Hi there, check out Blue Facility Services — I highly recommend them. Use this link and score XX % off! ' + referralLink)}`;
    document.getElementById('facebookShare').href = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(referralLink)}`;
    document.getElementById('linkedinShare').href = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(referralLink)}`;

    // ✅ Abre o modal
    document.getElementById('shareModal').style.display = 'block';
});

// Fechar o modal de compartilhar
document.getElementById('closeShareModal').addEventListener('click', function() {
    document.getElementById('shareModal').style.display = 'none';
});

// Copiar o link para área de transferência
document.getElementById('copyLink').addEventListener('click', function() {
    const copyText = document.getElementById('referralLink');
    copyText.select();
    copyText.setSelectionRange(0, 99999); // Para mobile
    document.execCommand("copy");

    alert("Referral link copied to clipboard!");
});

