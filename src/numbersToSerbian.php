<?php

namespace Oblak\SrbUtils;

class NumbersToSerbianWords
{
    /**
     * Digits to words mapping
     *
     * @var array<int, string>
     */
    private static $digits = [
        0    => 'nula',
        1    => 'jedan',
        2    => 'dva',
        3    => 'tri',
        4    => 'četiri',
        5    => 'pet',
        6    => 'šest',
        7    => 'sedam',
        8    => 'osam',
        9    => 'devet',
        10   => 'deset',
        11   => 'jedanaest',
        12   => 'dvanaest',
        13   => 'trinaest',
        14   => 'četrnaest',
        15   => 'petnaest',
        16   => 'šesnaest',
        17   => 'sedamnaest',
        18   => 'osamnaest',
        19   => 'devetnaest',
        40   => 'četrdeset',
        50   => 'pedeset',
        60   => 'šezdeset',
        90   => 'devedeset',
        100  => 'sto',
        200  => 'dvesta',
        300  => 'trista',
        1000 => 'hiljadu',
    ];

    public function __construct(
        /**
         * Which scale to use for large numbers.
         *
         * @var NumberScale $scale
         */
        private NumberScale $scale = NumberScale::Long,
        private bool $useAccusative = false,
    ) {
    }

    /**
     * Get the scale for large numbers
     *
     * @return NumbersToSerbianWords
     */
    public function useScale(NumberScale $scale): NumbersToSerbianWords
    {
        $this->scale = $scale;

        return $this;
    }

    public function useAccusative(bool $useAccusative = true): NumbersToSerbianWords
    {
        $this->useAccusative = $useAccusative;

        return $this;
    }

    /**
     * Converts the number to an array of words in Serbian
     *
     * @param  int|string|float|\Stringable $number The number to convert.
     * @return array<int, string>
     */
    public function toWordArray(int|string|float|\Stringable $number): array
    {
        if (\is_object($number)) {
            $number = (string) $number;
        }

        $formatted = [];
        $separated = $this->separateDigits((int)$number);
        $groups    = \count($separated) - 1;

        if ([ 0 ] === $separated) {
            return [ self::$digits[0] ];
        }

        foreach ($separated as $index => $grouped) :
            $formatted[] = $this->convertSections($grouped, $groups - $index);
        endforeach;

        return \array_filter($formatted);
    }

    /**
     * Converts the number to a string representation in Serbian
     *
     * @param  int|string|float|\Stringable $number The number to convert.
     * @return string
     */
    public function toWordString(int|string|float|\Stringable $number): string
    {
        return \implode(' ', $this->toWordArray($number));
    }

    /**
     * Separates the number into groups of 3 digits
     *
     * @param  int $number The number to separate.
     * @return array<int, int>
     */
    private function separateDigits(int $number): array
    {
        if (0 === (int) $number) {
            return [ 0 ];
        }

        $current = $number % 1000;
        $remainder = \intdiv($number, 1000);

        if (0 === $remainder) {
            return [ $current ];
        }

        $separated = $this->separateDigits($remainder);
        $separated[] = $current;

        return $separated;
    }

    private function convertSections(int $number, int $group): string
    {
        if ($this->withAccusative($number, $group)) {
            return $this->formatGroup($number, $group);
        }


        $numberString = $this->convertDigits($number);
        $numberString = $this->maybeGenderize($numberString, $group);

        $numberString .= ' ' . $this->formatGroup($number, $group);

        return \trim($numberString);
    }

    private function withAccusative(int $number, int $group): bool
    {
        if (0 === $number) {
            return true;
        }

        if (!$this->useAccusative) {
            return false;
        }


        return 1 === $number && $this->scale->hasAccusative($group);
    }

    private function convertDigits(int $number): string
    {
        $numString = '';
        $divisors = [
            100 => 'sto',
            10  => 'deset',
            1   => '',
        ];

        foreach ($divisors as $divisor => $suffix) {
            if (0 === $number) {
                break;
            }

            $numString .= $this->convertDigit($number, $divisor, $suffix);
        }

        return $numString;
    }

    private function convertDigit(int &$number, int $divisor, string $suffix): string
    {
        if (isset(self::$digits[$number])) {
            $result = self::$digits[$number];
            $number = 0; // Reset number after processing to avoid further handling.
            return $result;
        }

        $remainder = $number % $divisor;
        $processedNumber = $number - $remainder;
        $number = $remainder; // Update the number to be the remainder for further processing.

        if ($processedNumber > 0 && isset(self::$digits[$processedNumber])) {
            return self::$digits[$processedNumber] . ' ';
        }

        $digit = \intdiv($processedNumber, $divisor);
        return (self::$digits[$digit] ?? '') . $suffix . ' ';
    }

    private function maybeGenderize(string $numberString, int $group): string
    {
        $replacement = [
            'jedan' => 'jedna',
            'dva'   => 'dve',
        ];

        if (1 === $group) {
            return \strtr($numberString, $replacement);
        }

        if (NumberScale::Long !== $this->scale) {
            return $numberString;
        }

        return 1 === $group % 2 ? \strtr($numberString, $replacement) : $numberString;
    }

    private function formatGroup(int $num, int $group): string
    {
        if (0 === $num || 0 === $group) {
            return '';
        }

        $groupString = $this->scale->getScale($group);
        $suffixes    = $this->scale->getSuffixes($group);

        if ($this->withAccusative($num, $group)) {
            $suffixes[0] = 'u';
        }


        $suffix = match (true) {
            11 === $num % 100 => $suffixes[2],
            1 === $num % 10   => $suffixes[0],
            $num % 10 < 5     => $suffixes[1],
            default           => $suffixes[2],
        };

        return "{$groupString}{$suffix}";
    }
}
