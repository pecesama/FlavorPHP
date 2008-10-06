<?php

/**
  * Digg Style Pagination.
  *
  * The pagination algorithm is taken from Stranger Studios. Thanks!
  * http://www.strangerstudios.com/sandbox/pagination/diggstyle.php
  * 
  */

class pagination extends singleton {
	
	protected $registry;
	protected $path;
	protected $l10n;
	
    private $total_rows;
    private $limit;
    public $page;
    public $targetpage;
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];
		$this->l10n = l10n::getInstance();
	}
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}
	
    function init($tr, $page, $limit, $targetpage) {
		$this->total_rows   = (int) $tr;
		$this->page         = (int) $page;
		$this->limit        = (int) $limit;
		$this->targetpage   = (string) $targetpage;

        return $this->getPagination();          
    }


	private function getPagination($adjacents = 1) {
		$prev = $this->page - 1;
		$next = $this->page + 1;
		$lastpage = ceil($this->total_rows / $this->limit);
		$lpm1 = $lastpage - 1;	   
		$pagination = "";		   
		if ($lastpage > 1) {   
			$pagination .= "<div class=\"pagination\">";
				   
			//previous button
			if ($this->page > 1) {
				$pagination .= "<a href=\"". $this->targetpage. $prev ."\">".$this->l10n->__("Prev")."</a>";
			} else {
				$pagination .= "<span class=\"disabled\">".$this->l10n->__("Prev")."</span>";
			}
		   
			//pages   
			if ( $lastpage < 7 + ($adjacents * 2)) {
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if ($counter == $this->page) {
						$pagination .= '<span class="current">'.$counter.'</span>';
					} else {
				    	$pagination .= '<a href="'.$this->targetpage . $counter . '">'.$counter.'</a>';
					}
				}
			} elseif ($lastpage >= 7 + ($adjacents * 2)) {
				if ($this->page < 1 + ($adjacents * 3)) {
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
						if ($counter == $this->page) {
							$pagination .= '<span class="current">'.$counter.'</span>';
						} else {
							$pagination .= '<a href="'.$this->targetpage . $counter.'">'.$counter.'</a>';
						}
					}
					$pagination .= "...";
					$pagination .= '<a href="'.$this->targetpage . $lpm1 .'">'.$lpm1.'</a>';
					$pagination .= '<a href="'.$this->targetpage. $lastpage.'">'.$lastpage.'</a>';       
				} elseif ($lastpage - ($adjacents * 2) > $this->page && $this->page > ($adjacents * 2)) {
					$pagination .= '<a href="'.$this->targetpage.'1/">1</a>';
					$pagination .= '<a href="'.$this->targetpage.'2/">2</a>';
					$pagination .= '...';
					for ($counter = $this->page - $adjacents; $counter <= $this->page + $adjacents; $counter++) {
						if ($counter == $this->page) {
							$pagination .= '<span class="current">'.$counter.'</span>';
						} else {
							$pagination .= '<a href="'.$this->targetpage.$counter.'">'.$counter.'</a>';
						}
					}
					$pagination .= '...';
					$pagination .= '<a href="'.$this->targetpage.$lpm1.'">'.$lpm1.'</a>';
					$pagination .= '<a href="'.$this->targetpage.$lastpage.'">'.$lastpage.'</a>';
				} else {
					$pagination .= '<a href="'.$this->targetpage.'1/">1</a>';
					$pagination .= '<a href="'.$this->targetpage.'2/">2</a>';
					$pagination .= '...';
					for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
						if ($counter == $this->page) {
							$pagination .= '<span class="current">'.$counter.'</span>';
						} else {
							$pagination .= '<a href="'.$this->targetpage.$counter.'">'.$counter.'</a>';
						}
					}
				}
			}
		   
			//next button
			if ( $this->page < $counter - 1 ) {
				$pagination .= "<a href=\"".$this->targetpage . $next."\">".$this->l10n->__("Next")."</a>";
			} else {
				$pagination .= "<span class=\"disabled\">".$this->l10n->__("Next")."</span>";
			}
		   
			$pagination .= '</div>';
		}
		return $pagination;
	}
	
	
}
?>