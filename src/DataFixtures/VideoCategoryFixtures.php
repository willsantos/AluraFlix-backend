<?php

namespace App\DataFixtures;

use App\Entity\VideoCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VideoCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new VideoCategory();
        $category
            ->setTitle('Livre')
            ->setColor('#0f8046');
        $manager->persist($category);


        for($i=0;$i<10;$i++){
            $category = new VideoCategory();
            $category
                ->setTitle('Categoria'.$i++)
                ->setColor('#ACD'.mt_rand(0,9).'F'.mt_rand(0,9));
            $manager->persist($category);
        }

        $manager->flush();
    }
}
