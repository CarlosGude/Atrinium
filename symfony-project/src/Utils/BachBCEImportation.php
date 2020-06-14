<?php


namespace App\Utils;


use App\Entity\BCEImport;
use App\Entity\FixerData;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpClient\HttpClient;

class BachBCEImportation
{
    protected const BCE_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-hist.xml';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function import(): int
    {
        $count = 0;
        foreach ($this->getDataFromBCE()['Cube'] as $item){
            foreach ($item as $element){
                $time = (new DateTime($element['@attributes']['time']))->setTime(0,0,0);
                if($this->entityManager->getRepository(BCEImport::class)->findOneBy(['date' => $time])){
                    continue;
                }
                $this->saveBCEImport($time,$element);
                $count++;
            }
        }
        $this->entityManager->flush();
        return $count;
    }

    protected function saveBCEImport(DateTime $time, array $element): void
    {
        $bceImport = new BCEImport();
        $bceImport
            ->setDate($time)
            ->setBaseCurrency('EUR')
            ->setRate($element['Cube'])
        ;

        $this->entityManager->persist($bceImport);
    }

    protected function getDataFromBCE(): array
    {
        ini_set('memory_limit', '2048M');
        $client = HttpClient::create();
        $response = $client->request('GET', self::BCE_URL);

        $xml = simplexml_load_string($response->getContent());
        $json  = json_encode($xml);

        return json_decode($json, true);
    }
}