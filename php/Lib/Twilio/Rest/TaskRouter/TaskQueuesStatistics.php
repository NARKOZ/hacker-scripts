<?php

class Services_Twilio_Rest_TaskRouter_TaskQueuesStatistics extends Services_Twilio_TaskRouterListResource
{
	public function __construct($client, $uri) {
		$this->instance_name = "Services_Twilio_Rest_TaskRouter_TaskQueueStatistics";
		parent::__construct($client, $uri);
	}
}
