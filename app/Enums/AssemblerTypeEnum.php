<?php

namespace App\Enums;

enum AssemblerTypeEnum: string
{
    case CONTRACTED = 'contracted';
    case OUTSOURCED = 'outsourced';

    public function label(): string
    {
        return match ($this) {
            self::CONTRACTED => 'Contratado',
            self::OUTSOURCED => 'Terceirizado',
        };
    }
}