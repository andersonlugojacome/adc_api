<?php

declare(strict_types=1);

namespace App\Domain\RemissionConsecutives;

use App\Domain\DomainException\DomainRecordNotFoundException;

class RemissionConsecutivesNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The remission consecutive you requested does not exist.';
}