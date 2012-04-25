<?php

class twCoreRoutingLoader  {
	protected $type1_instructions = array();
	protected $type2_instructions = array();

	public function load(sfEventDispatcher $dispatcher) {
		$ra = sfConfig::get('tw_routing_array', array());
		foreach($ra as $route) {
			if ($route['type'] == 1) {
				$instructions = json_decode($route['instructions'], true);
				$instructions['route'] = $route['route'];
				$instructions['url'] = $route['url'];
				array_push($this->type1_instructions, $instructions);
				$dispatcher->connect('routing.load_configuration', array($this, 'addRouteForType1'));
			}
			if ($route['type'] == 2) {
				$instructions = json_decode($route['instructions'], true);
				$dispatcher->connect('routing.load_configuration', array($instructions['class'], $instructions['method']));
			}
		}
	}

	public function addRouteForType1(sfEvent $event) {
		$instructions = array_shift($this->type1_instructions);
		$event->getSubject()->prependRoute($instructions['route'], new sfRoute(
			$instructions['url'],
			array (
				'module' => $instructions['module'],
				'action' => $instructions['action'],
			),
			array (),
			array ()
			)
		);
	}
}

