<?php

declare(strict_types=1);

// GENERATED CODE -- DO NOT EDIT!

namespace App\Protobuf\Generated;

class UserServiceClient extends \Grpc\BaseStub
{
    /**
     * @param string        $hostname hostname
     * @param array         $opts     channel options
     * @param \Grpc\Channel $channel  (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null)
    {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \App\Protobuf\Generated\GetUserRequest $argument input argument
     * @param array                                  $metadata metadata
     * @param array                                  $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function GetUserById(
        GetUserRequest $argument,
        $metadata = [],
        $options = []
    ) {
        return $this->_simpleRequest(
            '/intrvproto.UserService/GetUserById',
            $argument,
            ['\App\Protobuf\Generated\UserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \App\Protobuf\Generated\GetUserRequest $argument input argument
     * @param array                                  $metadata metadata
     * @param array                                  $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function GetUserDetails(
        GetUserRequest $argument,
        $metadata = [],
        $options = []
    ) {
        return $this->_simpleRequest(
            '/intrvproto.UserService/GetUserDetails',
            $argument,
            ['\App\Protobuf\Generated\UserDetails', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \App\Protobuf\Generated\VerifyUserRequest $argument input argument
     * @param array                                     $metadata metadata
     * @param array                                     $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function VerifyUser(
        VerifyUserRequest $argument,
        $metadata = [],
        $options = []
    ) {
        return $this->_simpleRequest(
            '/intrvproto.UserService/VerifyUser',
            $argument,
            ['\App\Protobuf\Generated\VerificationResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \App\Protobuf\Generated\ChangePasswordRequest $argument input argument
     * @param array                                         $metadata metadata
     * @param array                                         $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function ChangePassword(
        ChangePasswordRequest $argument,
        $metadata = [],
        $options = []
    ) {
        return $this->_simpleRequest(
            '/intrvproto.UserService/ChangePassword',
            $argument,
            ['\App\Protobuf\Generated\ChangePasswordResponse', 'decode'],
            $metadata,
            $options
        );
    }
}
