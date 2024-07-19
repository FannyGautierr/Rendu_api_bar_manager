<?php
namespace App\Enum;

enum OrderStatus: string
{
    case Ready = 'ready';
    case Paid = 'paid';
    case In_progress = 'in_progress';
}