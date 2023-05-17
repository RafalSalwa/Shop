<?php
// GENERATED CODE -- DO NOT EDIT!

namespace App\grpc\php\Intrvproto;

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
     * @param \App\grpc\php\Intrvproto\GetUserRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function GetUserById(\App\grpc\php\Intrvproto\GetUserRequest $argument,
                                                                        $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.UserService/GetUserById',
        $argument,
        ['\Intrvproto\UserResponse', 'decode'],
        $metadata, $options);
    }

}
