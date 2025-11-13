<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    public function render(array $data = []): string
    {
        $body = $this->body;
        
        foreach ($data as $key => $value) {
            $body = str_replace("{{" . $key . "}}", $value, $body);
        }
        
        return $body;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function send(string $slug, string $to, array $data = [])
    {
        $template = static::where('slug', $slug)->where('is_active', true)->first();
        
        if (!$template) {
            return false;
        }

        $subject = $template->subject;
        foreach ($data as $key => $value) {
            $subject = str_replace("{{" . $key . "}}", $value, $subject);
        }
        
        $body = $template->render($data);

        // Send email using TemplateMail
        try {
            \Mail::to($to)->send(new \App\Mail\TemplateMail($subject, $body, $data));
            return true;
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}
