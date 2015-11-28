<?php


class Services_Twilio_NextGenListResource extends Services_Twilio_ListResource {

	public function getPage($page = 0, $size = 50, $filters = array(), $deep_paging_uri = null) {
		if ($deep_paging_uri !== null) {
			$page = $this->client->retrieveData($deep_paging_uri, array(), true);
		} else if ($page == 0) {
			$page = $this->client->retrieveData($this->uri, array('Page' => $page, 'PageSize' => $size) + $filters);
		} else {
			return $this->emptyPage();
		}

		$list_name = $page->meta->key;
		if (!isset($list_name) || $list_name === '') {
			throw new Services_Twilio_HttpException("Couldn't find list key in response");
		}

		$page->$list_name = array_map(
			array($this, 'getObjectFromJson'),
			$page->$list_name
		);
		$page->next_page_uri = $page->meta->next_page_url;

		return new Services_Twilio_Page($page, $list_name, $page->meta->next_page_url);
	}

	private function emptyPage() {
		$page = new stdClass();
		$page->empty = array();
		return new Services_Twilio_Page($page, 'empty');
	}

	/**
	 * Create a resource on the list and then return its representation as an
	 * InstanceResource.
	 *
	 * :param array $params: The parameters with which to create the resource
	 *
	 * :return: The created resource
	 * :rtype: :php:class:`InstanceResource <Services_Twilio_InstanceResource>`
	 */
	protected function _create($params)
	{
		$params = $this->client->createData($this->uri, $params, true);
		/* Some methods like verified caller ID don't return sids. */
		if (isset($params->sid)) {
			$resource_uri = $this->uri . '/' . $params->sid;
		} else {
			$resource_uri = $this->uri;
		}
		return new $this->instance_name($this->client, $resource_uri, $params);
	}

	public function count() {
		throw new BadMethodCallException("Counting is not supported by this resource");
	}

}
