<?php

namespace App\Models\Redis;

use Ramsey\Uuid\Uuid;

/**
 * @property string $ciphertext
 * @property int $attempts
 * @property int $expires_at
 *
 */
class Secret extends AbstractModel
{
    const CIPHER_METHOD = 'aes-256-cbc';

    const ATTEMPTS_MAX = 3;

    public $table = 'secrets';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    public $timestamps = true;

    protected $fillable = ['expires_at', 'ip'];

    protected $prefix = 'secret';

    protected function getNextIncrementingKey()
    {
        return Uuid::uuid4()->toString();
    }

    public function encrypt($data, $password)
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER_METHOD));
        $encrypted = openssl_encrypt($data, self::CIPHER_METHOD, $password, 0, $iv);
        $this->ciphertext = $encrypted . ':' . base64_encode($iv);
    }

    public function decrypt($password)
    {
        list($encrypted, $iv_b64) = explode(':', $this->ciphertext);
        $iv = base64_decode($iv_b64);
        return openssl_decrypt($encrypted, self::CIPHER_METHOD, $password, 0, $iv);
    }

    public function increaseAttempts()
    {
        $this->attempts += 1;
    }

    public function getAttemptsLeft()
    {
        $left = self::ATTEMPTS_MAX - $this->attempts;

        return max($left, 0);
    }

    public function needsDeletion()
    {
        return $this->attempts >= self::ATTEMPTS_MAX || $this->expires_at < time();
    }
}