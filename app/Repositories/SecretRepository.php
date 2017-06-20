<?php

namespace App\Repositories;

use App\Models\Secret;

/*
 * Simple repo, without any criteria handling
 */
class SecretRepository
{
    /**
     * @param $data
     * @return Secret
     */
    public function create($data)
    {
        $attributes = array_only($data, ['secret', 'password', 'ip', 'expire_sec', 'expires_at']);

        if (!array_has($attributes, 'expires_at')) {
            $attributes['expires_at'] = date('Y-m-d H:i:s', time() + $attributes['expire_sec']);
        }

        $secret = new Secret();
        $secret->fill($attributes);

        $secret->encrypt($attributes['secret'], $attributes['password']);

        $secret->save();

        return $secret;
    }

    /**
     * Checks if password is valid; if it's not increases attempts counter
     * If attempts counter exceeded it's limit, deletes Secret
     *
     * @param Secret $secret
     * @param string $password
     * @return Secret|bool
     */
    public function decipher(Secret $secret, $password)
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
}