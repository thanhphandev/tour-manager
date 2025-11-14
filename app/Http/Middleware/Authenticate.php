<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            // Lưu URL hiện tại vào session để redirect sau khi đăng nhập
            $intendedUrl = $request->fullUrl();
            
            // Kiểm tra URL có hợp lệ không (không phải các route auth)
            if ($this->shouldSaveIntendedUrl($intendedUrl)) {
                session(['url.intended' => $intendedUrl]);
            }
            
            return route('login');
        }
        
        return null;
    }

    /**
     * Kiểm tra xem có nên lưu intended URL không
     */
    private function shouldSaveIntendedUrl(string $url): bool
    {
        // Không lưu các URL liên quan đến auth
        $excludedPaths = [
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/auth/google',
            '/logout',
        ];

        foreach ($excludedPaths as $path) {
            if (str_contains($url, $path)) {
                return false;
            }
        }

        return true;
    }
}
