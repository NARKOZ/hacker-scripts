<?php

/**
 * A single Message
 *
 * .. php:attr:: date_created
 *
 *    The date the message was created
 *
 * .. php:attr:: date_updated
 *
 *    The date the message was updated
 *
 * .. php:attr:: sid
 *
 *    A 34 character string that identifies this object
 *
 * .. php:attr:: account_sid
 *
 *    The account that sent the message
 *
 * .. php:attr:: body
 *
 *    The body of the message
 *
 * .. php:attr:: num_segments
 *
 *    The number of sms messages used to deliver the body
 *
 * .. php:attr:: num_media
 *
 *    The number of media that are associated with the image
 *
 * .. php:attr:: subresource_uris
 *
 *    The subresources associated with this message (just Media at the moment)
 *
 * .. php:attr:: from
 *
 *    The number this message was sent from
 *
 * .. php:attr:: to
 *
 *    The phone number this message was sent to
 */
class Services_Twilio_Rest_Message extends Services_Twilio_InstanceResource {
    protected function init($client, $uri) {
        $this->setupSubresources(
            'media'
        );
    }

    public function redact() {
        $postParams = array('Body' => '');
        self::update($postParams);
    }

    /**
     * Make a request to delete the specified resource.
     *
     * :rtype: boolean
     */
    public function delete()
    {
        return $this->client->deleteData($this->uri);
    }
}

