<?php

/**
 *   An object representing a single phone number. For more
 *   information, see the `IncomingPhoneNumber Instance Resource
 *   <http://www.twilio.com/docs/api/rest/incoming-phone-numbers#instance>`_
 *   documentation.
 *
 *   .. php:attr:: sid
 *
 *      A 34 character string that uniquely idetifies this resource.
 *
 *   .. php:attr:: date_created
 *
 *      The date that this resource was created, given as GMT RFC 2822 format.
 *
 *   .. php:attr:: date_updated
 *
 *      The date that this resource was last updated, given as GMT RFC 2822 format.
 *
 *   .. php:attr:: friendly_name
 *
 *      A human readable descriptive text for this resource, up to 64
 *      characters long. By default, the FriendlyName is a nicely formatted
 *      version of the phone number.
 *
 *   .. php:attr:: account_sid
 *
 *      The unique id of the Account responsible for this phone number.
 *
 *   .. php:attr:: phone_number
 *
 *      The incoming phone number. e.g., +16175551212 (E.164 format)
 *
 *   .. php:attr:: api_version
 *
 *      Calls to this phone number will start a new TwiML session with this
 *      API version.
 *
 *   .. php:attr:: voice_caller_id_lookup
 *
 *      Look up the caller's caller-ID name from the CNAM database (additional charges apply). Either true or false.
 *
 *   .. php:attr:: voice_url
 *
 *      The URL Twilio will request when this phone number receives a call.
 *
 *   .. php:attr:: voice_method
 *
 *      The HTTP method Twilio will use when requesting the above Url. Either GET or POST.
 *
 *   .. php:attr:: voice_fallback_url
 *
 *      The URL that Twilio will request if an error occurs retrieving or executing the TwiML requested by Url.
 *
 *   .. php:attr:: voice_fallback_method
 *
 *      The HTTP method Twilio will use when requesting the VoiceFallbackUrl. Either GET or POST.
 *
 *   .. php:attr:: status_callback
 *
 *      The URL that Twilio will request to pass status parameters (such as call ended) to your application.
 *
 *   .. php:attr:: status_callback_method
 *
 *      The HTTP method Twilio will use to make requests to the StatusCallback URL. Either GET or POST.
 *
 *   .. php:attr:: sms_url
 *
 *      The URL Twilio will request when receiving an incoming SMS message to this number.
 *
 *   .. php:attr:: sms_method
 *
 *      The HTTP method Twilio will use when making requests to the SmsUrl. Either GET or POST.
 *
 *   .. php:attr:: sms_fallback_url
 *
 *      The URL that Twilio will request if an error occurs retrieving or executing the TwiML from SmsUrl.
 *
 *   .. php:attr:: sms_fallback_method
 *
 *      The HTTP method Twilio will use when requesting the above URL. Either GET or POST.
 *
 *   .. php:attr:: beta
 *
 *      Whether this number is new to Twilio's inventory.
 *
 *   .. php:attr:: uri
 *
 *    The URI for this resource, relative to https://api.twilio.com.
 */
class Services_Twilio_Rest_IncomingPhoneNumber
    extends Services_Twilio_InstanceResource
{
}
