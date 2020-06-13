<?php


namespace App\EventListener;

use App\Entity\Exchange;
use App\Utils\FixerApi;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ExchangeEventSubscriber implements EventSubscriber
{
    /**
     * @var FixerApi
     */
    private $fixerApi;

    public function __construct(FixerApi $fixerApi)
    {
        $this->fixerApi = $fixerApi;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $exchange = $args->getObject();

        if (!$exchange instanceof Exchange){
            return;
        }

        $fixerExchanges = $this->fixerApi->getExchangesByDate($exchange);

        $rates = $fixerExchanges->getRate();

        //First convert in base currency
        $baseValue = $exchange->getOriginValue()*$rates[$exchange->getOriginCurrency()];

        //Covert the base currency in the final value
        $exchange->setFinalCurrency($baseValue * $rates[$exchange->getDestinyCurrency()]);
    }
}