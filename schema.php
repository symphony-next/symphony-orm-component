<?php

	interface Field {
		public function listSchemaGenerators();
		public function getSchemaGenerator();
	}

	interface FilterableField {
		public function listFilterGenerators();
		public function getFilterGenerator();
	}

	interface DisplayableField {
		public function listBriefGenerators();
		public function getBriefGenerator();
	}

	interface EntryGenerator {
		public function getMarkup(Entry $entry);
	}

	interface BriefGenerator extends EntryGenerator {

	}

	interface SchemaGenerator extends EntryGenerator {

	}














	interface DataFilterType {
		public function listMethods();
	}

	interface RangeDataFilterType extends DataFilterType {
		public function between($from, $to);
		public function less($value);
		public function greater($value);
	}

	interface BooleanDataFilterType extends DataFilterType {
		public function filter();
		public function notFilter();
	}

	interface EqualityDataFilterType extends DataFilterType {
		public function filter($value);
		public function notFilter($value);
	}

	class DataFilterCollection {
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

	class Field {
		public function getFilters() {
			return new DataFilterCollection([
				new EqualityDataFilter(),
				new CheckboxDataFilter()
			]);
		}
	}

	$field = new Field();
	$filters = $field->getFilters();

	$filter->is('no');

	class EqualityDataFilter implements EqualityDataFilterType {
		public function listMethods() {
			return [
				'filter' =>		'is',
				'notFilter' =>	'isNot'
			];
		}

		public function is($value, $something = false) {
			return $this->filter($value, $something);
		}

		public function filter($value, $something = false) {

		}

		public function notFilter($value) {

		}
	}

	class BooleanDataFilter implements BooleanDataFilterType {
		public function listMethods() {
			return [
				'filter' =>		'is',
				'notFilter' =>	'isNot'
			];
		}

		public function filter() {

		}

		public function notFilter() {

		}
	}

	class CheckboxDataFilter implements BooleanDataFilterType {
		public function listMethods() {
			return [
				'filter' =>		'isChecked',
				'notFilter' =>	'isNotChecked'
			];
		}

		public function filter() {
			// Is checkbox = 'yes'
		}

		public function notFilter() {
			// Is checkbox = 'no' or not found or null
		}
	}