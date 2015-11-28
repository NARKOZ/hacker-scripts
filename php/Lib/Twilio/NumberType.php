<?php

class Services_Twilio_NumberType extends Services_Twilio_ListResource
{
    public function getResourceName($camelized = false) {
        $this->instance_name = 'Services_Twilio_Rest_IncomingPhoneNumber';
        return $camelized ? 'IncomingPhoneNumbers' : 'incoming_phone_numbers';
    }

    /**
     * Purchase a new phone number.
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $marlosBurner = '+14105551234';
     *      $client->account->incoming_phone_numbers->local->purchase($marlosBurner);
     *
     * :param string $phone_number: The phone number to purchase
     * :param array $params: An optional array of parameters to pass along with
     *      the request (to configure the phone number)
     */
    public function purchase($phone_number, array $params = array()) {
        $postParams = array(
            'PhoneNumber' => $phone_number
        );
        return $this->create($postParams + $params);
    }

    public function create(array $params = array()) {
        return parent::_create($params);
    }

}
