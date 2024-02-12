<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: user.proto

namespace App\Protobuf\Generated;

use App\Protobuf\Generated\GPBMetadata\User;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\Message;

/**
 * Generated from protobuf message <code>intrvproto.VerifyUserRequest</code>.
 */
class VerifyUserRequest extends Message
{
    /**
     * Generated from protobuf field <code>string code = 1;</code>.
     *
     * @var string
     */
    protected $code = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     * @var string
     *             }
     */
    public function __construct($data = null)
    {
        User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string code = 1;</code>.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Generated from protobuf field <code>string code = 1;</code>.
     *
     * @param string $var
     *
     * @return $this
     */
    public function setCode($var)
    {
        GPBUtil::checkString($var, true);
        $this->code = $var;

        return $this;
    }
}
