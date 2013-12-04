<?php

	namespace symphony\ORM\Fields;
	use symphony\ORM\Formats;
	use symphony\ORM\Settings;

	abstract class Field {
		public function __construct() {
			$this->format = new Formats\Controller($this);
			$this->settings = new Settings\Controller([
				'format' =>		$this->format
			]);
		}

		public function settings() {
			return $this->settings;
		}
	}