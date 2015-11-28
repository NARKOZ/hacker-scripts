<?php

class Services_Twilio_Rest_Messages extends Services_Twilio_ListResource {

    /**
     * Create a new Message instance
     *
     * Example usage:
     *
     * .. code-block:: php
     *
     *      $client->account->messages->create(array(
     *          "Body" => "foo",
     *          "From" => "+14105551234",
     *          "To" => "+14105556789",
     *      ));
     *
     * :param array $params: a single array of parameters which is serialized and
     *      sent directly to the Twilio API. You may find it easier to use the
     *      sendMessage helper instead of this function.
     *
     */
    public function create($params = array()) {
        return parent::_create($params);
    }

    /**
     * Send a message
     *
     * .. code-block:: php
     *
     *      $client = new Services_Twilio('AC123', '123');
     *      $message = $client->account->messages->sendMessage(
     *          '+14105551234', // From a Twilio number in your account
     *          '+14105556789', // Text any number
     *          'Come at the king, you best not miss.'   // Message body (if any)
     *          array('https://demo.twilio.com/owl.png'),   // An array of MediaUrls
     *      );
     *
     * :param string $from: the from number for the message, this must be a
     *      number you purchased from Twilio
     * :param string $to: the message recipient's phone number
     * :param $mediaUrls: the URLs of images to send in this MMS
     * :type $mediaUrls: null (don't include media), a single URL, or an array
     *      of URLs to send as media with this message
     * :param string $body: the text to include along with this MMS
     * :param array $params: Any additional params (callback, etc) you'd like to
     *      send with this request, these are serialized and sent as POST
     *      parameters
     *
     * :return: The created :class:`Services_Twilio_Rest_Message`
     * :raises: :class:`Services_Twilio_RestException`
     *      An exception if the parameters are invalid (for example, the from
     *      number is not a Twilio number registered to your account, or is
     *      unable to send MMS)
     */
    public function sendMessage($from, $to, $body = null, $mediaUrls = null,
        $params = array()
    ) {
        $postParams = array(
            'From' => $from,
            'To' => $to,
        );
        // When the request is made, this will get serialized into MediaUrl=a&MediaUrl=b
        if (!is_null($mediaUrls)) {
            $postParams['MediaUrl'] = $mediaUrls;
        }
        if (!is_null($body)) {
            $postParams['Body'] = $body;
        }
        return self::create($postParams + $params);
    }
}
