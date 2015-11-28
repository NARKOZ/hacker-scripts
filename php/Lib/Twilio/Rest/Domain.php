<?php

/**
 * A single Domain
 *
 * .. php:attr:: date_created
 *
 *    The date the domain was created
 *
 * .. php:attr:: date_updated
 *
 *    The date the domain was updated
 *
 * .. php:attr:: sid
 *
 *    A 34 character string that identifies this object
 *
 * .. php:attr:: account_sid
 *
 *    The account that created the domain
 *
 * .. php:attr:: friendly_name
 *
 *    The friendly name of the domain
 *
 * .. php:attr:: domain_name
 *
 *    The *.sip.twilio domain for the domain
 *
 * .. php:attr:: auth_type
 *
 *    The auth type used for the domain
 *
 * .. php:attr:: voice_url
 *
 *    The voice url for the domain
 *
 * .. php:attr:: voice_fallback_url
 *
 *    The voice fallback url for the domain
 *
 * .. php:attr:: voice_fallback_method
 *
 *    The voice fallback method for the domain
 *
 * .. php:attr:: voice_status_callback_url
 *
 *    The voice status callback url for the domain
 *
 * .. php:attr:: voice_status_callback_method
 *
 *    The voice status_callback_method for the domain
 *
 * .. php:attr:: uri
 *
 *    The uri of the domain
 *
 * .. php:attr:: subresource_uris
 *
 *    The subresources associated with this domain (IpAccessControlListMappings, CredentialListMappings)
 *
 */
class Services_Twilio_Rest_Domain extends Services_Twilio_InstanceResource {
    protected function init($client, $uri) {
        $this->setupSubresources(
            'ip_access_control_list_mappings',
            'credential_list_mappings'
        );
    }
}
