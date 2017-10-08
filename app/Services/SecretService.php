<?php

namespace App\Services;

use App\Models\Contracts\SecretInterface;
use App\Models\Redis\Secret as SecretRedis;
use App\Models\Postgres\Secret as SecretPostgres;

/*
 * what a name
 */
class SecretService
{
    const STORAGE_POSTGRES = 'POSTGRES';
    const STORAGE_REDIS = 'REDIS';

    public function create(array $data): SecretInterface
    {
        $attributes = array_only($data, ['secret', 'password', 'ip', 'expire_sec', 'expires_at']);

        if (!array_has($attributes, 'expires_at')) {
            $attributes['expires_at'] = date('Y-m-d H:i:s', time() + $attributes['expire_sec']);
        }

        // TODO: refactor to StorageProvider or smth like taht
        switch ($this->getStorageType()) {
            case self::STORAGE_POSTGRES:
                $secret = new SecretPostgres();
                break;
            case self::STORAGE_REDIS:
                $secret = new SecretRedis();
                break;
            default:
                throw new \LogicException('Cannot determine type of storage');
        }

        $secret->fill($attributes);

        $secret->encrypt($attributes['secret'], $attributes['password']);

        $secret->save();

        return $secret;
    }

    /**
     * Check if password is valid; if it's not increase attempts counter
     * If attempts counter exceeded its limit, delete the secret
     */
    public function decipher(SecretInterface $secret, string $password)
    {
        $decrypted = $secret->decrypt($password);

        /*
         * decryption went well, delete secret
         */
        if ($decrypted !== false) {
            $secret->delete();

            return $decrypted;
        }

        $secret->increaseAttempts();

        if ($secret->needsDeletion()) {
            $secret->delete();
        } else {
            $secret->save();
        }

        return false;
    }

    private function getStorageType(): string
    {
        return env('STORAGE_TYPE');
    }
}