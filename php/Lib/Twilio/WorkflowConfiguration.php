<?php
/**
 * Created by PhpStorm.
 * User: jwitz
 * Date: 7/16/15
 * Time: 4:15 PM
 */

class WorkflowConfiguration implements JsonSerializable {
	public $filters;
	public $default_filter;

	public function __construct($filters, $default_filter = null)
	{
		$this->filters = $filters;
		$this->default_filter = $default_filter;
	}

	public function toJSON() {
		return json_encode($this);
	}

	public static function parse($json) {
		return json_decode($json);
	}

	public function jsonSerialize() {
		$json = array();
		$task_routing = array();
		$task_routing["filters"] = $this->filters;
		$task_routing["default_filter"] = $this->default_filter;
		$json["task_routing"] = $task_routing;
		return $json;
	}
}

class WorkflowRule implements JsonSerializable {
	public $expression;
	public $friendly_name;
	public $targets;

	public function __construct($expression, $targets, $friendly_name = null)
	{
		$this->expression = $expression;
		$this->targets = $targets;
		$this->friendly_name = $friendly_name;
	}

	public function jsonSerialize() {
		$json = array();
		$json["expression"] = $this->expression;
		$json["targets"] = $this->targets;
		if($this->friendly_name != null) {
			$json["friendly_name"] = $this->friendly_name;
		}
		return $json;
	}
}

class WorkflowRuleTarget implements JsonSerializable {
	public $queue;
	public $expression;
	public $priority;
	public $timeout;

	public function __construct($queue, $priority = null, $timeout = null, $expression = null)
	{
		$this->queue = $queue;
		$this->priority = $priority;
		$this->timeout = $timeout;
		$this->expression = $expression;
	}

	public function jsonSerialize() {
		$json = array();
		$json["queue"] = $this->queue;
		if($this->priority != null) {
			$json["priority"] = $this->priority;
		}
		if($this->timeout != null) {
			$json["timeout"] = $this->timeout;
		}
		if($this->expression != null) {
			$json["expression"] = $this->expression;
		}
		return $json;
	}
}