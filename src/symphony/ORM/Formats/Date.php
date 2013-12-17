<?php

	namespace symphony\ORM\Formats;
	use symphony\ORM\Fields;
	use symphony\ORM\Fields\Field;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use DateTime;
	use PDO;

	class Date implements Format {
		const FORMAT = 'Y-m-d H:i:s';

		public function install(Section $section, Field $field) {
			database()->query(sprintf(
				"
					create table if not exists `%s_%s` (
						`id` int(11) unsigned not null auto_increment,
						`entry` int(11) unsigned not null,
						`value` datetime default null,
						primary key (`id`),
						key `entry` (`entry`),
						key `value` (`value`)
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
					'value' =>		null
				];
			}

			// Import the new value on top:
			if (isset($new->value)) {
				$result->value = $new->value;
			}

			else if (isset($new)) {
				$result->value = $new;
			}

			// Convert value to DateTime:
			if (isset($result->value) && ($result->value instanceof DateTime) === false) {
				$date = DateTime::createFromFormat(self::FORMAT, $result->value);

				if ($date instanceof DateTime) {
					$result->value = $date;
				}

				else {
					$result->value = null;
				}
			}

			return $result;
		}

		public function validate(Entry $entry, Field $field, $data) {
			if (
				$field->settings()->required === 'yes'
				&& ($data->value instanceof DateTime) === false
			) {
				throw new Fields\RequiredException("'{$field->settings()->handle}' is a required field.");
			}

			yield true;
		}

		public function write(Section $section, Entry $entry, Field $field, $data) {
			$statement = database()->prepare(sprintf(
				'insert into `%s_%s`(entry, value) VALUES(?, ?)',
				$section->settings()->handle,
				$field->settings()->handle
			));
			$statement->bindValue(1, $entry->getId(), PDO::PARAM_INT);

			if ($data->value instanceof DateTime) {
				$statement->bindValue(2, $data->value->format(self::FORMAT), PDO::PARAM_STR);
			}

			else {
				$statement->bindValue(2, '', PDO::PARAM_STR);
			}

			$statement->execute();
		}
	}