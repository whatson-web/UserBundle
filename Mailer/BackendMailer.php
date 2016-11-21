<?php

namespace WH\UserBundle\Mailer;

use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class BackendMailer
 *
 * @package WH\UserBundle\Mailer
 */
class BackendMailer extends TwigSwiftMailer implements MailerInterface
{

	/**
	 * {@inheritdoc}
	 */
	public function sendConfirmationEmailMessage(UserInterface $user)
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendResettingPasswordMessage(UserInterface $user, $newPassword)
	{
		$context = array(
			'user'        => $user,
			'loginUrl'    => $this->router->generate(
				'bk_wh_user_security_login',
				array(),
				UrlGeneratorInterface::ABSOLUTE_URL
			),
			'newPassword' => $newPassword,
		);

		$this->sendMessage(
			'@WHUser/Backend/Email/resetting-new-password.html.twig',
			$context,
			$this->parameters['from_email']['resetting'],
			(string)$user->getEmail()
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function sendResettingEmailMessage(UserInterface $user)
	{
		$url = $this->router->generate(
			'bk_wh_user_resetting_reset',
			array('token' => $user->getConfirmationToken()),
			UrlGeneratorInterface::ABSOLUTE_URL
		);

		$context = array(
			'user'            => $user,
			'confirmationUrl' => $url,
		);

		$this->sendMessage(
			'@WHUser/Backend/Email/resetting-link-email.html.twig',
			$context,
			$this->parameters['from_email']['resetting'],
			(string)$user->getEmail()
		);
	}
}
