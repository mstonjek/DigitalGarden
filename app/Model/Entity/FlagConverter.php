<?php

declare(strict_types=1);

namespace App\Model\Entity;

class FlagConverter
{
    public function getFlowerFlag(?string $country): string
    {
        $code = self::countryCode($country);
        if ($code === null) {
            return \OtherFlagsEmojiEnum::getRandomEmoji(1)[0];
        }

        return $this->countryCodeToFlag($code);
    }

    public function getFlag(?string $country): string
    {
        $code = self::countryCode($country);
        if ($code === null) {
            return '🌍';
        }

        return $this->countryCodeToFlag($code);
    }

    /**
     * First two ASCII letters of the country name, uppercased.
     * Anything else (null, empty, too short, non-latin) means "unknown".
     */
    private static function countryCode(?string $country): ?string
    {
        if ($country === null || $country === '') {
            return null;
        }

        $code = strtoupper(substr($country, 0, 2));
        return strlen($code) === 2 && ctype_alpha($code) ? $code : null;
    }


    private function countryCodeToFlag(string $countryCode): string
    {
        $flagOffset = 0x1F1E6;
        $asciiOffset = 0x41;

        $firstChar = mb_ord($countryCode[0]) - $asciiOffset + $flagOffset;
        $secondChar = mb_ord($countryCode[1]) - $asciiOffset + $flagOffset;

        return mb_chr($firstChar) . mb_chr($secondChar);
    }

}
