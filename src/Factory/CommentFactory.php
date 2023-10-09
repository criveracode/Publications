<?php

namespace App\Factory;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;


final class CommentFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'content' => self::faker()->text(),
            'post'    => PostFactory::new(),
            'user'    => UserFactory::random()
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Comment $comment): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Comment::class;
    }
}
