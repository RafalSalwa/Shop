<?php

declare(strict_types=1);

namespace App\Model;

use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

final class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait;
    use EntityTrait;
    use TokenEntityTrait;

    public function __construct(string $privateJWTKey, string $privateJWTKeyPassphrase)
    {
        $cryptKey = new CryptKey($privateJWTKey, $privateJWTKeyPassphrase);
        $this->setPrivateKey($cryptKey);
    }
}
