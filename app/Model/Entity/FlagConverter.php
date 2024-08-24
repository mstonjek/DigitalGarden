<?php

namespace App\Model\Entity;

class FlagConverter
{
    public function getFlowerFlag(string $country): string
    {
        $countryCode = strtoupper(substr($country, 0, 2));
        $flag = $this->countryCodeToFlag($countryCode);

        if (!$this->isValidFlag($flag)) {
            return \OtherFlagsEmojiEnum::getRandomEmoji(1);
        }

        return $flag;
    }

    public function getFlag(string $country): string
    {
        if (empty($country)) {
            return "🌍";
        }
        $countryCode = strtoupper(substr($country, 0, 2));
        return $this->countryCodeToFlag($countryCode);
    }


    private function countryCodeToFlag(string $countryCode): string
    {
        $flagOffset = 0x1F1E6;
        $asciiOffset = 0x41;

        $firstChar = mb_ord($countryCode[0]) - $asciiOffset + $flagOffset;
        $secondChar = mb_ord($countryCode[1]) - $asciiOffset + $flagOffset;

        return mb_chr($firstChar) . mb_chr($secondChar);
    }

    private function isValidFlag(string $flag): bool
    {
        return preg_match('/^\p{Regional_Indicator}\p{Regional_Indicator}$/u', $flag) === 1;
    }
}