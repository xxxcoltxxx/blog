<?php

namespace Utils;

use System\View;

class Pagination
{
    private $total;
    private $per_page;
    private $current_page;
    public $max_display_pages = 10;

    public function __construct($total_count, $per_page, $current_page)
    {
        $this->total = $total_count;
        $this->per_page = $per_page;
        $this->current_page = $current_page;
    }

    /**
     * @param $url_pattern /url/{page} - {page} заменится на номер страницы
     * @param bool|false $indicator
     * @return string
     * @throws \Exception
     */
    public function getPagination($url_pattern, $indicator = false)
    {
        $view = new View();
        return $view->template("pagination", [
            'total_pages'   => ceil($this->total / $this->per_page),
            'current_page'  => $this->current_page,
            'indicator'     => $indicator,
            'url_pattern'   => $url_pattern,
            'max_display_pages' => $this->max_display_pages
        ]);
    }
}