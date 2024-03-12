<?php

namespace Config;

use CodeIgniter\Config\View as BaseView;

class Twig extends BaseView
{

	/**
	 * Parser Filters map a filter name with any PHP callable. When the
	 * Parser prepares a variable for display, it will chain it
	 * through the filters in the order defined, inserting any parameters.
	 * To prevent potential abuse, all filters MUST be defined here
	 * in order for them to be available for use within the Parser.
	 *
	 * Examples:
	 *  { title|esc(js) }
	 *  { created_on|date(Y-m-d)|esc(attr) }
	 *
	 * @var array
	 */
	public $filters = [];

	/**
	 * Parser Globals provide a way to extend the functionality provided
	 * by the core Parser by creating aliases that will be replaced with
	 * any callable. Can be single or tag pair.
	 *
	 * @var array
	 */
	public $globals = [];

	/**
	 * Parser Functions provide a way to extend the functionality provided
	 * by the core Parser by creating aliases that will be replaced with
	 * any callable. Can be single or tag pair.
	 *
	 * @var array
	 */
	public $functions = [
		'base_url',
		'assets',
		'site_url',
		'env',
		'helper',
		'config',
		'lang',
		'session',
		'cookies',
		'cookie',
		'mailto',
		'current_url',
		'previous_url',
		'url_to',
		'anchor',
		'number_to_size',
		'number_to_amount',
		'number_to_currency',
		'number_to_roman',
		'underscore',
		'ordinalize',
		'app_timezone',
		'csrf_token',
		'csrf_header',
		'csrf_hash',
		'csrf_field',
		'csrf_meta',
		'redirect'
	];
}
