<?php
namespace App\Helpers;

class ContentFilter {
    /**
     * Sanitiza una cadena HTML permitiendo únicamente etiquetas y atributos seguros.
     * 
     * @param string $html
     * @return string
     */
    public static function filter(string $html): string {
        if (empty(trim($html))) {
            return '';
        }

        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $div = $dom->getElementsByTagName('div')->item(0);
        if (!$div) {
            return '';
        }

        self::filterNode($dom, $div);

        $innerHtml = '';
        foreach ($div->childNodes as $child) {
            $innerHtml .= $dom->saveHTML($child);
        }

        return $innerHtml;
    }

    private static function filterNode(\DOMDocument $dom, \DOMNode $node): void {
        $allowedTags = ['b', 'strong', 'i', 'em', 'span', 'a', '#text', 'p', 'br', 'ul', 'ol', 'li', 'u', 's', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        $completelyRemoveTags = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea'];

        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if (!$child) {
                continue;
            }

            $tagName = strtolower($child->nodeName);

            if (in_array($tagName, $completelyRemoveTags)) {
                $node->removeChild($child);
                continue;
            }

            if (!in_array($tagName, $allowedTags)) {
                while ($child->childNodes->length > 0) {
                    $grandchild = $child->childNodes->item(0);
                    $node->insertBefore($grandchild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            if ($child->nodeType === XML_ELEMENT_NODE) {
                self::filterAttributes($child);
                self::filterNode($dom, $child);
            }
        }
    }

    private static function filterAttributes(\DOMElement $element): void {
        $tagName = strtolower($element->tagName);
        $attrs = [];

        foreach ($element->attributes as $attr) {
            $attrs[$attr->name] = $attr->value;
        }

        // Permite atributos seguros como class, id, title
        $allowedAttributes = ['class', 'id', 'title'];

        foreach (array_keys($attrs) as $name) {
            if (!in_array($name, $allowedAttributes)) {
                $element->removeAttribute($name);
            }
        }

        if ($tagName === 'a' && isset($attrs['href'])) {
            $href = trim($attrs['href']);
            // Bloquear explícitamente javascript: y data:
            if (!preg_match('/^(javascript|data|vbscript):/i', $href)) {
                $element->setAttribute('href', $href);
                $element->setAttribute('target', '_blank');
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        if (isset($attrs['style'])) {
            $style = trim($attrs['style']);
            if (preg_match('/^color\s*:\s*(#[a-f0-9]{3,6}|rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)|rgba\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*(0?\.\d+|0|1)\s*\)|[a-z]+)\s*;?$/i', $style)) {
                $element->setAttribute('style', $style);
            }
        }
    }
}
