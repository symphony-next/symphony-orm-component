<?php

	namespace symphony\ORM\Formats;
	use symphony\ORM\Fields;
	use symphony\ORM\Fields\Field;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use PDO;

	class Text implements Format {
		public function install(Section $section, Field $field) {
			database()->query(sprintf(
				"
					create table if not exists `%s_%s` (
						`id` int(11) unsigned not null auto_increment,
						`entry` int(11) unsigned not null,
						`handle` varchar(255) default null,
						`value` text default null,
						primary key (`id`),
						key `entry` (`entry`),
						key `handle` (`handle`),
						key `value` (`value`(255))
					);
				",
				$section->settings()->handle,
				$field->settings()->handle
			));
		}

		public function prepare(Entry $entry, Field $field, $new = null, $old = null) {
			// Start with the old value:
			if (isset($old->value)) {
				$result = $old;
			}

			else {
				$result = (object)[
					'handle' =>		null,
					'value' =>		null
				];
			}

			// Import the new value on top:
			if (isset($new->value, $new->handle)) {
				$result->handle = $new->handle;
				$result->value = $new->value;
			}

			else if (isset($new->value)) {
				$result->handle = null;
				$result->value = $new->value;
			}

			else if (isset($new)) {
				$result->handle = null;
				$result->value = $new;
			}

			// Generate the handle:
			if (isset($result->value) && isset($result->handle) === false) {
				$result->handle = strtolower($new);
			}

			return $result;
		}

		public function validate(Entry $entry, Field $field, $data) {
			if (
				$field->settings()->required === 'yes'
				&& (
					isset($data->value) === false
					|| trim($data->value) == false
				)
			) {
				throw new Fields\RequiredException("'{$field->settings()->handle}' is a required field.");
			}

			yield true;
		}

		public function write(Section $section, Entry $entry, Field $field, $data) {
			$statement = database()->prepare(sprintf(
				'insert into `%s_%s`(entry, handle, value) VALUES(?, ?, ?)',
				$section->settings()->handle,
				$field->settings()->handle
			));
			$statement->bindValue(1, $entry->getId(), PDO::PARAM_INT);
			$statement->bindValue(2, $data->handle, PDO::PARAM_STR);
			$statement->bindValue(3, $data->value, PDO::PARAM_STR);
			$statement->execute();
		}
	}