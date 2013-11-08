<?php

	namespace symphony\ORM\DataFilters;

	class Collection {
		protected $methods;

		public function __construct($filters) {
			foreach ($filters as $filter) {
				$this->addFilter($filter);
			}
		}

		public function addFilter(DataFilterType $filter) {
			foreach ($filter->listMethods() as $actual => $alias) {
				$this->methods[$alias] = new ReflectionMethod($actual);
			}
		}

		public function __call($name, $arguments) {
			if (isset($this->methods[$name]) === false) return false;

			return $this->methods[$name]->invokeArgs($name, $arguments);
		}
	}