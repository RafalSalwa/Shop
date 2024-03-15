<?php
// GENERATED CODE -- DO NOT EDIT!

namespace App\Protobuf\Service;

/**
 */
class UserServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \App\Protobuf\Message\StringValue $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function CheckUserExists(\App\Protobuf\Message\StringValue $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/CheckUserExists',
        $argument,
        ['\App\Protobuf\Message\BoolValue', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \App\Protobuf\Message\GetUserRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetUserById(\App\Protobuf\Message\GetUserRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/GetUserById',
        $argument,
        ['\App\Protobuf\Message\UserDetails', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \App\Protobuf\Message\GetUserRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetUserDetails(\App\Protobuf\Message\GetUserRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/GetUserDetails',
        $argument,
        ['\App\Protobuf\Message\UserDetails', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \App\Protobuf\Message\VerifyUserRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function VerifyUser(\App\Protobuf\Message\VerifyUserRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/VerifyUser',
        $argument,
        ['\App\Protobuf\Message\VerificationResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \App\Protobuf\Message\ChangePasswordRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function ChangePassword(\App\Protobuf\Message\ChangePasswordRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/ChangePassword',
        $argument,
        ['\App\Protobuf\Message\ChangePasswordResponse', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \App\Protobuf\Message\GetUserSignInRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetUser(\App\Protobuf\Message\GetUserSignInRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/GetUser',
        $argument,
        ['\App\Protobuf\Message\UserDetails', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \App\Protobuf\Message\VerificationCode $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetUserByCode(\App\Protobuf\Message\VerificationCode $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/GetUserByCode',
        $argument,
        ['\App\Protobuf\Message\UserDetails', 'decode'],
        $metadata, $options);
    }

}
