<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: user.proto

namespace App\Protobuf\Generated;

use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>intrvproto.User</code>.
 */
class User extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 id = 1;</code>.
     *
     * @var int|string
     */
    protected $id = 0;

    /**
     * Generated from protobuf field <code>string username = 2;</code>.
     *
     * @var string
     */
    protected $username = '';

    /**
     * Generated from protobuf field <code>string email = 3;</code>.
     *
     * @var string
     */
    protected $email = '';

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 4;</code>.
     *
     * @var \Google\Protobuf\Timestamp|null
     */
    protected $created_at;

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     * @var int|string
     * @var string
     * @var string
     * @var \Google\Protobuf\Timestamp
     *                                 }
     */
    public function __construct($data = null)
    {
        \App\Protobuf\Generated\GPBMetadata\User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 id = 1;</code>.
     *
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>int64 id = 1;</code>.
     *
     * @param int|string $var
     *
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkInt64($var);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string username = 2;</code>.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Generated from protobuf field <code>string username = 2;</code>.
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
     * Generated from protobuf field <code>string email = 3;</code>.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Generated from protobuf field <code>string email = 3;</code>.
     *
     * @param string $var
     *
     * @return $this
     */
    public function setEmail($var)
    {
        GPBUtil::checkString($var, true);
        $this->email = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 4;</code>.
     *
     * @return \Google\Protobuf\Timestamp
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 4;</code>.
     *
     * @param \Google\Protobuf\Timestamp $var
     *
     * @return $this
     */
    public function setCreatedAt($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->created_at = $var;

        return $this;
    }
}
