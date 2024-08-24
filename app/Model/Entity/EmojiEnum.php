<?php

trait RandomEmojiTrait
{
    public static function getRandomEmoji(int $amount): array
    {
        $emojis = array_column(self::cases(), 'value');
        shuffle($emojis);
        return array_slice($emojis, 0, $amount);
    }
}

enum OtherFlagsEmojiEnum: string
{
    use RandomEmojiTrait;
    case CHEQUERED = "🏁";
    case CROSSED = "🎌";
    case WHITE = "🏳️";
    case PIRATE = "🏴‍☠️";
    case GOLF = "⛳";
    case BLACK = "🏴";
    case TRIANGULAR = "🚩";

}

enum OtherGardenEmojiEnum: string
{
    use RandomEmojiTrait;
    case WOLF = "🐺";
    case BUTTERFLY = "🦋";
    case BEE = "🐝";
    case LADYBUG = "🐞";
    case SNAIL = "🐌";
    case SPIDER = "🕷";
    case ANT = "🐜";
    case CATERPILLAR = "🐛";
    case MUSHROOM = "🍄";
    case LEAF = "🍃";
    case HERB = "🌿";
    case GRASS = "🌱";
    case CACTUS = "🌵";
    case EARTHWORM = "🪱";
    case DRAGONFLY = "🪰";
    case SPIDER_WEB = "🕸";
    case BIRD = "🐦";
    case RABBIT = "🐇";
    case FROG = "🐸";
    case LIZARD = "🦎";
    case MOUSE = "🐁";
    case SQUIRREL = "🐿";
    case CHICKEN = "🐔";
    case DUCK = "🦆";
    case WATERING_CAN = "🪣";
    case HOE = "🪓";
    case RAINBOW = "🌈";
    case CLOUD = "☁️";
    case SUN = "☀️";
    case RAIN = "🌧";
}


enum FlowerEmojiEnum: string
{
    use RandomEmojiTrait;
    case SUNFLOWER = "🌻";
    case ROSE = "🌹";
    case LOTUS = "🪷";
    case BOUQUET = "💐";
    case CHERRY = "🌸";
    case ROSETTE = "🏵";
    case HIBISCUS = "🌺";
    case BLOSSOM = "🌼";
    case TULIP = "🌷";
    case HYACINTH = "🪻";
    case WILTED = "🥀";
}
