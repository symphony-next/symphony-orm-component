<?php

	$query = new EntriesQuery();
	$query->from(function($and) {
		$and('hotel-categories');
		$and('villa-categories');
	});

	$query->all(function($and) {
		$and('title', 'villa-categories')
			->isNot('some villa');

		$and('title', 'hotel-categories')
			->isNot('some hotel');

		$and('published')
			->isChecked();

		$and('publish-date')
			->between('2012', '2014');

		$and->any(function($or) {
			$or('body')
				->contains('spam spam spam spam');

			$or('category')
				->isNotNull();
		});
	});

	$and->all(new PublishedQuery());

	class PublishedQuery {
		public function __invoke($and) {
			$and('publish-date')
				->laterThan('now');

			$and('published')
				->isChecked();
		}
	}

	$query->sort(function($asc, $desc) {
		$desc('publish-date');
		$asc('title');
	});

	$query->paginate(1, 20);

	/*
		Advantages

		- Can reuse the closures in other queries.
		- Use of variables explains itself ($and, $or, $asc, $desc).

		Disadvantages

		- More boilerplate code.
	*/