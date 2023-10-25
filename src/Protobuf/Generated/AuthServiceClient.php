<?php

declare(strict_types=1);

// GENERATED CODE -- DO NOT EDIT!

namespace App\Protobuf\Generated;

class AuthServiceClient extends \Grpc\BaseStub
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
     * @param \App\Protobuf\Generated\SignUpUserInput $argument input argument
     * @param array                                   $metadata metadata
     * @param array                                   $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function SignUpUser(SignUpUserInput $argument, $metadata = [], $options = [])
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
     * @param \App\Protobuf\Generated\SignInUserInput $argument input argument
     * @param array                                   $metadata metadata
     * @param array                                   $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function SignInUser(SignInUserInput $argument, $metadata = [], $options = [])
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
     * @param \App\Protobuf\Generated\VerificationCodeRequest $argument input argument
     * @param array                                           $metadata metadata
     * @param array                                           $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function GetVerificationKey(VerificationCodeRequest $argument, $metadata = [], $options = [])
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
