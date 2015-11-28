<?php

/**
 * For more information, see the
 * `IncomingPhoneNumbers API Resource
 * <http://www.twilio.com/docs/api/rest/incoming-phone-numbers#local>`_
 * documentation at twilio.com.
 */
class Services_Twilio_Rest_IncomingPhoneNumbers extends Services_Twilio_ListResource {
    function init($client, $uri) {
        $this->setupSubresources(
            'local',
            'toll_free',
            'mobile'
        );
    }

    function create(array $params = array()) {
        return parent::_create($params);
    }

    function getList($type, array $params = array())
    {
        return $this->client->retrieveData($this->uri . "/$type", $params);
    }

    /**
     * Return a phone number instance from its E.164 representation. If more
     * than one number matches the search string, returns the first one.
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $number = $client->account->incoming_phone_numbers->getNumber('+14105551234');
     *      echo $number->sid;
     *
     * :param string $number: The number in E.164 format, eg "+684105551234"
     * :return:  A :php:class:`Services_Twilio_Rest_IncomingPhoneNumber` object, or null
     * :raises: a A :php:class:`Services_Twilio_RestException` if the number is
     *      invalid, not provided in E.164 format or for any other API exception.
     */
    public function getNumber($number) {
        $page = $this->getPage(0, 1, array(
            'PhoneNumber' => $number
        ));
        $items = $page->getItems();
        if (is_null($items) || empty($items)) {
            return null;
        }
        return $items[0];
    }
}

class Services_Twilio_Rest_Local extends Services_Twilio_NumberType { }

class Services_Twilio_Rest_Mobile extends Services_Twilio_NumberType { }

class Services_Twilio_Rest_TollFree extends Services_Twilio_NumberType { }
