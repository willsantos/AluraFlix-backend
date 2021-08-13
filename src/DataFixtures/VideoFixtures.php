<?php

namespace App\DataFixtures;

use App\Entity\Videos;
use App\Repository\VideoCategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends Fixture implements DependentFixtureInterface
{

    private VideoCategoryRepository $repository;

    public function __construct(VideoCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0;$i<13;$i++){
            $video = new Videos();
            $video
                ->setTitle('Video'.$i)
                ->setCategoriaId($this->repository->find(mt_rand(1,4)))
                ->setDescription('Fictum, deserunt mollit anim laborum astutumque! Quisque placerat facilisis egestas cillum dolore. Nec dubitamus multa iter quae et nos invenerat. Contra legem facit qui id facit quod lex prohibet. Quam diu etiam furor iste tuus nos eludet?')
                ->setUrl('https://www.youtube.com/alura');

            $manager->persist($video);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            VideoCategoryFixtures::class
        ];
    }
}
