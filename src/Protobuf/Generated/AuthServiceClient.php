<?php

declare(strict_types=1);

// GENERATED CODE -- DO NOT EDIT!

namespace App\Protobuf\Generated;

use Grpc\BaseStub;

class AuthServiceClient extends BaseStub
{
    /**
     * @param string        $hostname hostname
     * @param array         $opts     channel options
     * @param \Grpc\Channel $channel  (optional) re-use channel object
     */
    public function __construct(string $hostname, array $opts, \Grpc\Channel $channel = null)
    {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param SignUpUserInput $argument input argument
     * @param array           $metadata metadata
     * @param array           $options  call options
     */
    public function SignUpUser(SignUpUserInput $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/SignUpUser',
            $argument,
            ['\App\Protobuf\Generated\SignUpUserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param SignInUserInput $argument input argument
     * @param array           $metadata metadata
     * @param array           $options  call options
     */
    public function SignInUser(SignInUserInput $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/SignInUser',
            $argument,
            ['\App\Protobuf\Generated\SignInUserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param VerificationCodeRequest $argument input argument
     * @param array                   $metadata metadata
     * @param array                   $options  call options
     */
    public function GetVerificationKey(VerificationCodeRequest $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/GetVerificationKey',
            $argument,
            ['\App\Protobuf\Generated\VerificationCodeResponse', 'decode'],
            $metadata,
            $options
        );
    }
}
