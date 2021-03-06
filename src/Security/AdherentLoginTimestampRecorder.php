<?php

namespace AppBundle\Security;

use AppBundle\Entity\Adherent;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class AdherentLoginTimestampRecorder implements EventSubscriberInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();

        // OAuth calls are not login attempts
        if ($token instanceof PostAuthenticationGuardToken && 'api_oauth' === $token->getProviderKey()) {
            return;
        }

        if (!$token instanceof UsernamePasswordToken) {
            throw new \RuntimeException(sprintf('Authentication token must be a %s instance.', UsernamePasswordToken::class));
        }

        $user = $token->getUser();
        if ($user instanceof Adherent) {
            $user->recordLastLoginTime();
            $this->manager->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onSecurityInteractiveLogin',
        ];
    }
}
