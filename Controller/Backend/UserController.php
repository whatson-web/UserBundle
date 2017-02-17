<?php

namespace WH\UserBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use WH\BackendBundle\Controller\Backend\BaseController;
use UserBundle\Entity\User;

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
     * @Route("/reset_password/{id}", name="bk_wh_user_user_resetpassword")
     *
     * @param         $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetPasswordAction($id)
    {
        $em = $this->get('doctrine')->getManager();

        $user = $em->getRepository('UserBundle:User')->get(
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
