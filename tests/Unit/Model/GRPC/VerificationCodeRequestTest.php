<?php

declare(strict_types=1);

namespace App\Tests\Unit\Model\GRPC;

use App\Model\GRPC\VerificationCodeRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: VerificationCodeRequest::class)]
final class VerificationCodeRequestTest extends TestCase
{
    public function testGetSetVerificationCode(): void
    {
        $verificationCodeRequest = new VerificationCodeRequest();
        $verificationCode = '123456';

        $verificationCodeRequest->setVerificationCode($verificationCode);

        $this->assertSame($verificationCode, $verificationCodeRequest->getVerificationCode());
    }
}
