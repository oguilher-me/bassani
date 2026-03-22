<?php

namespace App\Enums;

enum PaymentMethodEnum: string
{
    case CreditCard = 'Credit Card';
    case DebitCard = 'Debit Card';
    case BankTransfer = 'Bank Transfer';
    case Cash = 'Cash';
    case Pix = 'Pix';

    public function label(): string
    {
        return match ($this) {
            self::CreditCard => 'Cartão de Crédito',
            self::DebitCard => 'Cartão de Débito',
            self::BankTransfer => 'Transferência Bancária',
            self::Cash => 'Dinheiro',
            self::Pix => 'Pix',
        };
    }
}