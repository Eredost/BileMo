<?php


namespace App\Tests\Entity;

use App\Entity\Client;
use App\Tests\Entity\Traits\AssertTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClientTest extends KernelTestCase
{
    use AssertTrait;

    private function getEntity(): Client
    {
        return (new Client())
            ->setName('LDLC')
        ;
    }

    public function testValidEntity(): void
    {
        $this->assertHasErrors($this->getEntity());
    }

    public function testInvalidBlankName()
    {
        $this->assertHasErrors($this->getEntity()->setName(''), 1);
    }

    public function testInvalidLengthName()
    {
        $this->assertHasErrors($this->getEntity()->setName(str_repeat('*', 81)), 1);
    }
}
