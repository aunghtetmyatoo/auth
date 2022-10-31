<?php

namespace App\Enums;

enum OtpAction: string
{
    case Register =  'register';
    case Login = 'login';
    case UpdatePasscode = 'update passcode';
}
