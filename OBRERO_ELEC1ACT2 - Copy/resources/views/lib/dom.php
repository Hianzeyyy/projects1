<?php
/**
 * Pure-PHP DOM builder.  Zero HTML literals.  Zero echo / print.
 * All HTML structure is built through PHP's DOMDocument API.
 */
class Dom
{
    private static ?DOMDocument $doc = null;

    public static function reset(): void
    {
        self::$doc = new DOMDocument('1.0', 'UTF-8');
        self::$doc->formatOutput = false;
    }

    public static function doc(): DOMDocument
    {
        if (self::$doc === null) self::reset();
        return self::$doc;
    }

    /**
     * Create an element; $children may contain DOMNode objects or plain strings.
     */
    public static function el(string $tag, array $attrs = [], array $children = []): DOMElement
    {
        $d = self::doc();
        $e = $d->createElement($tag);
        foreach ($attrs as $k => $v) {
            if ($v !== null && $v !== false) {
                $e->setAttribute($k, (string) $v);
            }
        }
        foreach ($children as $c) {
            if ($c === null) continue;
            $e->appendChild(is_string($c) ? $d->createTextNode($c) : $c);
        }
        return $e;
    }

    /** Create a plain text node. */
    public static function text(string $s): DOMText
    {
        return self::doc()->createTextNode($s);
    }

    /**
     * Wrap JavaScript source in a <script> element.
     * The $jsCode argument is a PHP string — no HTML syntax involved.
     */
    public static function script(string $jsCode): DOMElement
    {
        $el = self::doc()->createElement('script');
        $el->appendChild(self::doc()->createTextNode($jsCode));
        return $el;
    }

    /** Serialize to a full HTML document string — called once per request. */
    public static function html(): string
    {
        $root = self::doc()->documentElement;
        return $root ? '<!DOCTYPE html>' . "\n" . self::doc()->saveHTML($root) : '';
    }
}
