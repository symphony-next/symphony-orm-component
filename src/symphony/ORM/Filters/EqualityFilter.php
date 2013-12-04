<?php

	namespace symphony\ORM\Filters;

	interface EqualityFilter extends Filter {
		public function filter($value);
		public function notFilter($value);
	}