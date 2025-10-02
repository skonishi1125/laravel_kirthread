<?php

namespace App\Enums\Rpg;

enum FieldData: int
{
    case Grassland = 1;
    case Desert = 2;
    case Volcano = 3;
    case Coast = 4;
    case WetFog = 5;
    case IceAndSnow = 6;
    case NightForest = 7;
    case DecayedFarmland = 8;
    case CastleTown = 9;
    case AncientCastle = 10;
    case VastExpanse = 11;

    public function label(): string
    {
        return match ($this) {
            self::Grassland => '草原',
            self::Desert => '砂漠',
            self::Volcano => '火山',
            self::Coast => '海岸',
            self::WetFog => '湿霧の地',
            self::IceAndSnow => '氷雪地帯',
            self::NightForest => '常夜の森',
            self::DecayedFarmland => '退廃した耕作地',
            self::CastleTown => '門前雀羅の城下街',
            self::AncientCastle => '古城',
            self::VastExpanse => '茫洋の地'
        };
    }

    public function image_path(): string
    {
        return match ($this) {
            self::Grassland => 'grassland.png',
            self::Desert => 'desert.png',
            self::Volcano => 'volcano.png',
            self::Coast => 'coast.png',
            self::WetFog => 'wetfog.png',
            self::IceAndSnow => 'iceandsnow.png',
            self::NightForest => 'nightforest.png',
            self::DecayedFarmland => 'decayedfarmland.png',
            self::CastleTown => 'castletown.png',
            self::AncientCastle => 'ancientcastle.png',
            self::VastExpanse => 'vastexpanse.png',
        };
    }
}
