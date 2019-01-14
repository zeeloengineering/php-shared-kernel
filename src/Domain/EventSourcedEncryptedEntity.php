<?php namespace StraTDeS\SharedKernel\Domain;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\EnvironmentIsBrokenException;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;

class EventSourcedEncryptedEntity extends EventSourcedEntity
{
    /** @var string */
    private $encryptedKey = null;

    /**
     * EventSourcedEncryptedEntity constructor.
     * @param Id $id
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     */
    public function __construct(Id $id)
    {
        parent::__construct($id);
        $this->encryptedKey = Key::createNewRandomKey();
    }

    /**
     * @param $string
     * @return string
     */
    public function encrypt($string): string {
        try {
            $encryptedString = Crypto::encryptWithPassword($string, $this->encryptedKey);
        } catch(EnvironmentIsBrokenException $e) {
            return $string;
        }
        return $encryptedString;
    }

    /**
     * @param $encryptedString
     * @return string
     */
    public function decrypt($encryptedString): string {
        try {
            $string = Crypto::decryptWithPassword($encryptedString, $this->encryptedKey);
        } catch(WrongKeyOrModifiedCiphertextException $e) {
            return $encryptedString;
        } catch(EnvironmentIsBrokenException $e) {
            return $encryptedString;
        }
    }
}