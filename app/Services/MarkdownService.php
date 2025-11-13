<?php

namespace App\Services;

use Parsedown;
use Illuminate\Support\Facades\Cache;

class MarkdownService
{
    protected Parsedown $parsedown;

    public function __construct()
    {
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(true); // Enable safe mode for security
    }

    /**
     * Convert Markdown to HTML
     *
     * @param string|null $markdown
     * @param bool $useCache
     * @return string
     */
    public function toHtml(?string $markdown, bool $useCache = true): string
    {
        if (empty($markdown)) {
            return '';
        }

        if (!$useCache) {
            return $this->parsedown->text($markdown);
        }

        // Cache the result for 1 hour
        $cacheKey = 'markdown_' . md5($markdown);
        
        return Cache::remember($cacheKey, 3600, function () use ($markdown) {
            return $this->parsedown->text($markdown);
        });
    }

    /**
     * Convert Markdown to HTML with custom styling
     *
     * @param string|null $markdown
     * @return string
     */
    public function toStyledHtml(?string $markdown): string
    {
        $html = $this->toHtml($markdown);
        
        // Wrap in a styled container
        return '<div class="markdown-content">' . $html . '</div>';
    }

    /**
     * Get a preview of the markdown (first N characters)
     *
     * @param string|null $markdown
     * @param int $length
     * @return string
     */
    public function preview(?string $markdown, int $length = 200): string
    {
        if (empty($markdown)) {
            return '';
        }

        $stripped = strip_tags($this->toHtml($markdown));
        
        if (strlen($stripped) <= $length) {
            return $stripped;
        }

        return substr($stripped, 0, $length) . '...';
    }

    /**
     * Clear markdown cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::flush();
    }
}
