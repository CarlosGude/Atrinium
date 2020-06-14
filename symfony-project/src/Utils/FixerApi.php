<?php

namespace App\Utils;

use App\Entity\BCEImport;
use App\Entity\Exchange;
use App\Entity\FixerData;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class FixerApi
{
    protected const API_KEY = 'c6f302c03200ed107d0c7a32bd32ba61';
    protected const API_URL = 'http://data.fixer.io/api/DATE?access_key='.self::API_KEY;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getExchangesByDate(Exchange $exchange): FixerData
    {
        /** @var DateTime $date */
        $date = $exchange->getDate();

        if (!$date) {
            throw new RuntimeException('The date can not be null.');
        }

        $date = $date->setTime(0,0,0);

        $fixerData = $this->getFixerData($exchange, $date);
        $bceData = $this->entityManager->getRepository(BCEImport::class)->findOneBy(['date' => $date]);

        if (!$bceData){
            return $fixerData;
        }

        $bceRates = $this->getBCERates($bceData,$exchange);

        if(empty($bceRates)){
            return $fixerData;
        }

        $rates = $fixerData->getRate();

        if(isset($bceRates['originCurrency']) && $rates[$exchange->getOriginCurrency()] !== $bceRates['originCurrency']){
            $rates[$exchange->getOriginCurrency()] = $bceRates['originCurrency'];
        }

        if(isset($bceRates['destinyCurrency']) && $rates[$exchange->getOriginCurrency()] !== $bceRates['destinyCurrency']){
            $rates[$exchange->getDestinyCurrency()] = $bceRates['destinyCurrency'];
        }

        return $fixerData;
    }

    /**
     * @param Exchange $exchange
     * @param DateTime $date
     * @return FixerData
     */
    public function getFixerData(Exchange $exchange,DateTime $date): FixerData
    {
        $fixerData = $this->entityManager->getRepository(FixerData::class)->findOneBy([
            'date' => $date,
        ]);

        return ($fixerData) ?: $this->setFixerData($date);
    }

    protected function setFixerData(DateTime $date): FixerData
    {
        $client = HttpClient::create();
        $response = $client->request(
            'GET',
            str_replace('DATE', $date->format('Y-m-d'), self::API_URL)
        );

        $raw = json_decode($response->getContent(), true);

        $fixerData = new FixerData();

        $fixerData
            ->setDate($date)
            ->setBaseCurrency($raw['base'])
            ->setRate($raw['rates'])
        ;

        $this->entityManager->persist($fixerData);

        return $fixerData;
    }

    protected function getBCERates(BCEImport $BCEImport, Exchange $exchange): array
    {
        $data = array();
        foreach ($BCEImport->getRate() as $rate){
            if($exchange->getOriginCurrency() === $rate['@attributes']['currency']){
                $data[] = ['originCurrency' => $rate['@attributes']['rate']];
            }
            if($exchange->getDestinyCurrency() === $rate['@attributes']['currency']){
                $data[] = ['destinyCurrency' => $rate['@attributes']['rate']];
            }
        }

        return $data;
    }
}
