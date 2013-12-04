<?php

	namespace symphony\ORM\Sections;
	use symphony\ORM\Fields;
	use PDO;
	use Exception;
	use StdClass;

	class Entry {
		protected $section;
		protected $data;
		protected $id;

		public function __construct(Section $section) {
			$this->section = $section;
			$this->data = new StdClass();
		}

		public function __get($handle) {
			if (isset($this->data->{$handle}) === false) return null;

			return $this->data->{$handle};
		}

		public function __set($handle, $value) {
			$field = $this->section->fields()->{$handle};
			$format = $field->format();
			$this->data->{$handle} = $format->prepare($this, $field, $value, $this->{$handle});
		}

		public function getId() {
			return $this->id;
		}

		public function setId($id) {
			$this->id = $id;
		}

		public function getSection() {
			return $this->section;
		}

		public function setSection(Section $section) {
			$this->section = $section;
		}

		public function validate() {
			foreach ($this->section->fields() as $field) {
				try {
					$format = $field->format();
					$handle = $field->settings()->handle;
					$data = $this->{$handle};

					foreach ($format->validate($this, $field, $data) as $result) {
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

		public function write() {
			$db = database();

			try {
				$db->beginTransaction();

				$statement = $db->prepare(
					'insert into `entries`(section) VALUES(?)'
				);
				$statement->bindValue(1, $this->section->settings()->handle, PDO::PARAM_STR);
				$statement->execute();

				$this->setId($db->lastInsertId());

				foreach ($this->section->fields() as $field) {
					$format = $field->format();
					$handle = $field->settings()->handle;
					$data = $format->prepare($this, $field, $this->{$handle});

					$format->write($this->section, $this, $field, $data);
				}

				$db->commit();
			}

			catch (Exception $error) {
				$db->rollBack();

				throw $error;
			}
		}
	}