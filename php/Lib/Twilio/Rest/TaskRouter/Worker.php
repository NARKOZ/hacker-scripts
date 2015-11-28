<?php

class Services_Twilio_Rest_TaskRouter_Worker extends Services_Twilio_TaskRouterInstanceResource {

	protected function init($client, $uri) {
		$this->setupSubresource('statistics');
		$this->setupSubresources('reservations');
	}
}
