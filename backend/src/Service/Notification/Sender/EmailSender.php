<?php

namespace App\Service\Notification\Sender;

use App\Entity\Notification;
use App\Enum\NotificationLogStatus;
use App\Enum\NotificationType;
use App\Service\Notification\Renderer\NotificationRendererInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class EmailSender implements NotificationSenderInterface
{

    private const string NAME = 'email';

    public function __construct(
        #[Autowire('%email.sender%')]
        private string $emailSender,
        private MailerInterface             $mailer,
        private NotificationRendererInterface         $renderer,
        private NotificationRendererInterface         $textRenderer
    ) {
    }

    public function supports(Notification $notification): bool
    {
        return in_array($notification->getType(), [NotificationType::CONTACT, NotificationType::SUGGESTION, NotificationType::REPORT]);
    }

    public function send(Notification $notification): NotificationSenderResult
    {
        $content = $this->renderer->render($notification, $this);

        $email = new Email()
            ->from($this->emailSender)
            ->to($notification->getRecipientEmail())
            ->priority(Email::PRIORITY_HIGH)
            ->subject($notification->getSubject())
            ->text($this->textRenderer->render($notification, $this))
            ->html($content);

        try {
            $this->mailer->send($email);
            return new NotificationSenderResult(NotificationLogStatus::SENT, 'Email sent');
        } catch (TransportExceptionInterface $exception) {
            return new NotificationSenderResult(NotificationLogStatus::ERROR, 'Email could not be sent ' . $exception->getMessage());
        }
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
