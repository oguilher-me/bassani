<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case Pending = 'Pending';
    case Paid = 'Paid';
    case Refunded = 'Refunded';
    case Failed = 'Failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendente',
            self::Paid => 'Pago',
            self::Refunded => 'Reembolsado',
            self::Failed => 'Falhou',
        };
    }
}