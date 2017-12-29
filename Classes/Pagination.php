<?php
/**
* Pagination
*/
class Pagination
{
	
	/**
	 * Base URL
	 *
	 * The page that we're linking to
	 *
	 * @var	string
	 */
	protected $base_url		= '';

	/**
	 * Prefix
	 *
	 * @var	string
	 */
	protected $prefix = '';

	/**
	 * Suffix
	 *
	 * @var	string
	 */
	protected $suffix = '';

	/**
	 * Total number of items
	 *
	 * @var	int
	 */
	protected $total_rows = 0;

	/**
	 * Number of links to show
	 *
	 * Relates to "digit" type links shown before/after
	 * the currently viewed page.
	 *
	 * @var	int
	 */
	protected $num_links = 2;

	/**
	 * Items per page
	 *
	 * @var	int
	 */
	public $per_page = 10;

	/**
	 * Current page
	 *
	 * @var	int
	 */
	public $cur_page = 0;

	/**
	 * Use page numbers flag
	 *
	 * Whether to use actual page numbers instead of an offset
	 *
	 * @var	bool
	 */
	protected $use_page_numbers = FALSE;

	/**
	 * First link
	 *
	 * @var	string
	 */
	protected $first_link = '&lsaquo; First';

	/**
	 * Next link
	 *
	 * @var	string
	 */
	protected $next_link = '&gt;';

	/**
	 * Previous link
	 *
	 * @var	string
	 */
	protected $prev_link = '&lt;';

	/**
	 * Last link
	 *
	 * @var	string
	 */
	protected $last_link = 'Last &rsaquo;';

	/**
	 * URI Segment
	 *
	 * @var	int
	 */
	protected $uri_segment = 0;


	/**
	 * LIMIT
	 *
	 * @var	string
	 */
	public $limit;


	/**
	 * LIMIT
	 *
	 * @var	string
	 */
	public $links;

	

	// function __construct()
	// {
	// 	# code...
	// }

	// --------------------------------------------------------------------

	/**
	 * Initialize Preferences
	 *
	 * @param	array	$params	Initialization parameters
	 * @return	PI_Pagination
	 */
	// public function initialize(array $params = array())
	// {
	// 	// isset($params['attributes']) OR $params['attributes'] = array();
	// 	// if (is_array($params['attributes']))
	// 	// {
	// 	// 	$this->_parse_attributes($params['attributes']);
	// 	// 	unset($params['attributes']);
	// 	// }


	// 	foreach ($params as $key => $val)
	// 	{
	// 		if (property_exists($this, $key))
	// 		{
	// 			$this->$key = $val;
	// 		}
	// 	}

	// 	return $this;
	// }

	/**
	 * Generate the pagination links
	 *
	 * @return	string
	 */
	public function create_links($countTotal='0', $items_per_page='20')
	{

		$output = "";
		$lastPage = '';
		$outputPage = '';

		$url = Url::fullUrl();
		$get_page_no = Replace::numeric(Input::get('p'));//Replace::numeric( end(explode('/', $url)) );


		if($countTotal == 0)
		{
			$outputPage = '';
		}
		else
		{
			$lastPage = ceil($countTotal / $items_per_page);

			if ( ! $get_page_no OR $get_page_no < 1)
			{
				$page_no = 1;
			}
			else if ($get_page_no > $lastPage)
			{
				$page_no = $lastPage;
			}
			else
			{
				$page_no = $get_page_no;
			}

			if ($get_page_no > $lastPage)
			{
				Redirect::to(Url::localUrl());
			}


			//Creating the links in between
			$centerPages = "";
			$sub1 = $page_no - 1;
			$sub2 = $page_no - 2;
			$add1 = $page_no + 1;
			$add2 = $page_no + 2;

			if($page_no == 1)
			{
				$centerPages .= '<span class="activePage">Page '.$page_no.' of '.$lastPage.'</span>';
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$add1.'" class="pn">'.$add1.'</a>&nbsp;';
			}
			else if($page_no == $lastPage)
			{
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$sub1.'" class="pn">'.$sub1.'</a>&nbsp;';
				$centerPages .= '<span class="activePage">Page '.$page_no.' of '.$lastPage.'</span>';
			}
			else if($page_no > 2 && $page_no < ($lastPage - 1))
			{
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$sub2.'" class="pn">'.$sub2.'</a>&nbsp;';
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$sub1.'" class="pn">'.$sub1.'</a>&nbsp;';
				$centerPages .= '<span class="activePage">Page '.$page_no.' of '.$lastPage.'</span>';
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$add1.'" class="pn">'.$add1.'</a>&nbsp;';
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$add2.'" class="pn">'.$add2.'</a>&nbsp;';
			}
			else if($page_no > 1 && $page_no < $lastPage)
			{
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$sub1.'" class="pn">'.$sub1.'</a>&nbsp;';
				$centerPages .= '<span class="activePage">Page '.$page_no.' of '.$lastPage.'</span>';
				$centerPages .= '&nbsp;<a href="'.$url.'?p='.$add1.'" class="pn">'.$add1.'</a>&nbsp;';
			}

			if($lastPage == 0)
			{
				$outputPage = "";
			}
			else if($lastPage != 1)
			{
				$outputPage = '';

				$prev = $page_no - 1;
				$next = $page_no + 1;

				$outputPage .= '<div id="pagination"><div><div>';

				if($page_no != 1)
				{
				  $outputPage .= '<a href="'.$url.'?p='.$prev.'" class="prev">< Prev </a>';
				}

				$outputPage .= '<span class="centerPages">'.$centerPages.'</span>';

				if($page_no != $lastPage)
				{
				  $outputPage .= '<a href="'.$url.'?p='.$next.'" class="next">Next ></a>';
				}

				$outputPage .= '</div></div></div>';
			}


			//Setting the LIMIT
			$lim1 = ($page_no - 1) * $items_per_page;
			$lim2 = $items_per_page;

			$this->limit = 'LIMIT '.$lim1.', '.$lim2;

		}

		return $outputPage;


	}

	public function limit()
	{
		return $this->limit;
	}

	
}