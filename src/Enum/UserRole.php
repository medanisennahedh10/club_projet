<?php

namespace App\Enum;

enum UserRole: string
{
    // الدور الافتراضي الإجباري في سيمفوني
    case ROLE_USER = 'ROLE_USER';
    
    // تعديل بقية الأدوار لتتبع معايير سيمفوني وتطابق الحروف الكبيرة
    case ADMIN       = 'ROLE_ADMIN';
    case STUDENT     = 'ROLE_STUDENT';
    case RESPONSABLE = 'ROLE_RESPONSABLE';
    case PRESIDENT   = 'ROLE_PRESIDENT';
}