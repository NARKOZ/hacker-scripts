<?php

/**
 * A single CredentialListMapping
 *
 * .. php:attr:: date_created
 *
 *    The date this mapping was created
 *
 * .. php:attr:: date_updated
 *
 *    The date this mapping was updated
 *
 * .. php:attr:: sid
 *
 *    The sid of this mapping
 *
 * .. php:attr:: friendly_name
 *
 *    The friendly name of this mapping
 *
 * .. php:attr:: uri
 *
 *    The uri of this mapping
 *
 * .. php:attr:: subresource_uris
 *
 *    The subresources associated with this mapping (Credentials)
 */

class Services_Twilio_Rest_CredentialListMapping extends Services_Twilio_InstanceResource {
    protected function init($client, $uri) {
        $this->setupSubresources(
            'credentials'
        );
    }
}
