<?php

	namespace symphony\ORM\Filters;

	interface BooleanFilter extends Filter {
		public function filter();
		public function notFilter();
	}