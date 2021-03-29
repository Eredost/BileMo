<?php


namespace App\Tests\Entity;

use App\Entity\Telephone;
use App\Tests\Entity\Traits\AssertTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TelephoneTest extends KernelTestCase
{
    use AssertTrait;

    private function getEntity(): Telephone
    {
        return (new Telephone())
            ->setName('Galaxy S41')
            ->setReference('09876432')
            ->setBrand('Samsung')
            ->setPrice(592.22)
            ->setDescription('Lorem dolor sit amet')
        ;
    }

    public function testValidEntity(): void
    {
        $this->assertHasErrors($this->getEntity());
    }

    public function testInvalidBlankName(): void
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
    }

    public function testInvalidLengthName(): void
    {
        $this->assertHasErrors($this->getEntity()->setName(str_repeat('*', 81)), 1);
    }

    public function testInvalidBlankReference(): void
    {
        $this->assertHasErrors($this->getEntity()->setReference(''), 1);
    }

    public function testInvalidLengthReference(): void
    {
        $this->assertHasErrors($this->getEntity()->setReference(str_repeat('0', 31)), 1);
    }

    public function testInvalidBlankBrand(): void
    {
        $this->assertHasErrors($this->getEntity()->setBrand(''), 1);
    }

    public function testInvalidLengthBrand(): void
    {
        $this->assertHasErrors($this->getEntity()->setBrand(str_repeat('*', 81)), 1);
    }

    public function testInvalidValuePrice(): void
    {
        $this->assertHasErrors($this->getEntity()->setPrice(-3), 1);
    }

    public function testInvalidLengthDescription(): void
    {
        $this->assertHasErrors($this->getEntity()->setDescription(str_repeat('*', 1001)), 1);
    }
}
