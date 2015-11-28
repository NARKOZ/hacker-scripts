<?php

/**
 * An exception talking to the Twilio API. This is thrown whenever the Twilio
 * API returns a 400 or 500-level exception.
 *
 * :param int $status: the HTTP status for the exception
 * :param string $message: a human-readable error message for the exception
 * :param int $code: a Twilio-specific error code for the exception
 * :param string $info: a link to more information
 */
class Services_Twilio_RestException extends Exception {

    /**
     * The HTTP status for the exception.
     */
    protected $status;

    /**
     * A URL to get more information about the error. This is not always
     * available
     */
    protected $info;

    public function __construct($status, $message, $code = 0, $info = '') {
        $this->status = $status;
        $this->info = $info;
        parent::__construct($message, $code);
    }

    /**
     * Get the HTTP status code
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Get a link to more information
     */
    public function getInfo() {
        return $this->info;
    }
}
