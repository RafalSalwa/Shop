<?php

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: rpc_signin.proto

namespace App\Protobuf\GPBMetadata;

class RpcSignin
{
    public static $is_initialized = false;

    public static function initOnce()
    {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
            return;
        }
        \GPBMetadata\Google\Protobuf\Timestamp::initOnce();
        $pool->internalAddGeneratedFile(hex2bin(
            '0af2040a107270635f7369676e696e2e70726f746f120a696e74727670726f746f22440a0f5369676e496e55736572496e70757412100a08757365726e616d65180120012809120d0a05656d61696c18022001280912100a0870617373776f726418032001280922380a155369676e496e4279436f646555736572496e707574120d0a05656d61696c18012001280912100a0861757468436f646518022001280922410a125369676e496e55736572526573706f6e736512140a0c6163636573735f746f6b656e18012001280912150a0d726566726573685f746f6b656e180220012809224b0a0f5369676e557055736572496e707574120d0a05656d61696c18012001280912100a0870617373776f726418022001280912170a0f70617373776f7264436f6e6669726d180320012809227e0a125369676e557055736572526573706f6e7365120a0a02696418012001280312100a08757365726e616d65180220012809121a0a12766572696669636174696f6e5f746f6b656e180320012809122e0a0a637265617465645f617418042001280b321a2e676f6f676c652e70726f746f6275662e54696d657374616d7022280a17566572696669636174696f6e436f646552657175657374120d0a05656d61696c18012001280922280a18566572696669636174696f6e436f6465526573706f6e7365120c0a04636f646518012001280942665a326769746875622e636f6d2f526166616c53616c77612f696e746572766965772d6170702d7372762f696e74727670726f746fca02144170705c50726f746f6275665c4d657373616765e202184170705c50726f746f6275665c4750424d65746164617461620670726f746f33'
        ), true);

        static::$is_initialized = true;
    }
}
