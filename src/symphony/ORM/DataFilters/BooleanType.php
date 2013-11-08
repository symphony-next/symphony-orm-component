<?php

	namespace symphony\ORM\DataFilters;

	interface BooleanType extends Type {
		public function filter();
		public function notFilter();
	}