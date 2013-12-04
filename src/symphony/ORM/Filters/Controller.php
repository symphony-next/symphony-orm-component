<?php

	namespace symphony\ORM\Filters;
	use ReflectionMethod;

	class Controller {
		protected $methods;
		protected $results;

		public function __construct(array $filters, array &$results) {
			$this->results = &$results;

			foreach ($filters as $filter) {
				$this->addFilter($filter);
			}
		}

		public function __call($name, $arguments) {
			if (isset($this->methods[$name]) === false) return false;

			$current = $this->methods[$name];
			$this->results[] = $current->method->invokeArgs($current->filter, $arguments);

			return $this;
		}

		public function addFilter(Filter $filter) {
			foreach ($filter->listMethods() as $actual => $alias) {
				$this->methods[$alias] = (object)[
					'filter' =>		$filter,
					'method' =>		new ReflectionMethod($filter, $actual)
				];
			}
		}
	}