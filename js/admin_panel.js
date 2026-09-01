/* Admin Panel helper - toast & modal ringan */
(function () {
  function toast(msg, type) {
    var t = document.getElementById('toast');
    if (!t) return;
    t.textContent = msg;
    t.className = 'toast show ' + (type || 'success');
    clearTimeout(t._timer);
    t._timer = setTimeout(function () { t.className = 'toast'; }, 2600);
  }
  window.rhToast = toast;

  function confirmModal(message, onYes) {
    var m = document.getElementById('modal');
    if (!m) return onYes(true);
    m.hidden = false;
    m.innerHTML =
      '<div class="modal-overlay" onclick="if(event.target===this)closeModal()">' +
      '<div class="modal-box">' +
      '<h3>Konfirmasi</h3><p>' + message + '</p>' +
      '<div class="modal-actions">' +
      '<button class="btn btn-secondary" onclick="closeModal()">Batal</button>' +
      '<button class="btn btn-danger" id="cnfYes">Ya, lanjutkan</button>' +
      '</div></div></div>';
    document.getElementById('cnfYes').onclick = function () {
      closeModal();
      onYes(true);
    };
  }
  window.rhConfirm = confirmModal;
  window.closeModal = function () {
    var m = document.getElementById('modal');
    if (m) m.hidden = true;
  };
})();
