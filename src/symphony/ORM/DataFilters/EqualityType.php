<?php

	namespace symphony\ORM\DataFilters;

	interface EqualityType extends Type {
		public function filter($value);
		public function notFilter($value);
	}