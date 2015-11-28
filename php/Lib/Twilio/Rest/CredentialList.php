<?php

/**
 * A single CredentialList
 *
 * .. php:attr:: date_created
 *
 *    The date the credential list was created
 *
 * .. php:attr:: date_updated
 *
 *    The date the credential list was updated
 *
 * .. php:attr:: sid
 *
 *    A 34 character string that identifies this object
 *
 * .. php:attr:: account_sid
 *
 *    The account that created the credential list
 *
 * .. php:attr:: friendly_name
 *
 *    The friendly name of the credential list
 *
 * .. php:attr:: uri
 *
 *    The uri of the credential list
 *
 * .. php:attr:: subresource_uris
 *
 *    The subresources associated with this credential list (Credentials)
 */

class Services_Twilio_Rest_CredentialList extends Services_Twilio_InstanceResource {
    protected function init($client, $uri) {
        $this->setupSubresources(
            'credentials'
        );
    }
}

