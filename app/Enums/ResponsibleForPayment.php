<?php

namespace App\Enums;

enum ResponsibleForPayment: string
{
    case Company = 'Company';
    case Driver = 'Driver';
    case Shared = 'Shared';

    public function getLabel(): string
    {
        return match ($this) {
            self::Company => 'Empresa',
            self::Driver => 'Motorista',
            self::Shared => 'Compartilhado',
        };
    }
}