<?php

class Services_Twilio_Rest_TaskRouter_TaskQueues extends Services_Twilio_TaskRouterListResource {

    public function create($friendlyName, $assignmentActivitySid, $reservationActivitySid, array $params = array()) {
        $params['FriendlyName'] = $friendlyName;
        $params['AssignmentActivitySid'] = $assignmentActivitySid;
        $params['ReservationActivitySid'] = $reservationActivitySid;
        return parent::_create($params);
    }

	protected function init($client, $uri) {
		$this->setupSubresource('statistics');
	}
}
