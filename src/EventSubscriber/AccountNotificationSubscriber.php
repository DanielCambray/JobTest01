<?php

namespace App\EventSubscriber;

use App\Entity\Account;
use App\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Notifies about account creation.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 */
class AccountNotificationSubscriber implements EventSubscriberInterface
{
    private $mailer;
    private $sender;
    private $emailAdmin;
    private $templating;
    private $translator;

    public function __construct(\Swift_Mailer $mailer, $sender, $emailAdmin, \Twig_Environment $templating, TranslatorInterface $translator)
    {
        $this->mailer = $mailer;
        $this->translator = $translator;
        $this->sender = $sender;
        $this->emailAdmin = $emailAdmin;
        $this->templating = $templating;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::ACCOUNT_CREATED => array('onAccountCreated', 0),
        ];
    }

    public function onAccountCreated(GenericEvent $event): void
    {
        // Get the new account
        $account = $event->getSubject();

        // Create the message
        /*
        $body = $this->translator->trans('notification.account_created.description', [
            '%firstName%' => $account->getFirstName(),
        ]);*/
        $subject = $this->translator->trans('notification.account_created');

        // Send the confirmation to the user
        $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setTo($account->getEmail())
            ->setFrom($this->sender)
            ->setBody(
                $this->templating->render(
                    'emails/account_notification.html.twig',
                    array('account' => $account)
                ),
                'text/html'
            )
        ;

        $this->mailer->send($message);

        // Send the confirmation to the admin
        $message->setTo($this->emailAdmin);
        $this->mailer->send($message);
    }
}
