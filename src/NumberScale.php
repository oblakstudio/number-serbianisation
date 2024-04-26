<?php

/**
 * NumberScale enum file.
 *
 * @package Oblak\SrbUtils
 */

declare(strict_types=1);

namespace Oblak\SrbUtils;

/**
 * Defines the number scale for large numbers
 */
enum NumberScale: string
{
    case Short = 'short';
    case Long  = 'long';

    /**
     * Get the scale for the given group
     *
     * @param  int     $group The group number.
     * @return string
     */
    public function getScale(int $group): string
    {
        $scale = match ($this) {
            self::Short => [
                1  => 'hiljad',
                2  => 'milion',
                3  => 'bilion',
                4  => 'trilion',
                5  => 'kvadrilion',
                6  => 'kvintilion',
                7  => 'sekstilion',
                8  => 'septilion',
                9  => 'oktilion',
                10 => 'nonilion',
            ],
            self::Long => [
                1  => 'hiljad',
                2  => 'milion',
                3  => 'milijard',
                4  => 'bilion',
                5  => 'bilijard',
                6  => 'trilion',
                7  => 'trilijard',
                8  => 'kvadrilion',
                9  => 'kvadrijard',
                10 => 'kvintilion',
            ],
        };

        return $scale[$group] ?? '';
    }

    /**
     * Get the suffixes for the given group
     *
     * @param  int $group The group number.
     * @return array<int, string>
     */
    public function getSuffixes(int $group): array
    {
        /**
         * Variable override.
         *
         * @var array<int, array<int, string>> $suffixes
         */
        $suffixes = match ($this) {
            self::Short =>[
                1  => ['a', 'e', 'a'],
                2  => ['', 'a', 'a'],
                3  => ['', 'a', 'a'],
                4  => ['', 'a', 'a'],
                5  => ['', 'a', 'a'],
                6  => ['', 'a', 'a'],
                7  => ['', 'a', 'a'],
                8  => ['', 'a', 'a'],
                9  => ['', 'a', 'a'],
                10 => ['', 'a', 'a'],
            ],
            self::Long => [
                1  => ['a', 'e', 'a'],
                2  => ['', 'a', 'a'],
                3  => ['a', 'e', 'i'],
                4  => ['', 'a', 'a'],
                5  => ['a', 'e', 'i'],
                6  => ['', 'a', 'a'],
                7  => ['a', 'e', 'i'],
                8  => ['', 'a', 'a'],
                9  => ['a', 'e', 'i'],
                10 => ['', 'a', 'a'],
            ],
        };

        return $suffixes[$group] ?? [ '', '', '' ];
    }

    public function hasAccusative(int $group): bool
    {
        return match ($this) {
            self::Short => 1 === $group,
            self::Long => 1 === $group % 2,
        };
    }
}
