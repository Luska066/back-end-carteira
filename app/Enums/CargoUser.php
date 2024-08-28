<?php

namespace App\Enums;

enum CargoUser:string
{
    case MASTER = "MASTER";
    case ADMIN = "ADMIN";
    case STUDENT = "STUDENT";
}
