<?php
// GENERATED CODE -- DO NOT EDIT!

namespace App\grpc\php\Intrvproto;

/**
 */
class AuthServiceClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \App\grpc\php\Intrvproto\SignInUserInput $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     * @return \Grpc\UnaryCall
     */
    public function SignInUser(\App\grpc\php\Intrvproto\SignInUserInput $argument,
                                                                        $metadata = [], $options = []) {
        return $this->_simpleRequest('/intrvproto.AuthService/SignInUser',
        $argument,
        ['\Intrvproto\SignInUserResponse', 'decode'],
        $metadata, $options);
    }

}
