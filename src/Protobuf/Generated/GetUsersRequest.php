<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: user.proto

namespace App\Protobuf\Generated;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.GetUsersRequest</code>
 */
class GetUsersRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>repeated .intrvproto.GetUserRequest users = 1;</code>
     */
    private $users;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \App\Protobuf\Generated\GetUserRequest[]|\Google\Protobuf\Internal\RepeatedField $users
     * }
     */
    public function __construct($data = NULL) {
        \App\Protobuf\Generated\GPBMetadata\User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>repeated .intrvproto.GetUserRequest users = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Generated from protobuf field <code>repeated .intrvproto.GetUserRequest users = 1;</code>
     * @param \App\Protobuf\Generated\GetUserRequest[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setUsers($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::MESSAGE, \App\Protobuf\Generated\GetUserRequest::class);
        $this->users = $arr;

        return $this;
    }

}
