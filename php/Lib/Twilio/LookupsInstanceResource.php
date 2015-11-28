<?php

abstract class Services_Twilio_LookupsInstanceResource extends Services_Twilio_NextGenInstanceResource {

    protected function setupSubresources() {
        foreach (func_get_args() as $name) {
            $constantized = ucfirst(self::camelize($name));
            $type = "Services_Twilio_Rest_Lookups_" . $constantized;
            $this->subresources[$name] = new $type(
                $this->client, $this->uri . "/$constantized"
            );
        }
    }

}
