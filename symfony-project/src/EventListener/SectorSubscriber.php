<?php

namespace App\EventListener;

use App\Entity\Sector;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use RuntimeException;
use Symfony\Contracts\Translation\TranslatorInterface;

class SectorSubscriber implements EventSubscriber
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    public function getSubscribedEvents(): array
    {
        return [
            Events::preRemove,
        ];
    }

    public function preRemove(LifecycleEventArgs $args): void
    {
        $sector = $args->getObject();

        if (!$sector instanceof Sector) {
            return;
        }

        if($sector->getCompanies()->count() > 0){
            throw new RuntimeException($this->translator->trans('error.delete'));
        }
    }
}
