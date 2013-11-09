<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\Settings;

	abstract class Type {
		public function __construct() {
			$this->settings = new Settings\Controller();
		}

		public function settings() {
			return $this->settings;
		}
	}