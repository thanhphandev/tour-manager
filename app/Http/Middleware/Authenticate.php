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
            // Lưu URL hiện tại vào query parameter
            $intendedUrl = $request->fullUrl();
            
            // Chỉ lưu URL nội bộ, không phải route auth
            if ($this->shouldRedirectBack($intendedUrl)) {
                return route('login', ['redirect' => $intendedUrl]);
            }
            
            return route('login');
        }
        
        return null;
    }

    /**
     * Kiểm tra có nên redirect về URL này sau khi login không
     */
    private function shouldRedirectBack(string $url): bool
    {
        // Không redirect về các trang auth
        $excludedPaths = [
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/verify-email',
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
