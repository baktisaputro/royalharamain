<?php
/** Panel: Pesan Masuk (dari form kontak website) */
$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'status') {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'baru';
            db()->prepare('UPDATE contact_messages SET status=? WHERE id=?')->execute([$status, $id]);
            $msg = 'Status diperbarui.';
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('DELETE FROM contact_messages WHERE id=?')->execute([$id]);
            $msg = 'Pesan dihapus.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}

$st = $_GET['status'] ?? 'all';
$where = ($st === 'all') ? '1=1' : 'status = ' . db()->quote($st);
$messages = db()->query("SELECT * FROM contact_messages WHERE $where ORDER BY created_at DESC")->fetchAll();
$counts = db()->query("SELECT status, COUNT(*) c FROM contact_messages GROUP BY status")->fetchAll();
$countMap = ['baru'=>0,'dibaca'=>0,'selesai'=>0];
foreach ($counts as $c) $countMap[$c['status']] = (int)$c['c'];
?>
<div class="card" style="margin-bottom:16px">
  <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
    <a href="?tab=kontak" class="btn btn-sm <?= $st==='all'?'btn-primary':'btn-outline' ?>">Semua (<?= array_sum($countMap) ?>)</a>
    <a href="?tab=kontak&status=baru" class="btn btn-sm <?= $st==='baru'?'btn-primary':'btn-outline' ?>">Baru (<?= $countMap['baru'] ?>)</a>
    <a href="?tab=kontak&status=dibaca" class="btn btn-sm <?= $st==='dibaca'?'btn-primary':'btn-outline' ?>">Dibaca (<?= $countMap['dibaca'] ?>)</a>
    <a href="?tab=kontak&status=selesai" class="btn btn-sm <?= $st==='selesai'?'btn-primary':'btn-outline' ?>">Selesai (<?= $countMap['selesai'] ?>)</a>
  </div>
</div>

<div class="card">
  <h2><i class="fa-solid fa-envelope-open-text"></i> Pesan Kontak (<span><?= count($messages) ?></span>)</h2>
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Nama</th><th>Kontak</th><th>Subjek</th><th>Pesan</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($messages as $x): ?>
          <tr>
            <td><strong><?= htmlspecialchars($x['name']) ?></strong><?= $x['email'] ? '<br><span style="color:#6c757d;font-size:12px">'.htmlspecialchars($x['email']).'</span>' : '' ?></td>
            <td><?= $x['phone'] ? '<a href="tel:'.htmlspecialchars($x['phone']).'">'.htmlspecialchars($x['phone']).'</a>' : '—' ?></td>
            <td><?= htmlspecialchars($x['subject'] ?: '—') ?></td>
            <td style="color:#6c757d;max-width:260px"><?= htmlspecialchars(mb_substr($x['message'],0,80)) ?><?= mb_strlen($x['message'])>80?'…':'' ?></td>
            <td><span class="badge st-<?= $x['status'] ?>"><?= ucfirst($x['status']) ?></span></td>
            <td><?= date('d M Y H:i', strtotime($x['created_at'])) ?></td>
            <td>
              <div class="actions">
                <button type="button" class="btn btn-sm btn-outline" onclick="showMsg(<?= (int)$x['id'] ?>)"><i class="fa-solid fa-eye"></i></button>
                <?php if (!$readonly): ?>
                  <form method="post" action="?tab=kontak" style="display:inline">
                    <input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:4px 6px;border-radius:6px;font-size:12px">
                      <option value="baru" <?= $x['status']==='baru'?'selected':'' ?>>Baru</option>
                      <option value="dibaca" <?= $x['status']==='dibaca'?'selected':'' ?>>Dibaca</option>
                      <option value="selesai" <?= $x['status']==='selesai'?'selected':'' ?>>Selesai</option>
                    </select>
                  </form>
                  <form method="post" action="?tab=kontak" onsubmit="return confirm('Hapus pesan ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$messages): ?><tr><td colspan="7" class="empty"><i class="fa-regular fa-folder-open"></i><br>Belum ada pesan masuk.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div id="msgModal" class="modal-overlay" hidden>
  <div class="modal-box">
    <h3><i class="fa-solid fa-envelope"></i> Detail Pesan</h3>
    <div id="msgBody"></div>
    <div class="modal-actions"><button type="button" class="btn btn-secondary" onclick="closeMsgModal()">Tutup</button></div>
  </div>
</div>

<script>
  window.RH_MESSAGES = <?= json_encode($messages, JSON_UNESCAPED_UNICODE) ?>;
  function showMsg(id) {
    var m = window.RH_MESSAGES.find(function(x){ return parseInt(x.id,10) === parseInt(id,10); });
    if (!m) return;
    var html =
      '<div style="margin-bottom:10px"><strong>' + m.name + '</strong></div>' +
      '<div style="font-size:13px;color:#6c757d;margin-bottom:12px">' +
        (m.email ? 'Email: <a href="mailto:'+m.email+'">'+m.email+'</a><br>' : '') +
        (m.phone ? 'Telp/WA: ' + m.phone + '<br>' : '') +
        (m.subject ? 'Subjek: ' + m.subject + '<br>' : '') +
        'Tanggal: ' + m.created_at +
      '</div>' +
      '<div style="background:#f8f9fa;border:1px solid var(--border);border-radius:8px;padding:14px;white-space:pre-wrap">' + m.message + '</div>';
    document.getElementById('msgBody').innerHTML = html;
    document.getElementById('msgModal').hidden = false;
  }
  function closeMsgModal(){ document.getElementById('msgModal').hidden = true; }
  document.addEventListener('click', function(ev){
    if (ev.target && ev.target.id === 'msgModal' && ev.target.classList.contains('modal-overlay')) closeMsgModal();
  });
</script>
