<?php

/**
 * A list of :class:`Media <Services_Twilio_Rest_MediaInstance>` objects.
 * For the definitive reference, see the `Twilio Media List Documentation
 * <https://www.twilio.com/docs/api/rest/media>`_.
 */
class Services_Twilio_Rest_Media extends Services_Twilio_ListResource {


    // This is overridden because the list key in the Twilio response
    // is "media_list", not "media".
    public function getResourceName($camelized = false)
    {
        if ($camelized) {
            return "MediaList";
        } else {
            return "media_list";
        }
    }

    // We manually set the instance name here so that the parent
    // constructor doesn't attempt to figure out it. It would do it
    // incorrectly because we override getResourceName above.
    public function __construct($client, $uri) {
        $this->instance_name = "Services_Twilio_Rest_MediaInstance";
        parent::__construct($client, $uri);
    }

}

