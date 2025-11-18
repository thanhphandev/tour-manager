<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect user to Google OAuth page
     */
    public function redirect(Request $request)
    {
        // Validate và lưu callback URL an toàn
        $intendedUrl = $request->query('callback');
        
        if ($intendedUrl && $this->isValidCallbackUrl($intendedUrl)) {
            session(['url.intended' => $intendedUrl]);
        }
        
        return Socialite::driver('google')
            ->stateless()
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

            // Tìm hoặc tạo user
            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                if (!$user->is_active) {
                    return redirect()->route('login')
                        ->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
                }
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                // Tạo user mới
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(32)), // Random password
                    'email_verified_at' => now(),
                    'is_admin' => false,
                    'is_active' => true,
                ]);
            }

            // Đăng nhập user
            Auth::login($user, true);

            // Lấy intended URL an toàn từ session
            $intendedUrl = session()->pull('url.intended');
            
            if ($intendedUrl && $this->isValidCallbackUrl($intendedUrl)) {
                return redirect($intendedUrl);
            }

            // Redirect mặc định dựa vào role
            return redirect()->intended($user->is_admin ? '/admin/dashboard' : '/');

        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            
            return redirect()->route('login')
                ->withErrors(['error' => 'Đăng nhập Google thất bại. Vui lòng thử lại.']);
        }
    }

    /**
     * Kiểm tra URL callback có hợp lệ và an toàn không
     * 
     * @param string $url
     * @return bool
     */
    private function isValidCallbackUrl(string $url): bool
    {
        // Chỉ chấp nhận URL nội bộ (không có domain hoặc cùng domain)
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $urlParts = parse_url($url);
            $appUrlParts = parse_url(config('app.url'));
            
            // Kiểm tra domain phải giống với APP_URL
            if (isset($urlParts['host']) && $urlParts['host'] !== $appUrlParts['host']) {
                return false;
            }
            
            // Kiểm tra scheme phải là http hoặc https
            if (isset($urlParts['scheme']) && !in_array($urlParts['scheme'], ['http', 'https'])) {
                return false;
            }
        }
        
        // Kiểm tra relative path (bắt đầu bằng /)
        if (strpos($url, '/') === 0) {
            // Không chấp nhận URL có // (protocol-relative URLs)
            if (strpos($url, '//') === 0) {
                return false;
            }
            
            return true;
        }
        
        // Nếu là full URL và đã pass các kiểm tra trên
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
