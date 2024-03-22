<?php
declare(strict_types=1);

namespace App\Tests\Unit\Model\GRPC;

use App\Model\GRPC\VerificationCodeRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(className: VerificationCodeRequest::class)]
class VerificationCodeRequestTest extends TestCase
{
    public function testGetSetVerificationCode(): void
    {
        $request = new VerificationCodeRequest();
        $verificationCode = '123456';

        $request->setVerificationCode($verificationCode);

        $this->assertEquals($verificationCode, $request->getVerificationCode());
    }
}
