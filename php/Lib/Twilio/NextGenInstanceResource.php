<?php

abstract class Services_Twilio_NextGenInstanceResource extends Services_Twilio_InstanceResource {

	/**
	 * Make a request to the API to update an instance resource
	 *
	 * :param mixed $params: An array of updates, or a property name
	 * :param mixed $value:  A value with which to update the resource
	 *
	 * :rtype: null
	 * :throws: a :php:class:`RestException <Services_Twilio_RestException>` if
	 *      the update fails.
	 */
	public function update($params, $value = null)
	{
		if (!is_array($params)) {
			$params = array($params => $value);
		}
		$decamelizedParams = $this->client->createData($this->uri, $params, true);
		$this->updateAttributes($decamelizedParams);
	}
}