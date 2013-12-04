<?php

	namespace symphony\ORM\Formats;
	use symphony\ORM\Fields\Field;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;

	interface Format {
		public function __construct();

		public function install(Section $section, Field $field);

		public function prepare(Entry $entry, Field $field, $new = null, $old = null);

		public function validate(Entry $entry, Field $field, $data);

		public function write(Section $section, Entry $entry, Field $field, $data);
	}