<?php

namespace App\View\Components;

use App\Services\MarkdownService;
use Illuminate\View\Component;
use Illuminate\View\View;

class MarkdownContent extends Component
{
    public string $content;
    public bool $styled;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $markdown = null,
        bool $styled = true
    ) {
        $markdownService = app(MarkdownService::class);
        $this->content = $styled 
            ? $markdownService->toStyledHtml($markdown) 
            : $markdownService->toHtml($markdown);
        $this->styled = $styled;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('components.markdown-content');
    }
}
