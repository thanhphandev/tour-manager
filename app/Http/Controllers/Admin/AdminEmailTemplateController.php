<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminEmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::latest()->paginate(20);
        return view('admin.email-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.email-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:email_templates,name',
            'slug' => 'required|unique:email_templates,slug|alpha_dash',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['variables'])) {
            $validated['variables'] = json_decode($validated['variables'], true);
        }

        $template = EmailTemplate::create($validated);

        ActivityLog::log(
            "Created email template: {$template->name}",
            $template,
            auth()->user()
        );

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Đã tạo email template mới.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|unique:email_templates,name,' . $emailTemplate->id,
            'slug' => 'required|alpha_dash|unique:email_templates,slug,' . $emailTemplate->id,
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|json',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['variables'])) {
            $validated['variables'] = json_decode($validated['variables'], true);
        }

        $emailTemplate->update($validated);

        ActivityLog::log(
            "Updated email template: {$emailTemplate->name}",
            $emailTemplate,
            auth()->user()
        );

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template đã được cập nhật.');
    }

    public function show(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.show', compact('emailTemplate'));
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $name = $emailTemplate->name;
        $emailTemplate->delete();

        ActivityLog::log(
            "Deleted email template: {$name}",
            null,
            auth()->user()
        );

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Đã xóa email template.');
    }

    public function toggle(EmailTemplate $emailTemplate)
    {
        $emailTemplate->update(['is_active' => !$emailTemplate->is_active]);

        $status = $emailTemplate->is_active ? 'activated' : 'deactivated';
        
        ActivityLog::log(
            "Template {$emailTemplate->name} has been {$status}",
            $emailTemplate,
            auth()->user()
        );

        return back()->with('success', 'Đã cập nhật trạng thái template.');
    }
}
