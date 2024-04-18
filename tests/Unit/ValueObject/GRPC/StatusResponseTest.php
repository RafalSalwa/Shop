<?php

declare(strict_types=1);

namespace App\Tests\ValueObject\GRPC;

use App\ValueObject\GRPC\StatusResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;
use const Grpc\STATUS_OK;

#[CoversClass(className: StatusResponse::class)]
final class StatusResponseTest extends TestCase
{
    public function testConstructor(): void
    {
        // Create a stdClass object to simulate GRPC response data
        $grpcResponse = new stdClass();
        $grpcResponse->code = 200;
        $grpcResponse->details = 'OK';

        // Create a StatusResponse instance using the constructor
        $statusResponse = new StatusResponse($grpcResponse);

        // Assert that the object is created successfully
        $this->assertInstanceOf(StatusResponse::class, $statusResponse);
        $this->assertSame(200, $statusResponse->getCode());
        $this->assertSame('OK', $statusResponse->getDetails());
    }

    public function testIsOk(): void
    {
        // Create a stdClass object for a successful GRPC response
        $grpcResponseOk = new stdClass();
        $grpcResponseOk->code = STATUS_OK;
        $grpcResponseOk->details = 'Success';

        // Create a StatusResponse instance with STATUS_OK code
        $statusResponseOk = new StatusResponse($grpcResponseOk);

        // Assert that isOk() method returns true for STATUS_OK code
        $this->assertTrue($statusResponseOk->isOk());

        // Create a stdClass object for a failed GRPC response
        $grpcResponseError = new stdClass();
        $grpcResponseError->code = 404;
        $grpcResponseError->details = 'Not Found';

        // Create a StatusResponse instance with non-STATUS_OK code
        $statusResponseError = new StatusResponse($grpcResponseError);

        // Assert that isOk() method returns false for non-STATUS_OK code
        $this->assertFalse($statusResponseError->isOk());
    }
}
