<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: user.proto

namespace App\Protobuf\Generated;

use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\Message;
use Google\Protobuf\Timestamp;

/**
 * Generated from protobuf message <code>intrvproto.User</code>.
 */
class User extends Message
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
     * @var null|\Google\Protobuf\Timestamp
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
     * @var Timestamp
     *                 }
     */
    public function __construct(array $data = null)
    {
        GPBMetadata\User::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 id = 1;</code>.
     */
    public function getId(): int|string
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>int64 id = 1;</code>.
     *
     * @return $this
     */
    public function setId(int|string $var)
    {
        GPBUtil::checkInt64($var);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string username = 2;</code>.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Generated from protobuf field <code>string username = 2;</code>.
     *
     * @return $this
     */
    public function setUsername(string $var)
    {
        GPBUtil::checkString($var, true);
        $this->username = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string email = 3;</code>.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Generated from protobuf field <code>string email = 3;</code>.
     *
     * @return $this
     */
    public function setEmail(string $var)
    {
        GPBUtil::checkString($var, true);
        $this->email = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 4;</code>.
     */
    public function getCreatedAt(): Timestamp
    {
        return $this->created_at;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 4;</code>.
     *
     * @return $this
     */
    public function setCreatedAt(Timestamp $var)
    {
        GPBUtil::checkMessage($var, Timestamp::class);
        $this->created_at = $var;

        return $this;
    }
}
