<?php

namespace App\Enum;

enum UserRole: string
{
    case ROLE_USER = 'ROLE_USER';

    case ADMIN = 'ROLE_ADMIN';
    case STUDENT = 'etudiant';
    case RESPONSABLE = 'ROLE_RESPONSABLE';
    case PRESIDENT = 'ROLE_PRESIDENT';
}