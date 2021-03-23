<?php


namespace App\DataFixtures;


use App\Entity\Telephone;
use Doctrine\Persistence\ObjectManager;

class TelephoneFixture extends AbstractFixture
{
    protected function loadData(ObjectManager $manager): void
    {
        $telephones = $this->faker->telephonesSortedByBrand;

        foreach ($telephones as $brand => $phones) {
            $this->createMany(count($phones), sprintf('phones_%s', $brand), function ($count) use ($brand, $phones) {
                return (new Telephone())
                    ->setName($phones[$count])
                    ->setReference($this->faker->ean8)
                    ->setBrand($brand)
                    ->setPrice($this->faker->randomFloat(2, 200, 900))
                    ->setDescription($this->faker->paragraph())
                ;
            });
        }

        $manager->flush();
    }
}
