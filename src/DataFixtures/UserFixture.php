<?php


namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends AbstractFixture implements DependentFixtureInterface
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function loadData(ObjectManager $manager): void
    {
        /** @var Client[] $clients */
        $clients = $this->getReferences('clients');

        foreach ($clients as $client) {
            $this->createMany(2, sprintf('clients_%s', $client->getName()), function ($count) use ($client) {
                $user = (new User())
                    ->setFullname($this->faker->name)
                    ->setEmail($this->faker->safeEmail)
                    ->setRoles(($count === 0) ? ['ROLE_ADMIN']: ['ROLE_USER'])
                    ->setClient($client)
                ;
                $encodedPassword = $this->encoder->encodePassword($user, 'Pas3word');
                $user->setPassword($encodedPassword);

                return $user;
            });
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ClientFixture::class,
        ];
    }
}
