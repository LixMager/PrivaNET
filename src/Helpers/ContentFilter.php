<?php
namespace App\Helpers;

class ContentFilter {
    /**
     * Sanitiza una cadena HTML permitiendo únicamente etiquetas y atributos seguros.
     * 
     * @param string $html
     * @return string
     */
    public static function sanitize(string $html): string {
        if (empty(trim($html))) {
            return '';
        }

        // Cargar HTML con soporte completo para UTF-8
        $dom = new \DOMDocument();
        
        // Desactivar reporte interno de warnings para etiquetas inválidas o HTML5 no estándar
        libxml_use_internal_errors(true);
        // Agregamos declaración de xml encoding y un div wrapper para procesar el fragmento de forma aislada
        $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $div = $dom->getElementsByTagName('div')->item(0);
        if (!$div) {
            return '';
        }

        self::sanitizeNode($dom, $div);

        // Reconstruir el HTML interno del div wrapper
        $innerHtml = '';
        foreach ($div->childNodes as $child) {
            $innerHtml .= $dom->saveHTML($child);
        }

        return $innerHtml;
    }

    /**
     * Sanitiza recursivamente un nodo del DOM.
     */
    private static function sanitizeNode(\DOMDocument $dom, \DOMNode $node): void {
        $allowedTags = ['b', 'strong', 'i', 'em', 'span', 'a', '#text', 'p', 'br'];
        $completelyRemoveTags = ['script', 'style', 'iframe', 'object', 'embed', 'form', 'input', 'button', 'textarea'];

        // Recorrer en sentido inverso ya que eliminaremos o reemplazaremos nodos sobre la marcha
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);
            if (!$child) {
                continue;
            }

            $tagName = strtolower($child->nodeName);

            // 1. Eliminar etiquetas de alto riesgo completamente (junto con su contenido de texto interno)
            if (in_array($tagName, $completelyRemoveTags)) {
                $node->removeChild($child);
                continue;
            }

            // 2. Si la etiqueta no está permitida (ej. div, table, etc.), extraemos su contenido hacia el padre
            if (!in_array($tagName, $allowedTags)) {
                while ($child->childNodes->length > 0) {
                    $grandchild = $child->childNodes->item(0);
                    $node->insertBefore($grandchild, $child);
                }
                $node->removeChild($child);
                continue;
            }

            // 3. Si la etiqueta está permitida, procesar sus atributos y recursivamente sus hijos
            if ($child->nodeType === XML_ELEMENT_NODE) {
                self::sanitizeAttributes($child);
                self::sanitizeNode($dom, $child);
            }
        }
    }

    /**
     * Sanitiza los atributos de un elemento DOM, permitiendo únicamente los seguros.
     */
    private static function sanitizeAttributes(\DOMElement $element): void {
        $tagName = strtolower($element->tagName);
        $attrs = [];

        // Copiar los atributos para evitar interferir con la iteración al removerlos
        foreach ($element->attributes as $attr) {
            $attrs[$attr->name] = $attr->value;
        }

        // Eliminar todos los atributos
        foreach (array_keys($attrs) as $name) {
            $element->removeAttribute($name);
        }

        // 1. Sanitizar enlaces <a>
        if ($tagName === 'a' && isset($attrs['href'])) {
            $href = trim($attrs['href']);
            
            // Validar que el enlace sea un esquema web seguro (http, https, mailto, anclas o relativos)
            // Esto bloquea "javascript:...", "data:...", "vbscript:...", etc.
            if (preg_match('/^(https?:\/\/|mailto:|#|\/)/i', $href) || !preg_match('/^[a-z]+:/i', $href)) {
                $element->setAttribute('href', $href);
                $element->setAttribute('target', '_blank');
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }

        // 2. Sanitizar etiquetas de estilo <span>
        if ($tagName === 'span' && isset($attrs['style'])) {
            $style = trim($attrs['style']);
            
            // Permitir únicamente definir el color de texto
            // Admite hex (ej: #fff, #3b82f6), nombres de colores básicos (ej: red, blue) y rgb/rgba (ej: rgb(255, 0, 0))
            if (preg_match('/^color\s*:\s*(#[a-f0-9]{3,6}|rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)|rgba\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*,\s*(0?\.\d+|0|1)\s*\)|[a-z]+)\s*;?$/i', $style)) {
                $element->setAttribute('style', $style);
            }
        }
    }
}
