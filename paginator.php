<?php namespace Paginator;

class Paginator
{
	/**
	 * Total number of items
	 *
	 * @var integer
	 */

	public static $total = 1;

	/**
	 * Current offset
	 *
	 * @var integer
	 */

	public static $offset = 0;

	/**
	 * Current base URL
	 *
	 * @var string
	 */

	public static $url = '/';

	/**
	 * Maximum number of items per page
	 *
	 * @var integer
	 */

	public static $limit = 20;

	/**
	 * Generate links for pages
	 *
	 * @return string
	 */
	
	public static function links()
	{
		$all_pages    = ceil(self::$total  / self::$limit);
		$current_page = ceil(self::$offset / self::$limit);

		$string = '<nav class="paginator"><ul>';

		if($current_page > 1)
		{
			$string .= '<li>' . self::link("Previous", ($current_page - 1) * self::$limit) . '</li>';
		}

		$from_page = max($current_page - 3, 0);
		$to_page   = min($current_page + 3, $all_pages);

		for($i = $from_page; $i < $to_page; $i++)
		{
			$string .= '<li ' . ($current_page == $i ? 'class="current"' : '') . '>' . self::link($i + 1, $i * self::$limit) . '</li>';
		}

		if(($current_page + 1) !== $all_pages)
		{
			$string .= '<li>' . self::link("Next", ($current_page + 1) * self::$limit) . '</li>';
		}

		$string .= '</ul></nav>';

		return $string;
	}

	/**
	 * Generates one link
	 *
	 * @param  string  $text
	 * @param  integer $offset
	 * @return string
	 */

	public static function link($text, $offset)
	{
		return '<a href="' . self::$url . '?offset=' . $offset . '">' . $text . '</a>';
	}
}