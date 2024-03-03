<?php
namespace App\Enums;

enum StatusEnum:string {
    case New = 'New';
    case Incomplete = 'Incomplete';
    case Complete = 'Complete';
}
