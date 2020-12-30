<?php

declare(strict_types=1);

namespace App\Config;

class EmailConfig
{
    public const EMAIL_FROM = 'test@test.com';
    public const EMAIL_TO = 'test@test.com';
    public const EMAIL_SUBJECT = 'New product was added';
    public const MESSAGE_TEMPLATE = 'Product %s (%s) was added in our shop';
}