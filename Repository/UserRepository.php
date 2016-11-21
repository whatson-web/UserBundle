<?php

namespace WH\UserBundle\Repository;

use WH\LibBundle\Repository\BaseRepository;

/**
 * Class UserRepository
 *
 * @package WH\UserBundle\Repository
 */
class UserRepository extends BaseRepository
{

	/**
	 * @return string
	 */
	public function getEntityNameQueryBuilder()
	{
		return 'user';
	}
}
