<?php

namespace App\Enums;

enum DeliveryStatusEnum: string
{
    case Pending = 'Pendente';
    case InTransit = 'Em Trânsito';
    case Delivered = 'Entregue';
    case Returned = 'Devolvido';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::InTransit => 'Em Trânsito',
            self::Delivered => 'Entregue',
            self::Returned => 'Devolvido',
        };
    }
}
