<?php

	use symphony\ORM\Queries\Entries;
	use symphony\ORM\Sections\Entry;
	use symphony\ORM\Sections\Section;

	require_once 'vendor/autoload.php';

	/*
	$section = new Section();
	$section->openUri('assets/section.articles.xml');
	$query = new Entries($section);

	$query->all(function($and) {
		$and('title')
			->is('foobar')
			->isNot('boofar');

		$this->any(function($or) {
			$or('publish-date')
				->earlierThan('now');

			$this->none(function($and) {
				$and('title')
					->is('foobar')
					->isNot('boofar');
			});
		});
	});

	var_dump($query);
	*/










	// Connect to the database:
	require_once 'db.php';

	$section = new Section();
	$section->openUri('assets/section.articles.xml');

	//var_dump($section->fields()->{'title'}->format()->settings());

	//exit;

	// Install table schema:
	$section->install();

	$entry = new Entry($section);
	$entry->{'title'} = 'Foobar';
	$entry->{'copy'} = 'Boofar foobar.';
	$entry->{'publish-date'} = '2013-08-18 01:00:00';

	// Validate all fields and get any errors:
	$valid = true;

	foreach ($entry->validate() as $field => $error) {
		var_dump($error);

		$valid = false;
	}

	// Save the entry:
	if ($valid) {
		$entry->write();

		printf('<p>Entry %d created.</p>', $entry->getId());
	}