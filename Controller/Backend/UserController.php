<?php

namespace WH\UserBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WH\BackendBundle\Controller\Backend\BaseController;
use WH\UserBundle\Entity\User;

/**
 * @Route("/admin/users")
 *
 * Class UserController
 *
 * @package WH\UserBundle\Controller\Backend
 */
class UserController extends BaseController
{

	public $bundlePrefix = 'WH';
	public $bundle = 'UserBundle';
	public $entity = 'User';

	/**
	 * @Route("/index/", name="bk_wh_user_user_index")
	 *
	 * @param Request $request
	 *
	 * @return string
	 */
	public function indexAction(Request $request)
	{
		$indexController = $this->get('bk.wh.back.index_controller');

		return $indexController->index($this->getEntityPathConfig(), $request);
	}

	/**
	 * @Route("/create/", name="bk_wh_user_user_create")
	 *
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function createAction(Request $request)
	{
		$createController = $this->get('bk.wh.back.create_controller');

		return $createController->create($this->getEntityPathConfig(), $request);
	}

	/**
	 * @Route("/update/{id}", name="bk_wh_user_user_update")
	 *
	 * @param         $id
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function updateAction($id, Request $request)
	{
		$updateController = $this->get('bk.wh.back.update_controller');

		return $updateController->update($this->getEntityPathConfig(), $id, $request);
	}

	/**
	 * @Route("/delete/{id}", name="bk_wh_user_user_delete")
	 *
	 * @param         $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function deleteAction($id)
	{
		$deleteController = $this->get('bk.wh.back.delete_controller');

		return $deleteController->delete($this->getEntityPathConfig(), $id);
	}

	/**
	 * @Route("/reset_password/{id}", name="bk_wh_user_user_resetpassword")
	 *
	 * @param         $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function resetPasswordAction($id)
	{
		$em = $this->get('doctrine')->getManager();

		$user = $em->getRepository('WHUserBundle:User')->get(
			'one',
			array(
				'conditions' => array(
					'user.id' => $id,
				),
			)
		);

		if ($user) {
			$newPassword = User::generateStrongPassword();

			$user->setPlainPassword($newPassword);
			$this->get('fos_user.user_manager')->updateUser($user);

			$this->get('bk.wh_user.mailer')->sendResettingPasswordMessage($user, $newPassword);
		}

		return $this->redirect($this->getActionUrl($this->getEntityPathConfig(), 'index'));
	}

	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function flapColumnAction()
	{
		$user = $this->getUser();

		return $this->render(
			'@WHUser/Backend/User/flap-column.html.twig',
			array(
				'user' => $user,
			)
		);
	}

}
