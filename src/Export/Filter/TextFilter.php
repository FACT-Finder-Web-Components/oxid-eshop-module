<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Oxid\Export\Filter;

class TextFilter implements FilterInterface
{
    public function filterValue(?string $value): string
    {
        if (empty($value)) {
            $value = '';
        }

        // phpcs:ignore
        $tags  = '#<(address|article|aside|blockquote|br|canvas|dd|div|dl|dt|fieldset|figcaption|figure|footer|form|h[1-6]|header|hr|li|main|nav|noscript|ol|p|pre|section|table|tfoot|ul|video)#';
        $value = preg_replace($tags, ' <$1', $value); // Add one space in front of block elements before stripping tags
        $value = strip_tags($value);
        $value = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
        $value = mb_convert_encoding($value, 'UTF-8', 'HTML-ENTITIES');
        $value = str_replace("\xc2\xa0", ' ', $value);
        $value = preg_replace('#\s+#', ' ', $value);
        $value = preg_replace('#[[:^print:]]#u', '', $value);
        return trim($value);
    }
}
