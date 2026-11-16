<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class AnnouncementContentSanitizer
{
    /** @var list<string> */
    private const ALLOWED_ELEMENTS = [
        'a',
        'blockquote',
        'br',
        'code',
        'em',
        'h2',
        'h3',
        'img',
        'li',
        'ol',
        'p',
        'pre',
        's',
        'strong',
        'u',
        'ul',
    ];

    /** @var list<string> */
    private const ELEMENTS_REMOVED_WITH_CONTENT = [
        'button',
        'embed',
        'form',
        'iframe',
        'input',
        'link',
        'math',
        'meta',
        'object',
        'script',
        'select',
        'style',
        'svg',
        'textarea',
    ];

    public function sanitize(string $content): string
    {
        if (trim($content) === '') {
            return '';
        }

        if ($content === strip_tags($content)) {
            return nl2br(htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrorState = libxml_use_internal_errors(true);

        $document->loadHTML(
            '<?xml encoding="UTF-8"><div id="announcement-content-root">'.$content.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD,
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorState);

        $root = (new DOMXPath($document))->query('//*[@id="announcement-content-root"]')->item(0);

        if (! $root instanceof DOMElement) {
            return '';
        }

        $this->sanitizeChildren($root);

        $sanitizedContent = '';

        foreach (iterator_to_array($root->childNodes) as $child) {
            $sanitizedContent .= $document->saveHTML($child);
        }

        return trim($sanitizedContent);
    }

    public function hasMeaningfulContent(string $content): bool
    {
        $textContent = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim($textContent) !== '' || str_contains($content, '<img');
    }

    private function sanitizeChildren(DOMNode $parent): void
    {
        foreach (iterator_to_array($parent->childNodes) as $child) {
            if (! $child instanceof DOMElement) {
                if ($child->nodeType === XML_COMMENT_NODE) {
                    $parent->removeChild($child);
                }

                continue;
            }

            $tagName = strtolower($child->tagName);

            if (in_array($tagName, self::ELEMENTS_REMOVED_WITH_CONTENT, true)) {
                $parent->removeChild($child);

                continue;
            }

            if (! in_array($tagName, self::ALLOWED_ELEMENTS, true)) {
                $this->sanitizeChildren($child);
                $this->unwrapElement($child);

                continue;
            }

            if (! $this->sanitizeAttributes($child, $tagName)) {
                $parent->removeChild($child);

                continue;
            }

            $this->sanitizeChildren($child);
        }
    }

    private function sanitizeAttributes(DOMElement $element, string $tagName): bool
    {
        $attributes = [];

        foreach (iterator_to_array($element->attributes) as $attribute) {
            $attributes[strtolower($attribute->name)] = $attribute->value;
            $element->removeAttributeNode($attribute);
        }

        if ($tagName === 'a') {
            $href = trim($attributes['href'] ?? '');

            // Tambahkan https:// jika tidak ada skema (mis. "google.com", "www.google.com").
            // Skema berbahaya seperti "javascript:" tetap dikenali sebagai skema,
            // sehingga tidak diberi awalan dan akan ditolak oleh isSafeLink().
            if ($href !== '' && ! preg_match('/^([a-z][a-z0-9+.\-]*:|\/|#)/i', $href)) {
                $href = 'https://'.$href;
            }

            if (! $this->isSafeLink($href)) {
                return true;
            }

            $element->setAttribute('href', $href);
            $element->setAttribute('target', '_blank');
            $element->setAttribute('rel', 'noopener noreferrer');

            if (isset($attributes['title'])) {
                $element->setAttribute('title', $attributes['title']);
            }
        }

        if ($tagName === 'img') {
            $source = trim($attributes['src'] ?? '');

            if (! $this->isStoredAnnouncementImage($source)) {
                return false;
            }

            $element->setAttribute('src', $source);
            $element->setAttribute('alt', $attributes['alt'] ?? 'Gambar pengumuman');
            $element->setAttribute('loading', 'lazy');
        }

        if ($tagName === 'li' && in_array($attributes['data-list'] ?? '', ['bullet', 'ordered'], true)) {
            $element->setAttribute('data-list', $attributes['data-list']);
        }

        return true;
    }

    private function isSafeLink(string $href): bool
    {
        if ($href === '' || preg_match('/[\x00-\x20\x7f]/u', $href) === 1) {
            return false;
        }

        $scheme = strtolower((string) parse_url($href, PHP_URL_SCHEME));

        return $scheme === ''
            ? str_starts_with($href, '/') || str_starts_with($href, '#')
            : in_array($scheme, ['http', 'https', 'mailto', 'tel'], true);
    }

    private function isStoredAnnouncementImage(string $source): bool
    {
        if ($source === '' || preg_match('/[\x00-\x20\x7f]/u', $source) === 1) {
            return false;
        }

        $path = (string) parse_url($source, PHP_URL_PATH);

        if (! str_starts_with($path, '/storage/announcement-images/')) {
            return false;
        }

        $host = parse_url($source, PHP_URL_HOST);
        $applicationHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        return $host === null || $applicationHost === null || strcasecmp($host, $applicationHost) === 0;
    }

    private function unwrapElement(DOMElement $element): void
    {
        $parent = $element->parentNode;

        if ($parent === null) {
            return;
        }

        while ($element->firstChild !== null) {
            $parent->insertBefore($element->firstChild, $element);
        }

        $parent->removeChild($element);
    }
}