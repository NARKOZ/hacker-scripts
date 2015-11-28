<?php

class Services_Twilio_Rest_TaskRouter_Workspaces extends Services_Twilio_TaskRouterListResource {

    public function create($friendlyName, array $params = array()) {
        $params['FriendlyName'] = $friendlyName;
        return parent::_create($params);
    }
}
