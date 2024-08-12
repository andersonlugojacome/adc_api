<?php

declare(strict_types=1);

namespace App\Domain\CertificateConsecutives;

use App\Domain\DomainException\DomainRecordNotFoundException;

class CertificateConsecutivesNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The certificate consecutive you requested does not exist.';
}