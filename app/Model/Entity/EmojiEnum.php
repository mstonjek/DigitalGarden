<?php



enum OtherGardenEmojiEnum: string
{
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

    // TODO: FIX THIS  

    public static function getRandomEmoji(int $amount): array {
        $emojis = self::cases();
        $randomKeys = array_rand($emojis, $amount);


        return $randomKeys;
    }
}


enum FlowerEmojiEnum: string
{
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
