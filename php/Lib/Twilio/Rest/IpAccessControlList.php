<?php

/**
 * A single IpAccessControlList
 *
 * .. php:attr:: date_created
 *
 *    The date the ip access control list was created
 *
 * .. php:attr:: date_updated
 *
 *    The date the ip access control list was updated
 *
 * .. php:attr:: sid
 *
 *    A 34 character string that identifies this object
 *
 * .. php:attr:: account_sid
 *
 *    The account that created the ip access control list
 *
 * .. php:attr:: friendly_name
 *
 *    The friendly name of the ip access control list
 *
 * .. php:attr:: uri
 *
 *    The uri of the ip access control list
 *
 * .. php:attr:: subresource_uris
 *
 *    The subresources associated with this ip access control list (IpAddresses)
 */
class Services_Twilio_Rest_IpAccessControlList extends Services_Twilio_InstanceResource {
    protected function init($client, $uri) {
        $this->setupSubresources(
            'ip_addresses'
        );
    }
}
