<?php

	namespace symphony\ORM\Sections;
	use symphony\ORM\Fields;
	use symphony\ORM\Settings;
	use DOMDocument;
	use Exception;

	class Section {
		protected $fields;
		protected $settings;

		public function __construct() {
			$this->fields = new Fields\Controller();
			$this->settings = new Settings\Controller([
				'fields' =>		$this->fields
			]);
		}

		public function openUri($file) {
			$document = new DOMDocument();
			$document->load($file);

			$this->settings->fromXML($document->documentElement);
		}

		public function install() {
			$db = database();

			try {
				$db->beginTransaction();

				foreach ($this->fields as $field) {
					$format = $field->format();
					$format->install($this, $field);
				}

				$db->commit();
			}

			catch (Exception $error) {
				$db->rollBack();

				throw $error;
			}
		}

		public function settings() {
			return $this->settings;
		}

		public function fields() {
			return $this->fields;
		}
	}