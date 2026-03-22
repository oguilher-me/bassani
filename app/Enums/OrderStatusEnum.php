<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case Open = 'Open';
    case InProduction = 'In Production';
    case InTransit = 'In Transit';
    case Delivered = 'Delivered';
    case InAssembly = 'In Assembly';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Aberto',
            self::InProduction => 'Em produção',
            self::InTransit => 'Em Transporte',
            self::Delivered => 'Aguardando Montagem',
            self::InAssembly => 'Em Montagem',
            self::Completed => 'Concluído',
            self::Cancelled => 'Cancelado',
        };
    }
}
