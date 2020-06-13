<?php


namespace App\EventListener;

use App\Entity\User;
use App\Utils\FixerApi;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserEventSubscriber implements EventSubscriber
{
    /**
     * @var FixerApi
     */
    private $fixerApi;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $user = $args->getObject();

        if (!$user instanceof User){
            return;
        }

        $user->setPassword($this->encoder->encodePassword($user,$user->getPassword()));

    }
}