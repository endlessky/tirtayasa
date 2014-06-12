<?php
	class Category {
		public $googleKeywords;
		public $subcategories;
		public function __construct($googleKeywords, $subcategories) {
			$this->googleKeywords = is_null($googleKeywords) ? array() : $googleKeywords;
			$this->subcategories = is_null($subcategories) ? array() : $subcategories;
		}
	}
	$category_directory = new Category(null, array(
		'food' => new Category(array('bakery','bar','cafe','food','meal_delivery','meal_takeaway','restaurant'), null),
		'activities' => new Category(array('amusement_park','aquarium','art_gallery','city_hall','library','movie_theater','museum','night_club','park','spa','stadium','zoo'), null),
		'shop' => new Category(array('book_store','clothing_store','electronic_store','hardware_store','home_goods_store','shopping_mall'), null),
		'hotels' => new Category(array('lodging'), null),
		'amenities' => new Category(array('convenience_store','grocery_or_supermarket','laundry'), array(
			'atm' => new Category(array('atm'), null),
			'banks' => new Category(array('banks'), null),
			'medical' => new Category(array('doctor', 'hospital', 'pharmacy'), null),
			'publicservices' => new Category(array('police', 'post_office'), null),
			'transportation' => new Category(array('airport', 'bus_station', 'car_rental','gas_station','train_station'), null)
		)
	)));
					
?>