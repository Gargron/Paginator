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

namespace Gargron;

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
	 * The number of currently fetched items
	 * Should help determine if there is a next page
	 * 
	 * @var integer
	 */
	
	public $currentFetched;

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
		if(is_null($this->total))
		{
			# Render simple next/previous links if the total count is not given
			return $this->unknown_links();
		}

		# Calculating page numbers from offsets and total items
		$all_pages    = ceil($this->total  / $this->limit);
		$current_page = ceil($this->offset / $this->limit);
		$from_page    = max($current_page - 3, 0);
		$to_page      = min($current_page + 3, $all_pages);

		$string = '<div class="pagination pagination-centered"><ul>';

		if($current_page > 3)
		{
			# Page we're on is 3 farther than the first one, so we need a "First" link
			$string .= '<li>' . $this->link(__('pagination.first'), 0) . '</li>';
		}

		if($current_page > 0)
		{
			# This is not the first page so we need a "Previous" link
			$string .= '<li>' . $this->link(__('pagination.previous'), ($current_page - 1) * $this->limit) . '</li>';
		}

		for($i = $from_page; $i < $to_page; $i++)
		{
			# Print the window of page links from three before current to three after current
			$string .= '<li ' . ($current_page == $i ? 'class="active"' : '') . '>' . $this->link($i + 1, $i * $this->limit) . '</li>';
		}

		if(($current_page + 1) < $all_pages)
		{
			# This is not the last page so we need a "Next" link
			$string .= '<li>' . $this->link(__('pagination.next'), ($current_page + 1) * $this->limit) . '</li>';
		}

		if(($current_page + 3) < $all_pages)
		{
			# These are not the last three pages so we need a "Last" link
			$string .= '<li>' . $this->link(__('pagination.last'), $all_pages * $this->limit - $this->limit) . '</li>';
		}

		$string .= '</ul></div>';

		return $string;
	}

	/**
	 * Generate next/previous links only because we don't know the
	 * total number of items/pages beforehand
	 * 
	 * @return string
	 */
	
	public function unknown_links()
	{
		$string = '<ul class="pager">';

		if($this->offset > 0)
		{
			# If the offset is not 0 then there should be a previous page
			$string .= '<li>' . $this->link(__('pagination.previous'), max($this->offset - $this->limit, 0)) . '</li>';
		}

		if($this->currentFetched > $this->limit)
		{
			# If we always fetch one more item than we display then we can guess if
			# there is a next page
			$string .= '<li>' . $this->link(__('pagination.next'), $this->offset + $this->limit) . '</li>';
		}

		$string .= '</ul>';

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
		# This little snippet will merge query paramaters from the existing request
		# to the link to the other pages to preserve them, so for example:
		#   example?sort=updated
		# becomes
		#   example?sort=updated&offset=20
		# The offset parameter will be overwritten however.
		$query_string = array_merge($this->inputArray, array('offset' => $offset));

		return '<a href="' . $this->url . '?' . http_build_query($query_string) . '">' . $text . '</a>';
	}
}
