<?php

class Services_Twilio_Rest_FeedbackSummary extends Services_Twilio_InstanceResource {

    public function __construct($client, $uri, $params = array()) {
        $this->instance_name = "Services_Twilio_Rest_FeedbackSummary";
        return parent::__construct($client, $uri, $params);
    }

    /**
     * Create feedback summary for calls
     */
    public function create(array $params = array()) {
        $params = $this->client->createData($this->uri, $params);
        return new $this->instance_name($this->client, $this->uri, $params);
    }

    /**
     * Delete a feedback summary
     */
    public function delete($sid) {
        $this->client->deleteData($this->uri . '/' . $sid);
    }

    /**
     * Get a feedback summary
     */
    public function get($sid) {
        return new $this->instance_name(
            $this->client, $this->uri . '/' . $sid
        );
    }
}
