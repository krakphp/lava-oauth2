<?php

namespace Krak\LavaOAuth2\Model;

use Doctrine\ORM\EntityManagerInterface;

class Seed
{
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function seed(callable $seed) {
        $seed($this->em);
        $this->em->flush();
    }
}
