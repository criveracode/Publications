<?php

namespace App\Factory;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;


final class UserFactory extends ModelFactory
{

    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        parent::__construct();
        $this->hasher = $hasher;
    }

    protected function getDefaults(): array
    {
        return [
            'email' => self::faker()->email(),
            'name' => self::faker()->name(),
            'password' => '123456789',
            'roles' => ['ROLE_USER'],
        ];
    }

    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function(User $user): void {
                 $user->setPassword(
                     $this->hasher->hashPassword($user, $user->getPassword())
                 );
            });
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
