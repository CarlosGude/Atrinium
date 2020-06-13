<?php
namespace App\Utils;

use App\Entity\Exchange;
use App\Entity\FixerData;
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

    /**
     * @param Exchange $exchange
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getExchangesByDate(Exchange $exchange): FixerData
    {
        $client = HttpClient::create();

        $date = $exchange->getDate();

        if(!$date){
            throw new RuntimeException('The date can not be null.');
        }

        $fixerData = $this->entityManager->getRepository(FixerData::class)->findOneBy([
            'date' =>$date
        ]);

        if ($fixerData){
            return $fixerData;
        }

        $response = $client->request(
            'GET',
            str_replace('DATE',$date->format('Y-m-d'),self::API_URL)
        );

        $arrayResponse = json_decode($response->getContent(),true);

        $fixerData = new FixerData();

        $fixerData
            ->setDate($date)
            ->setBaseCurrency($arrayResponse['base'])
            ->setRate($arrayResponse['rates'])
        ;

        $this->entityManager->persist($fixerData);

        return $fixerData;
    }
}