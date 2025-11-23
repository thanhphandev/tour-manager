<?php

namespace App\Facades;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the displayable image URL from remote or local storage.
     *
     * @param string|null $path
     * @param string|null $default
     * @return string
     */
    public static function getUrl(?string $path, ?string $default = null): string
    {
        if (empty($path)) {
            return $default ?? asset('images/no-image.png');
        }
        // Nếu là URL tuyệt đối (http/https/ftp)
        if (preg_match('/^(http|https|ftp):\/\//i', $path)) {
            return $path;
        }
        // Nếu là đường dẫn lưu trong storage
        if (Storage::disk('public')->exists($path)) {
            return asset('storage/' . $path);
        }
        // Nếu là asset tĩnh
        if (file_exists(public_path($path))) {
            return asset($path);
        }
        // Trả về ảnh mặc định nếu không tìm thấy
        return $default ?? asset('images/no-image.png');
    }
}
