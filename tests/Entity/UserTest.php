<?php


namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\Entity\Traits\AssertTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    use AssertTrait;

    private function getEntity(): User
    {
        return (new User())
            ->setEmail('email@example.org')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword('Pas3word')
            ->setFullname('John Doe')
        ;
    }

    public function testValidEntity(): void
    {
        $this->assertHasErrors($this->getEntity());
    }

    public function testInvalidBlankEmail(): void
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 1, 'create');
    }

    public function testInvalidValuesEmail(): void
    {
        $entity = $this->getEntity();

        $this->assertHasErrors($entity->setEmail('@example.org'), 1, 'create');
        $this->assertHasErrors($entity->setEmail('hello@example'), 1, 'create');
        $this->assertHasErrors($entity->setEmail('hello@.org'), 1, 'create');
    }

    public function testInvalidValueRoles(): void
    {
        $this->assertHasErrors($this->getEntity()->setRoles(['ROLE_SOMETHING']), 1, 'create');
    }

    public function testInvalidBlankPassword(): void
    {
        $this->assertHasErrors($this->getEntity()->setPassword(''), 1, 'create');
    }

    public function testInvalidLengthPassword(): void
    {
        $entity = $this->getEntity();

        $this->assertHasErrors($entity->setPassword('Pa3sw'), 1, 'create');
        $this->assertHasErrors($entity->setPassword(str_repeat('Pas3', 11)), 1, 'create');
    }

    public function testInvalidValuesPassword(): void
    {
        $entity = $this->getEntity();

        $this->assertHasErrors($entity->setPassword('Password'), 1, 'create');
        $this->assertHasErrors($entity->setPassword('pas3word'), 1, 'create');
        $this->assertHasErrors($entity->setPassword('PAS3WORD'), 1, 'create');
    }

    public function testInvalidBlankFullname(): void
    {
        $this->assertHasErrors($this->getEntity()->setFullname(''), 1, 'create');
    }

    public function testInvalidLengthFullname(): void
    {
        $this->assertHasErrors($this->getEntity()->setFullname(str_repeat('*', 81)), 1, 'create');
    }
}
