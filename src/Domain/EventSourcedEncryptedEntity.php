<?php namespace StraTDeS\SharedKernel\Domain;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;

class EventSourcedEncryptedEntity extends EventSourcedEntity
{
    /** @var string|null */
    protected $encryptedKey;

    /**
     * EventSourcedEncryptedEntity constructor.
     * @param Id $id
     * @throws EnvironmentIsBrokenException
     */
    public function __construct(Id $id)
    {
        parent::__construct($id);

        if ($this->encryptedKey === null) {
            $this->encryptedKey = Key::createNewRandomKey()->saveToAsciiSafeString();
        }
    }

    /**
     * @param string $string
     * @param string $encryptedKey
     * @return string
     */
    public static function encrypt(string $string, string $encryptedKey = null): string
    {
        if ($encryptedKey === null) {
            return $string;
        }

        try {
            $encryptedString = Crypto::encryptWithPassword($string, $encryptedKey);
        } catch(EnvironmentIsBrokenException $e) {
            return $string;
        }

        return $encryptedString;
    }

    /**
     * @param string $encryptedString
     * @param string $encryptedKey
     * @return string
     */
    public static function decrypt(string $encryptedString, string $encryptedKey = null): string
    {
        if ($encryptedKey === null) {
            return $encryptedString;
        }

        try {
            $string = Crypto::decryptWithPassword($encryptedString, $encryptedKey);
        } catch(WrongKeyOrModifiedCiphertextException $e) {
            return $encryptedString;
        } catch(EnvironmentIsBrokenException $e) {
            return $encryptedString;
        }

        return $string;
    }

    public function getEncryptedKey(): ?string
    {
        return $this->encryptedKey;
    }
}