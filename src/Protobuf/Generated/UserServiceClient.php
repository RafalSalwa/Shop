<?php

declare(strict_types=1);

// GENERATED CODE -- DO NOT EDIT!

namespace App\Protobuf\Generated;

use Grpc\BaseStub;

class UserServiceClient extends BaseStub
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
     * @param GetUserRequest $argument input argument
     * @param array          $metadata metadata
     * @param array          $options  call options
     */
    public function GetUserById(GetUserRequest $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.UserService/GetUserById',
            $argument,
            ['\App\Protobuf\Generated\UserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param GetUserRequest $argument input argument
     * @param array          $metadata metadata
     * @param array          $options  call options
     */
    public function GetUserDetails(GetUserRequest $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.UserService/GetUserDetails',
            $argument,
            ['\App\Protobuf\Generated\UserDetails', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param VerifyUserRequest $argument input argument
     * @param array             $metadata metadata
     * @param array             $options  call options
     */
    public function VerifyUser(VerifyUserRequest $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.UserService/VerifyUser',
            $argument,
            ['\App\Protobuf\Generated\VerificationResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param ChangePasswordRequest $argument input argument
     * @param array                 $metadata metadata
     * @param array                 $options  call options
     */
    public function ChangePassword(ChangePasswordRequest $argument, array $metadata = [], array $options = []): \Grpc\UnaryCall
    {
        return $this->_simpleRequest(
            '/intrvproto.UserService/ChangePassword',
            $argument,
            ['\App\Protobuf\Generated\ChangePasswordResponse', 'decode'],
            $metadata,
            $options
        );
    }
}
