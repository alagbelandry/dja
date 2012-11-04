<?php

namespace LeDjassa\AdsBundle\Services;
use Knp\Component\Pager\Paginator as KnpPaginator;

use \Criteria;

class Paginator
{
	protected $paginator;

    public function __construct(KnpPaginator $paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * Return pagination
     * @param Criteria $criteria query
     * @param int $page page request
     * @param int $limit limit of result
     * @return object pagination
     */
    public function getPagination(Criteria $criteria , $page, $limit)
    {
    	return $this->paginator->paginate(
            $criteria,
            $page,
            $limit
        );
    }
}