<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'Pending';
    case Paid = 'Paid';
    case Contested = 'Contested';
    case Cancelled = 'Cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Paid => 'Paga',
            self::Contested => 'Contestada',
            self::Cancelled => 'Cancelada',
        };
    }
}