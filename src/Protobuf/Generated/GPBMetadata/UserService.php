<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: user_service.proto

namespace App\Protobuf\Generated\GPBMetadata;

class UserService
{
    public static bool $is_initialized = false;

    public static function initOnce()
    {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized === true) {
            return;
        }
        \App\Protobuf\Generated\GPBMetadata\User::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            '0ab4030a12757365725f736572766963652e70726f746f120a696e74727670726f746f32c9020a0b557365725365727669636512450a0b4765745573657242794964121a2e696e74727670726f746f2e47657455736572526571756573741a182e696e74727670726f746f2e55736572526573706f6e7365220012470a0e4765745573657244657461696c73121a2e696e74727670726f746f2e47657455736572526571756573741a172e696e74727670726f746f2e5573657244657461696c732200124f0a0a56657269667955736572121d2e696e74727670726f746f2e56657269667955736572526571756573741a202e696e74727670726f746f2e566572696669636174696f6e526573706f6e7365220012590a0e4368616e676550617373776f726412212e696e74727670726f746f2e4368616e676550617373776f7264526571756573741a222e696e74727670726f746f2e4368616e676550617373776f7264526573706f6e73652200423eca02164170705c50726f746f6275665c47656e657261746564e202224170705c50726f746f6275665c47656e6572617465645c4750424d65746164617461620670726f746f33'
        ), true);

        static::$is_initialized = true;
    }
}
