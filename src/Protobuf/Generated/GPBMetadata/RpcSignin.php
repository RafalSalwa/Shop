<?php

declare(strict_types=1);

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: rpc_signin.proto

namespace App\Protobuf\Generated\GPBMetadata;

use Google\Protobuf\Internal\DescriptorPool;
use GPBMetadata\Google\Protobuf\Timestamp;

class RpcSignin
{
    public static bool $is_initialized = false;

    public static function initOnce(): void
    {
        $pool = DescriptorPool::getGeneratedPool();

        if (true === static::$is_initialized) {
            return;
        }
        Timestamp::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            '0a91040a107270635f7369676e696e2e70726f746f120a696e74727670726f746f22350a0f5369676e496e55736572496e70757412100a08757365726e616d6518012001280912100a0870617373776f726418022001280922510a125369676e496e55736572526573706f6e7365120e0a0673746174757318012001280912140a0c6163636573735f746f6b656e18022001280912150a0d726566726573685f746f6b656e180320012809224b0a0f5369676e557055736572496e707574120d0a05656d61696c18012001280912100a0870617373776f726418022001280912170a0f70617373776f7264436f6e6669726d180320012809227e0a125369676e557055736572526573706f6e7365120a0a02696418012001280312100a08757365726e616d65180220012809121a0a12766572696669636174696f6e5f746f6b656e180320012809122e0a0a637265617465645f617418042001280b321a2e676f6f676c652e70726f746f6275662e54696d657374616d7022280a17566572696669636174696f6e436f646552657175657374120d0a05656d61696c18012001280922280a18566572696669636174696f6e436f6465526573706f6e7365120c0a04636f6465180120012809423eca02164170705c50726f746f6275665c47656e657261746564e202224170705c50726f746f6275665c47656e6572617465645c4750424d65746164617461620670726f746f33'
        ), true);

        static::$is_initialized = true;
    }
}
