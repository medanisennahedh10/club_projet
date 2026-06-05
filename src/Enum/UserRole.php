<?php

namespace App\Enum;

enum UserRole: string
{
    case ADMIN       = 'admin';
    case STUDENT     = 'etudiant';
    case RESPONSABLE = 'responsable';
    case PRESIDENT   = 'president';
}