<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        // Lưu URL redirect từ middleware vào session
        if ($request->has('redirect')) {
            $redirectUrl = $request->query('redirect');
            if ($this->isValidRedirectUrl($redirectUrl)) {
                session(['url.intended' => $redirectUrl]);
            }
        }
        
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Lấy intended URL từ session (đã được lưu từ query parameter 'redirect')
        $intendedUrl = session()->pull('url.intended');
        
        if ($intendedUrl && $this->isValidRedirectUrl($intendedUrl)) {
            return redirect($intendedUrl);
        }

        // Redirect mặc định dựa vào role
        $user = Auth::user();
        return redirect()->intended($user->is_admin ? '/admin/dashboard' : '/');
    }

    /**
     * Kiểm tra URL redirect có hợp lệ và an toàn không
     */
    private function isValidRedirectUrl(string $url): bool
    {
        // Chỉ chấp nhận URL nội bộ
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $urlParts = parse_url($url);
            $appUrlParts = parse_url(config('app.url'));
            
            if (isset($urlParts['host']) && $urlParts['host'] !== $appUrlParts['host']) {
                return false;
            }
            
            if (isset($urlParts['scheme']) && !in_array($urlParts['scheme'], ['http', 'https'])) {
                return false;
            }
        }
        
        // Không chấp nhận redirect về trang auth (tránh loop)
        $excludedPaths = ['/login', '/register', '/logout'];
        foreach ($excludedPaths as $path) {
            if (str_contains($url, $path)) {
                return false;
            }
        }
        
        if (strpos($url, '/') === 0) {
            if (strpos($url, '//') === 0) {
                return false;
            }
            return true;
        }
        
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
