<?php
/**
 * Royal Haramain - Helper Upload & Kompresi Gambar
 * Tujuan: simpan gambar dari admin ke folder /uploads, otomatis
 * diperkecil (resize + kompres WebP/JPEG) supaya website tetap ringan.
 */

function handle_image_upload(array $file, string $target_subdir = ''): array
{
    // Batas ukuran (default 5MB). Menyesuaikan upload_max_filesize.
    $max_bytes = 5 * 1024 * 1024;

    // Cek ada file?
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['ok' => false, 'error' => 'Upload gagal (parameter tidak valid).'];
    }

    // Cek error upload
    switch ($file['error']) {
        case UPLOAD_ERR_OK: break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            return ['ok' => false, 'error' => 'File terlalu besar. Maksimal 5MB.'];
        case UPLOAD_ERR_NO_FILE:
            return ['ok' => false, 'error' => 'Tidak ada file dipilih.'];
        default:
            return ['ok' => false, 'error' => 'Upload gagal (error #' . $file['error'] . ').'];
    }

    if ($file['size'] > $max_bytes) {
        return ['ok' => false, 'error' => 'File melebihi 5MB. Pilih gambar yang lebih kecil.'];
    }

    // Validasi tipe
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    $mime = mime_content_type($file['tmp_name']);
    // fallback ukuran extension
    if (!isset($allowed[$mime])) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext === 'jpg' || $ext === 'jpeg') $mime = 'image/jpeg';
        elseif ($ext === 'png') $mime = 'image/png';
        elseif ($ext === 'webp') $mime = 'image/webp';
        elseif ($ext === 'gif') $mime = 'image/gif';
    }
    if (!isset($allowed[$mime])) {
        return ['ok' => false, 'error' => 'Tipe file tidak diizinkan. Gunakan JPG, PNG, WebP, atau GIF.'];
    }

    // Cek & buat folder upload
    $upload_dir = __DIR__ . '/../uploads/' . $target_subdir;
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0755, true)) {
            return ['ok' => false, 'error' => 'Gagal membuat folder upload.'];
        }
    }

    // Decode gambar
    $src = imagecreatefromstring(file_get_contents($file['tmp_name']));
    if ($src === false) {
        return ['ok' => false, 'error' => 'File bukan gambar yang valid.'];
    }

    // Kompresi: resize jika lebar > 1600px, konversi ke WebP bila didukung, else JPEG
    $max_w = 1600;
    $w = imagesx($src);
    $h = imagesy($src);
    if ($w > $max_w) {
        $nh = (int)round($h * ($max_w / $w));
        $dst = imagecreatetruecolor($max_w, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $max_w, $nh, $w, $h);
        imagedestroy($src);
        $src = $dst;
    }

    // Nama file unik (tanpa ekstensi, kita tentukan sesuai format output)
    $name = 'img_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4));

    $use_webp = function_exists('imagewebp');
    $rel_path = '';
    $abs_path = '';

    if ($use_webp) {
        $filename = $name . '.webp';
        $abs_path = $upload_dir . '/' . $filename;
        imagewebp($src, $abs_path, 78); // kualitas 78 = ringan & cukup tajam
        $rel_path = 'uploads/' . ($target_subdir ? $target_subdir . '/' : '') . $filename;
    } else {
        $filename = $name . '.jpg';
        $abs_path = $upload_dir . '/' . $filename;
        imagejpeg($src, $abs_path, 80);
        $rel_path = 'uploads/' . ($target_subdir ? $target_subdir . '/' : '') . $filename;
    }

    imagedestroy($src);

    if (!file_exists($abs_path) || filesize($abs_path) === 0) {
        return ['ok' => false, 'error' => 'Gagal menyimpan gambar (kompresi).'];
    }

    return [
        'ok'   => true,
        'path' => $rel_path,
        'size' => filesize($abs_path),
    ];
}

/** Hapus file upload (dipakai saat ganti/hapus gambar) */
function delete_upload(string $rel_path): void
{
    if ($rel_path === '') return;
    // hanya izinkan di dalam /uploads
    if (strpos($rel_path, 'uploads/') !== 0) return;
    $abs = __DIR__ . '/../' . $rel_path;
    if (is_file($abs) && file_exists($abs)) {
        @unlink($abs);
    }
}
