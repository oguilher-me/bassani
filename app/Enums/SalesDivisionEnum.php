<?php

namespace App\Enums;

enum SalesDivisionEnum: string
{
    case Retail = 'Varejo';
    case Wholesale = 'Atacado';
    case Corporate = 'Corporativo';
    case Export = 'Exportação';
}
