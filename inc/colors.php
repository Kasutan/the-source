<?php
/**
 * Register Custom color palette for Gutenberg editor
 *
 * Should be the colors from css/colors.css.
 *
 * @package kasutan
 */

add_theme_support( 'editor-color-palette', array(
	/*array(
		'name'  =>'Bleu foncé',
		'slug'  => 'bleu-fonce',
		'color'	=> '#30358C',
	),*/
	array(
		'name'  =>'Blue',
		'slug'  => 'bleu',
		'color'	=> '#173a65',
	),
	array(
		'name'  =>'Cyan',
		'slug'  => 'cyan',
		'color'	=> '#18AD92',
	),
	array(
		'name'  =>'Light blue',
		'slug'  => 'bordure',
		'color'	=> '#c2d6ee',
	),
	array(
		'name'  =>'Very light blue',
		'slug'  => 'bleu-clair',
		'color'	=> '#F0F7FF',
	),
	
	
	array(
		'name'  =>'Red',
		'slug'  => 'rouge',
		'color'	=> '#eb1118',
	),

	
	array(
		'name'  =>'Grey',
		'slug'  => 'gris',
		'color'	=> '#959698',
	),
	array(
		'name'  =>'White',
		'slug'  => 'blanc',
		'color'	=> '#ffffff',
	),
	array(
		'name'  =>'Black',
		'slug'  => 'noir',
		'color'	=> '#081525',
	),
));