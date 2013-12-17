<?php

	namespace symphony\ORM\Formats;
	use symphony\ORM\Fields;
	use symphony\ORM\Fields\Field;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;
	use DateTime;
	use PDO;

	class Toggle implements Format {
		public function install(Section $section, Field $field) {
			database()->query(sprintf(
				"
					create table if not exists `%s_%s` (
						`id` int(11) unsigned not null auto_increment,
						`entry` int(11) unsigned not null,
						`value` varchar(3) default null,
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
			if (isset($old) && is_object($old)) {
				$result = $old;
			}

			else {
				$result = (object)[
					'value' =>		null
				];
			}

			if ($new !== null) {
				$result->value = $new;
			}

			return $result;
		}

		public function validate(Entry $entry, Field $field, $data) {
			yield true;
		}

		public function write(Section $section, Entry $entry, Field $field, $data) {
			$statement = database()->prepare(sprintf(
				'insert into `%s_%s`(entry, value) VALUES(?, ?)',
				$section->settings()->handle,
				$field->settings()->handle
			));
			$statement->bindValue(1, $entry->getId(), PDO::PARAM_INT);
			$statement->bindValue(2, $data->value, PDO::PARAM_STR);
			$statement->execute();
		}
	}