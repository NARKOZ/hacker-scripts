<?php

/**
 *   For more information, see the `Call Instance Resource <http://www.twilio.com/docs/api/rest/call#instance>`_ documentation.
 *
 *   .. php:attr:: sid
 *
 *      A 34 character string that uniquely identifies this resource.
 *
 *   .. php:attr:: parent_call_sid
 *
 *      A 34 character string that uniquely identifies the call that created this leg.
 *
 *   .. php:attr:: date_created
 *
 *      The date that this resource was created, given as GMT in RFC 2822 format.
 *
 *   .. php:attr:: date_updated
 *
 *      The date that this resource was last updated, given as GMT in RFC 2822 format.
 *
 *   .. php:attr:: account_sid
 *
 *      The unique id of the Account responsible for creating this call.
 *
 *   .. php:attr:: to
 *
 *      The phone number that received this call. e.g., +16175551212 (E.164 format)
 *
 *   .. php:attr:: from
 *
 *      The phone number that made this call. e.g., +16175551212 (E.164 format)
 *
 *   .. php:attr:: phone_number_sid
 *
 *      If the call was inbound, this is the Sid of the IncomingPhoneNumber that
 *      received the call. If the call was outbound, it is the Sid of the
 *      OutgoingCallerId from which the call was placed.
 *
 *   .. php:attr:: status
 *
 *      A string representing the status of the call. May be `QUEUED`, `RINGING`,
 *      `IN-PROGRESS`, `COMPLETED`, `FAILED`, `BUSY` or `NO_ANSWER`.
 *
 *   .. php:attr:: start_time
 *
 *      The start time of the call, given as GMT in RFC 2822 format. Empty if the call has not yet been dialed.
 *
 *   .. php:attr:: end_time
 *
 *      The end time of the call, given as GMT in RFC 2822 format. Empty if the call did not complete successfully.
 *
 *   .. php:attr:: duration
 *
 *      The length of the call in seconds. This value is empty for busy, failed, unanswered or ongoing calls.
 *
 *   .. php:attr:: price
 *
 *      The charge for this call in USD. Populated after the call is completed. May not be immediately available.
 *
 *   .. php:attr:: direction
 *
 *         A string describing the direction of the call. inbound for inbound
 *         calls, outbound-api for calls initiated via the REST API or
 *         outbound-dial for calls initiated by a <Dial> verb.
 *
 *   .. php:attr:: answered_by
 *
 *      If this call was initiated with answering machine detection, either human or machine. Empty otherwise.
 *
 *   .. php:attr:: forwarded_from
 *
 *        If this call was an incoming call forwarded from another number, the
 *        forwarding phone number (depends on carrier supporting forwarding).
 *        Empty otherwise.
 *
 *   .. php:attr:: caller_name
 *
 *      If this call was an incoming call from a phone number with Caller ID Lookup enabled, the caller's name. Empty otherwise.
 */
class Services_Twilio_Rest_Call extends Services_Twilio_InstanceResource {

    /**
     * Hang up the call
     */
    public function hangup() {
        $this->update('Status', 'completed');
    }

    /**
     * Redirect the call to a new URL
     *
     * :param string $url: the new URL to retrieve call flow from.
     */
    public function route($url) {
        $this->update('Url', $url);
    }

    protected function init($client, $uri) {
        $this->setupSubresources(
            'notifications',
            'recordings',
            'feedback'
        );
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
