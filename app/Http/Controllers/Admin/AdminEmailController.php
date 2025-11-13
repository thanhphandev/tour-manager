<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TemplateMail;

class AdminEmailController extends Controller
{
    public function index()
    {
        return view('admin.emails.index');
    }

    public function compose()
    {
        $templates = EmailTemplate::active()->get();
        $users = User::all();
        
        return view('admin.emails.compose', compact('templates', 'users'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'send_type' => 'required|in:individual,group,all',
        ]);

        $recipients = [];
        
        switch ($request->send_type) {
            case 'individual':
                $request->validate(['email' => 'required|email']);
                $recipients = [$request->email];
                break;
                
            case 'group':
                $request->validate(['user_ids' => 'required|array']);
                $recipients = User::whereIn('id', $request->user_ids)->pluck('email')->toArray();
                break;
                
            case 'all':
                $recipients = User::where('is_admin', false)->pluck('email')->toArray();
                break;
        }

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $email) {
            try {
                Mail::to($email)->send(new TemplateMail(
                    $request->subject,
                    $request->body,
                    []
                ));
                $sent++;
            } catch (\Exception $e) {
                \Log::error('Email failed to: ' . $email . ' - ' . $e->getMessage());
                $failed++;
            }
        }

        return redirect()->route('admin.emails.compose')
            ->with('success', "Email đã gửi thành công tới {$sent} người. {$failed} thất bại.");
    }

    public function preview(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:email_templates,id',
        ]);

        $template = EmailTemplate::findOrFail($request->template_id);
        
        // Sample data for preview
        $sampleData = [
            'name' => 'Nguyễn Văn A',
            'booking_code' => 'BK' . strtoupper(substr(md5(time()), 0, 8)),
            'tour_name' => 'Du lịch Hạ Long 3 ngày 2 đêm',
            'total_amount' => '5,000,000',
            'phone' => '0123456789',
            'email' => 'customer@example.com',
        ];

        $subject = $template->subject;
        foreach ($sampleData as $key => $value) {
            $subject = str_replace("{{" . $key . "}}", $value, $subject);
        }
        
        $body = $template->render($sampleData);

        return response()->json([
            'subject' => $subject,
            'body' => $body,
        ]);
    }
}
