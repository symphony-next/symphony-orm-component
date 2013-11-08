<?php

	class TestDatasource {
		public function execute() {
			$query = new EntriesQuery();

			$query->all(new TestDatasourceQuery());

			$query->sort(new TestDatasourceSort());

			// ....
		}
	}

	class TestDatasourceQuery {
		public function __invoke($and) {
			$and('publish-date')
				->after('now');

			$and('published')
				->isChecked();
		}
	}

	class TestDatasourceSort {
		public function __invoke($and) {
			$and('publish-date')
				->after('now');

			$and('published')
				->isChecked();
		}
	}