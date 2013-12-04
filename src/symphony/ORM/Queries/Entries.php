<?php

	namespace symphony\ORM\Queries;
	use symphony\ORM\Filters;
	use symphony\ORM\Fields;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use ReflectionMethod;

	class Entries {
		const FILTER_MODE_AND = 'all';
		const FILTER_MODE_OR = 'any';
		const FILTER_MODE_NOT = 'none';
		const FILTER_TYPE_AND = '$and';
		const FILTER_TYPE_OR = '$or';
		const FILTER_TYPE_NOT = '$not';

		protected $filterCurrent;

		protected $filterStack;

		protected $section;

		public function __construct(Section $section) {
			$this->filterMethod = new ReflectionMethod($this, 'filter');
			$this->filterMethod->setAccessible(true);
			$this->filterStack = [];
			$this->section = $section;
		}

		public function __call($name, $arguments) {
			switch ($name) {
				case static::FILTER_MODE_AND:
				case static::FILTER_MODE_OR:
				case static::FILTER_MODE_NOT:
					array_unshift($arguments, $name);

					return $this->filterMethod->invokeArgs($this, $arguments);
			}
		}

		protected function filter($mode, callable $callback) {
			$callback = $callback->bindTo($this);
			$parent = $this->filterCurrent;
			$results = [];

			array_push($this->filterStack, $parent);

			if ($mode === static::FILTER_MODE_AND) {
				$mode = '$and';
			}

			else if ($mode === static::FILTER_MODE_OR) {
				$mode = '$or';
			}

			else if ($mode === static::FILTER_MODE_NOT) {
				$mode = '$not';
			}

			$this->filterCurrent = (object)[
				$mode => []
			];

			$callback(function($name) use ($mode) {
				$field = $this->section->fields()->{$name};

				return new Filters\Controller(
					$field->filters(),
					$this->filterCurrent->{$mode}
				);
			});

			if (isset($parent->{'$and'})) {
				$parent->{'$and'}[] = $this->filterCurrent;
			}

			else if (isset($parent->{'$or'})) {
				$parent->{'$or'}[] = $this->filterCurrent;
			}

			else if (isset($parent->{'$not'})) {
				$parent->{'$not'}[] = $this->filterCurrent;
			}

			$parent = array_pop($this->filterStack);

			if ($parent) $this->filterCurrent = $parent;
		}
	}