<?php

class Services_Twilio_Rest_TaskRouter_Workflows extends Services_Twilio_TaskRouterListResource {

    public function create($friendlyName, $configuration, $assignmentCallbackUrl, array $params = array()) {
        $params['FriendlyName'] = $friendlyName;
        $params['Configuration'] = $configuration;
        $params['AssignmentCallbackUrl'] = $assignmentCallbackUrl;
        return parent::_create($params);
    }
}
