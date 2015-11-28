<?php

class Services_Twilio_Rest_Queues
    extends Services_Twilio_ListResource
{
    /**
     * Create a new Queue
     *
     * @param string $friendly_name The name of this queue
     * @param array $params A list of optional parameters, and their values
     * @return Services_Twilio_Rest_Queue The created Queue
     */
    function create($friendly_name, array $params = array()) {
        return parent::_create(array(
            'FriendlyName' => $friendly_name,
        ) + $params);
    }
}

