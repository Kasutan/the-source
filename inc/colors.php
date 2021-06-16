<?php
/**
 * Register Custom color palette for Gutenberg editor
 *
 * Should be the colors from css/colors.css.
 *
 * @package kasutan
 */

add_theme_support( 'editor-color-palette', array(
	array(
		'name'  =>'Bleu foncé',
		'slug'  => 'bleu-fonce',
		'color'	=> '#073239',
	),
	array(
		'name'  =>'Bleu',
		'slug'  => 'bleu',
		'color'	=> '#124b5a',
	),
	array(
		'name'  =>'Bleu clair',
		'slug'  => 'fond',
		'color'	=> '#e7edee',
	),
	array(
		'name'  =>'Vert',
		'slug'  => 'vert',
		'color'	=> '#1b7c32',
	),
	array(
		'name'  =>'Rouge',
		'slug'  => 'rouge',
		'color'	=> '#da1515',
	),
	array(
		'name'  =>'Gris (texte)',
		'slug'  => 'texte-gris',
		'color'	=> '#919191',
	),
	array(
		'name'  =>'Blanc (texte)',
		'slug'  => 'texte-blanc',
		'color'	=> '#eceded',
	),
	array(
		'name'  =>'Noir (texte)',
		'slug'  => 'texte',
		'color'	=> '#303030',
	),
	array(
		'name'  =>'Blanc (pur)',
		'slug'  => 'blanc',
		'color'	=> '#ffffff',
	),
	array(
		'name'  =>'Noir (pur)',
		'slug'  => 'noir',
		'color'	=> '#101631',
	),
));