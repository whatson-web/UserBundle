<?php

namespace WH\UserBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="WH\UserBundle\Repository\UserRepository")
 *
 * Class User
 *
 * @package WH\UserBundle\Entity
 */
class User extends BaseUser
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * User constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		$this->addRole('ROLE_USER');
		$this->enabled = true;
		$this->plainPassword = self::generateStrongPassword();
	}

	/**
	 * @param string $email
	 *
	 * @return $this
	 */
	public function setEmail($email)
	{
		$this->email = $email;
		$this->setUsername($email);

		return $this;
	}

	/**
	 * @param int    $length
	 * @param bool   $add_dashes
	 * @param string $available_sets
	 *
	 * @return string
	 */
	static public function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
	{
		$sets = array();
		if (strpos($available_sets, 'l') !== false) {
			$sets[] = 'abcdefghjkmnpqrstuvwxyz';
		}
		if (strpos($available_sets, 'u') !== false) {
			$sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
		}
		if (strpos($available_sets, 'd') !== false) {
			$sets[] = '23456789';
		}
		if (strpos($available_sets, 's') !== false) {
			$sets[] = '!@#$%&*?';
		}
		$all = '';
		$password = '';
		foreach ($sets as $set) {
			$password .= $set[array_rand(str_split($set))];
			$all .= $set;
		}
		$all = str_split($all);
		for ($i = 0; $i < $length - count($sets); $i++) {
			$password .= $all[array_rand($all)];
		}
		$password = str_shuffle($password);
		if (!$add_dashes) {
			return $password;
		}
		$dash_len = floor(sqrt($length));
		$dash_str = '';
		while (strlen($password) > $dash_len) {
			$dash_str .= substr($password, 0, $dash_len) . '-';
			$password = substr($password, $dash_len);
		}
		$dash_str .= $password;

		return $dash_str;
	}
}

