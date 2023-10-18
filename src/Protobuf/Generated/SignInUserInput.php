<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: rpc_signin.proto

namespace App\Protobuf\Generated;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.SignInUserInput</code>.
 */
class SignInUserInput extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string username = 1;</code>.
     *
     * @var string
     */
    protected $username = '';
    /**
     * Generated from protobuf field <code>string password = 2;</code>.
     *
     * @var string
     */
    protected $password = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     * @var string $username
     * @var string $password
     *             }
     */
    public function __construct($data = null)
    {
        \App\Protobuf\Generated\GPBMetadata\RpcSignin::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string username = 1;</code>.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Generated from protobuf field <code>string username = 1;</code>.
     *
     * @param string $var
     *
     * @return $this
     */
    public function setUsername($var)
    {
        GPBUtil::checkString($var, true);
        $this->username = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string password = 2;</code>.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Generated from protobuf field <code>string password = 2;</code>.
     *
     * @param string $var
     *
     * @return $this
     */
    public function setPassword($var)
    {
        GPBUtil::checkString($var, true);
        $this->password = $var;

        return $this;
    }
}
