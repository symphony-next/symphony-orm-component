select
	entries.id
from
	`entries`,
	`field_hotel-categories_title`,
	`field_villa-categories_title`,
	`field_hotel-categories_published`,
	`field_villa-categories_published`,
	`field_hotel-categories_publish-date`,
	`field_villa-categories_publish-date`,
	`field_hotel-categories_body`,
	`field_villa-categories_body`,
	`field_hotel-categories_category`,
	`field_villa-categories_category`
where
	(
		entries.section IN('hotel-categories', 'villa-categories')
	)
	and (
		(
			(
				field_hotel-categories_title.value != 'some hotel'
			)
			and (
				field_hotel-categories_published.value = 'yes'
			)
		)
		or (
			(
				field_villa-categories_title.value != 'some villa'
			)
			and (
				field_hotel-categories_published.value = 'yes'
			)
		)
	)


select
	*
from
	(
		select
			`entries`.id,
			`hotels_title`.value as `title`,
			`hotels_publish-date`.value as `publish-date`
		from
			`entries`,
			`hotels_title`,
			`hotels_publish-date`
		where
			`entries`.section = 'hotels'
			and `hotels_title`.entry = entries.id
			and `hotels_publish-date`.entry = entries.id
			and `hotels_title`.value != 'some hotel'

		union select
			`entries`.id,
			`villas_title`.value as `title`,
			`villas_publish-date`.value as `publish-date`
		from
			`entries`,
			`villas_title`,
			`villas_publish-date`
		where
			`entries`.section = 'villas'
			and `villas_title`.entry = entries.id
			and `villas_publish-date`.entry = entries.id
			and `villas_title`.value != 'some villa'
	) as `entries`
order by
	`publish-date` desc,
	`title` asc