<?php

	use symphony\ORM\DataFilters;

	require_once 'vendor/autoload.php';

	class Named {
		protected $name;

		public function __construct($name) {
			$this->name = $name;
		}

		public function getName() {
			return $this->name;
		}
	}

	class TextField extends Named {
		public function listFilters() {
			return [
				new DataFilters\Equality()
			];
		}
	}

	class DateField extends Named {
		public function listFilters() {
			return [
				new DataFilters\DateEquality(),
				new DataFilters\DateRange()
			];
		}
	}

	class Section extends Named {
		protected $fields;

		public function __construct($name) {
			parent::__construct($name);

			$fields = [
				new TextField('title'),
				new TextField('copy'),
				new DateField('date')
			];
			$this->fields = [];

			foreach ($fields as $field) {
				$this->fields[$field->getName()] = $field;
			}
		}

		public function getField($name) {
			return $this->fields[$name];
		}

		public function listFields() {
			return $this->fields;
		}
	}

	class SectionQuery {
		protected $filterCurrent;

		protected $filterStack;

		protected $section;

		public function __construct(Section $section) {
			$this->filterStack = [];
			$this->section = $section;
		}

		public function __call($name, $arguments) {
			switch ($name) {
				case 'all':
				case 'any':
					array_unshift($arguments, $name);

					return (new ReflectionMethod($this, 'filterBuilder'))
						->invokeArgs($this, $arguments);
			}
		}

		public function filterBuilder($mode, callable $callback) {
			$parent = $this->filterCurrent;
			$this->filterCurrent = (object)[
				'all' => []
			];
			$results = [];

			array_push($this->filterStack, $parent);

			$callback = $callback->bindTo($this);
			$callback(function($name) {
				$field = $this->section->getField($name);

				return new DataFilters\Controller(
					$field->listFilters(),
					$this->filterCurrent->all
				);
			});

			if (isset($parent->{'any'})) {
				$parent->{'any'}[] = $this->filterCurrent;
			}

			else if (isset($parent->{'all'})) {
				$parent->{'all'}[] = $this->filterCurrent;
			}

			$parent = array_pop($this->filterStack);

			if ($parent) $this->filterCurrent = $parent;
		}
	}

	$section = new Section('articles');
	$query = new SectionQuery($section);

	$query->all(function($and) {
		$and('title')
			->is('foobar')
			->isNot('boofar');

		$this->any(function($or) {
			$or('date')
				->earlierThan('now');

			$this->all(function($and) {
				$and('title')
					->is('foobar')
					->isNot('boofar');
			});
		});
	});

	var_dump($query);