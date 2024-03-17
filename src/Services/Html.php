<?php

declare(strict_types=1);

namespace Yormy\TripwireLaravel\Services;

class Html
{
    /**
     * @return array<string>
     */
    public static function tags(): array
    {
        $tags = [
            '<bold>',
            '<strong>',
            '<i>',
            '<u>',
            '<s>',
            '<ins>',
            '<del>',
            '<p>',
            '<h1>',
            '<h2>',
            '<h3>',
            '<h4>',
            '<ol>',
            '<ul>',
            '<li>',
            '<div>',
            '<span>',
        ];

        // add terminator options
        $terminators = array_map(function ($item) {
            return str_replace('<', '</', $item);
        }, $tags);

        return array_merge($tags, $terminators);
    }
}
