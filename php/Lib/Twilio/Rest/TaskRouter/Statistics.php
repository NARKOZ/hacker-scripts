<?php

class Services_Twilio_Rest_TaskRouter_Statistics extends Services_Twilio_TaskRouterInstanceResource
{
	public function get($filters = array()) {
		return $this->client->retrieveData($this->uri, $filters);
	}
}
