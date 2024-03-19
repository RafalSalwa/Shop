<?php

// GENERATED CODE -- DO NOT EDIT!

namespace App\Protobuf\Service;

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
     * @param \App\Protobuf\Message\SignUpUserInput $argument input argument
     * @param array                                 $metadata metadata
     * @param array                                 $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function SignUpUser(
        \App\Protobuf\Message\SignUpUserInput $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/SignUpUser',
            $argument,
            ['\App\Protobuf\Message\SignUpUserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \App\Protobuf\Message\SignInUserInput $argument input argument
     * @param array                                 $metadata metadata
     * @param array                                 $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function SignInUser(
        \App\Protobuf\Message\SignInUserInput $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/SignInUser',
            $argument,
            ['\App\Protobuf\Message\SignInUserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \App\Protobuf\Message\SignInByCodeUserInput $argument input argument
     * @param array                                       $metadata metadata
     * @param array                                       $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function SignInByCode(
        \App\Protobuf\Message\SignInByCodeUserInput $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/SignInByCode',
            $argument,
            ['\App\Protobuf\Message\SignInUserResponse', 'decode'],
            $metadata,
            $options
        );
    }

    /**
     * @param \App\Protobuf\Message\VerificationCodeRequest $argument input argument
     * @param array                                         $metadata metadata
     * @param array                                         $options  call options
     *
     * @return \Grpc\UnaryCall
     */
    public function GetVerificationKey(
        \App\Protobuf\Message\VerificationCodeRequest $argument,
        $metadata = [],
        $options = []
    )
    {
        return $this->_simpleRequest(
            '/intrvproto.AuthService/GetVerificationKey',
            $argument,
            ['\App\Protobuf\Message\VerificationCodeResponse', 'decode'],
            $metadata,
            $options
        );
    }
}
