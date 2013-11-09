<?php

	use symphony\ORM\DataFilters;
	use symphony\ORM\Fields;
	use symphony\ORM\Sections;
	use symphony\ORM\Settings;

	require_once 'vendor/autoload.php';

	class SectionQuery {
		protected $filterCurrent;

		protected $filterStack;

		protected $section;

		public function __construct(Sections\Type $section) {
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
				$field = $this->section->fields()->{$name};

				return new DataFilters\Controller(
					$field->filters(),
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

	class Entry {
		protected $section;

		public function __construct(Sections\Type $section) {
			$this->section = $section;
		}

		public function validate() {
			foreach ($this->section->fields() as $field) {
				try {
					foreach ($field->validate() as $result) {
						if ($result instanceof Fields\ValidationException) {
							yield $field => $result;
						}
					}
				}

				catch (Fields\ValidationException $error) {
					yield $field => $error;
				}
			}
		}
	}

	/*
	$query = new SectionQuery($section);

	$query->all(function($and) {
		$and('title')
			->is('foobar')
			->isNot('boofar');

		$this->any(function($or) {
			$or('publish-date')
				->earlierThan('now');

			$this->all(function($and) {
				$and('title')
					->is('foobar')
					->isNot('boofar');
			});
		});
	});
	*/

	$section = new Sections\Type();
	$section->openUri('assets/section.articles.xml');
	$entry = new Entry($section);
	$valid = true;

	// Validate all fields and get any errors:
	foreach ($entry->validate() as $error) {
		var_dump($error);

		$valid = false;
	}

	// Save the entry:
	if ($valid) {
		$entry->save();
	}