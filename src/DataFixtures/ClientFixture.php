<?php


namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;

class ClientFixture extends AbstractFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $clients = $this->faker->clients;

        $this->createMany(count($clients), 'clients', function ($count) use ($clients) {
            return (new Client())
                ->setName($clients[$count])
            ;
        });

        $manager->flush();
    }
}
