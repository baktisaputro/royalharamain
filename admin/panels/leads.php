<?php
$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'status') {
            $id = (int)($_POST['id'] ?? 0);
            $status = $_POST['status'] ?? 'baru';
            db()->prepare('UPDATE leads SET status=? WHERE id=?')->execute([$status, $id]);
            $msg = 'Status diperbarui.';
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('DELETE FROM leads WHERE id=?')->execute([$id]);
            $msg = 'Data pelanggan dihapus.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}

$st = $_GET['status'] ?? 'all';
$where = ($st === 'all') ? '1=1' : 'status = ' . db()->quote($st);
$leads = db()->query("SELECT * FROM leads WHERE $where ORDER BY created_at DESC")->fetchAll();
$counts = db()->query("SELECT status, COUNT(*) c FROM leads GROUP BY status")->fetchAll();
$countMap = ['baru'=>0,'dihubungi'=>0,'selesai'=>0];
foreach ($counts as $c) $countMap[$c['status']] = (int)$c['c'];
?>
<div class="card" style="margin-bottom:16px">
  <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
    <a href="?tab=leads" class="btn btn-sm <?= $st==='all'?'btn-primary':'btn-outline' ?>">Semua (<?= array_sum($countMap) ?>)</a>
    <a href="?tab=leads&status=baru" class="btn btn-sm <?= $st==='baru'?'btn-primary':'btn-outline' ?>">Baru (<?= $countMap['baru'] ?>)</a>
    <a href="?tab=leads&status=dihubungi" class="btn btn-sm <?= $st==='dihubungi'?'btn-primary':'btn-outline' ?>">Dihubungi (<?= $countMap['dihubungi'] ?>)</a>
    <a href="?tab=leads&status=selesai" class="btn btn-sm <?= $st==='selesai'?'btn-primary':'btn-outline' ?>">Selesai (<?= $countMap['selesai'] ?>)</a>
  </div>
</div>

<div class="card">
  <h2><i class="fa-solid fa-users"></i> Data Pendaftar (<span><?= count($leads) ?></span>)</h2>
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Nama</th><th>Kontak</th><th>Paket</th><th>Kota</th><th>Jumlah</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($leads as $x): ?>
          <tr>
            <td><strong><?= htmlspecialchars($x['name']) ?></strong><?= $x['email'] ? '<br><span style="color:#6c757d;font-size:12px">'.htmlspecialchars($x['email']).'</span>' : '' ?></td>
            <td><a href="tel:<?= htmlspecialchars($x['phone']) ?>"><?= htmlspecialchars($x['phone']) ?></a><?= $x['wa_link'] ? '<br><a href="'.htmlspecialchars($x['wa_link']).'" target="_blank" style="font-size:12px">WhatsApp</a>' : '' ?></td>
            <td><?= htmlspecialchars($x['package']) ?>(<?= htmlspecialchars($x['departure'] ?? '') ?>)</td>
            <td><?= htmlspecialchars($x['city']) ?></td>
            <td><?= (int)$x['guests'] ?> orang</td>
            <td><span class="badge st-<?= $x['status'] ?>"><?= ucfirst($x['status']) ?></span></td>
            <td><?= date('d M Y H:i', strtotime($x['created_at'])) ?></td>
            <td>
              <div class="actions">
                <?php if (!$readonly): ?>
                  <form method="post" action="?tab=leads" style="display:inline">
                    <input type="hidden" name="action" value="status"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <select name="status" onchange="this.form.submit()" style="padding:4px 6px;border-radius:6px;font-size:12px">
                      <option value="baru" <?= $x['status']==='baru'?'selected':'' ?>>Baru</option>
                      <option value="dihubungi" <?= $x['status']==='dihubungi'?'selected':'' ?>>Dihubungi</option>
                      <option value="selesai" <?= $x['status']==='selesai'?'selected':'' ?>>Selesai</option>
                    </select>
                  </form>
                <?php endif; ?>
                <?php if ($x['notes']): ?><button type="button" class="btn btn-sm btn-outline" onclick="alert('<?= htmlspecialchars(str_replace("'","\\'",$x['notes'])) ?>')"><i class="fa-solid fa-note-sticky"></i></button><?php endif; ?>
                <?php if (!$readonly): ?>
                  <form method="post" action="?tab=leads" onsubmit="return confirm('Hapus data ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$leads): ?><tr><td colspan="8" class="empty"><i class="fa-regular fa-folder-open"></i><br>Belum ada data pendaftar.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
