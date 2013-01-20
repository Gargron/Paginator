<?php

/**
 * Copyright (c) 2012 Eugen Rochko
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights 
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
 * copies of the Software, and to permit persons to whom the Software is 
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
 * SOFTWARE.
 */

namespace Paginator;

/**
 * Simple pagination library
 *
 * @author Eugen Rochko <gargron@gmail.com>
 */

class Paginator
{
	/**
	 * Total number of items
	 *
	 * @var integer
	 */

	public $total;

	/**
	 * Current offset
	 *
	 * @var integer
	 */

	public $offset;

	/**
	 * Current base URL
	 *
	 * @var string
	 */

	public $url;

	/**
	 * Maximum number of items per page
	 *
	 * @var integer
	 */

	public $limit;

	/**
	 * $_GET data from the previous request
	 * 
	 * @var array
	 */
	
	public $inputArray;

	/**
	 * Create new instance of Paginator
	 * 
	 * @param string  $url
	 * @param integer $offset
	 * @param integer $limit
	 * @param integer $total
	 * @param array   $inputArray
	 */
	
	public function __construct($url, $offset, $limit, $total, $inputArray)
	{
		$this->offset     = $offset;
		$this->total      = $total;
		$this->limit      = $limit;
		$this->url        = $url;
		$this->inputArray = $inputArray;
	}

	/**
	 * Generate links for pages
	 *
	 * @return string
	 */
	
	public function links()
	{
		$all_pages    = ceil($this->total  / $this->limit);
		$current_page = ceil($this->offset / $this->limit);
		$from_page    = max($current_page - 3, 0);
		$to_page      = min($current_page + 3, $all_pages);

		$string = '<div class="pagination pagination-centered"><ul>';

		if($current_page > 3)
		{
			$string .= '<li>' . $this->link(__('pagination.first'), 0) . '</li>';
		}

		if($current_page > 0)
		{
			$string .= '<li>' . $this->link(__('pagination.previous'), ($current_page - 1) * $this->limit) . '</li>';
		}

		for($i = $from_page; $i < $to_page; $i++)
		{
			$string .= '<li ' . ($current_page == $i ? 'class="active"' : '') . '>' . $this->link($i + 1, $i * $this->limit) . '</li>';
		}

		if(($current_page + 1) < $all_pages)
		{
			$string .= '<li>' . $this->link(__('pagination.next'), ($current_page + 1) * $this->limit) . '</li>';
		}

		if(($current_page + 3) < $all_pages)
		{
			$string .= '<li>' . $this->link(__('pagination.last'), $all_pages * $this->limit - $this->limit) . '</li>';
		}

		$string .= '</ul></div>';

		return $string;
	}

	/**
	 * Generates one link
	 *
	 * @param  string  $text
	 * @param  integer $offset
	 * @return string
	 */

	protected function link($text, $offset)
	{
		$query_string = array_merge($this->inputArray, array('offset' => $offset));

		return '<a href="' . $this->url . '?' . http_build_query($query_string) . '">' . $text . '</a>';
	}
}
