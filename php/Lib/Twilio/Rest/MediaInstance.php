<?php

/**
 * A single Media object. For the definitive reference, see the `Twilio Media
 * Documentation <https://www.twilio.com/docs/api/rest/media>`_.
 *
 * .. php:attr:: sid
 *
 *    A 34 character string that identifies this object
 *
 * .. php:attr:: account_sid
 *
 *    A 34 character string representing the account that sent the message
 *
 * .. php:attr:: parent_sid
 *
 *    The sid of the message that created this media.
 *
 * .. php:attr:: date_created
 *
 *    The date the message was created
 *
 * .. php:attr:: date_updated
 *
 *    The date the message was updated
 *
 * .. php:attr:: content_type
 *
 *    The content-type of the media.
 */
class Services_Twilio_Rest_MediaInstance extends Services_Twilio_InstanceResource {
    public function __construct($client, $uri) {
        $uri = str_replace('MediaInstance', 'Media', $uri);
        parent::__construct($client, $uri);
    }
}

