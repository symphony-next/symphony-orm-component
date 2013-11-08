<?php

	$query = new EntriesQuery();

	$query->all()
		->add('title')
			->isNot('foobar')
			->isNot('boofar')

		->add('checkbox')
			->isChecked()

		->add('publish-date')
			->between('2012', '2014')

		->any()
			->add('body')
				->contains('spam spam spam spam')

			->add('category')
				->isNotNull();



	$query->sort()
		->desc('publish-date')
		->asc('title');


	$query->paginate(1, 20);

	/*
		Advantages

		- Easy on the eyes/easy to write.

		Disadvantages

		- Difficult pattern to write and document.
	*/