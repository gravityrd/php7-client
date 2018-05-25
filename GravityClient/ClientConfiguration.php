<?php
declare(strict_types=1);

namespace Gravityrd\GravityClient;

use Gravityrd\GravityClient\Exceptions\ClientConfigurationValidationException;

/**
 * Class ClientConfiguration
 * @package Gravityrd\GravityClient
 */
class ClientConfiguration
{
    /**
     * GravityClientConfiguration constructor.
     * @param string $user
     * @param string $password
     * @param string $remoteUrl
     * @param array $retryMethods
     * @param int $retry
     * @param bool $forwardClientInfo
     */
    public function __construct(
        string $user,
        string $password,
        string $remoteUrl,
        array $retryMethods = ["addUsers", "addItems", "addEvents", "getItemRecommendation"],
        int $retry = 0,
        bool $forwardClientInfo = true
    ) {
        $this->user = $user;
        $this->password = $password;
        $this->remoteUrl = $remoteUrl;
        $this->retryMethods = $retryMethods;
        $this->retry = $retry;
        $this->forwardClientInfo = $forwardClientInfo;
    }

    //region Fields
    /**
     *
     * The user name for the http authenticated connection. Leave it blank in case of
     * connection without authentication.
     *
     * @var string
     */
    protected $user;

    /**
     * The password for the http authenticated connection. Leave it blank in case of
     * connection without authentication.
     *
     * @var string
     */
    protected $password;

    /**
     * Forwards the user-agent, referrer, browser language and client IP to the recommendation engine.
     * Default value is true;
     *
     * @var boolean
     */
    protected $forwardClientInfo;

    /**
     * The URL of the server side interface. It has no default value, must be specified.
     * Strings in the PHP client are always UTF-8 encoded.
     *
     * @var string
     */
    protected $remoteUrl;

    /**
     * The list of method names which should be retried after communication error.
     *
     * @var array(string)
     */
    protected $retryMethods;

    /**
     * If > 1 enables retry for the methods specified in $retryMethods.
     *
     * @var int
     */
    protected $retry;
    //endregion

    //region Getters
    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isForwardClientInfo(): bool
    {
        return $this->forwardClientInfo;
    }

    /**
     * @return string
     */
    public function getRemoteUrl(): string
    {
        return $this->remoteUrl;
    }

    /**
     * @return array
     */
    public function getRetryMethods(): array
    {
        return $this->retryMethods;
    }

    /**
     * @return int
     */
    public function getRetry(): int
    {
        return $this->retry;
    }
    //endregion

    /**
     * Returns the validation errors
     * @return array
     */
    public function validate(): array
    {
        $errors = [];

        if(empty($this->user)){
            $errors['user'] = 'User must be provided!';
        }

        if(empty($this->password)){
            $errors['password'] = 'Password cannot be empty!';
        }

        if(\count($this->retryMethods) > 0 && $this->retry < 0){
            $errors['remoteUrl'] = 'Retry must be a positive integer!';
        }

        if (empty($this->remoteUrl)) {
            $errors['remoteUrl'] = 'Remote URL must be specified.';
        }

        return $errors;
    }

    /**
     * True if valid
     * @return bool
     */
    public function isValid(): bool
    {
        return \count($this->validate()) === 0;
    }

    /**
     * Throws if invalid
     */
    public function validateOrFail()
    {
        $errors = $this->validate();
        if (\count($errors) > 0) {
            throw new ClientConfigurationValidationException($errors);
        }
    }
}