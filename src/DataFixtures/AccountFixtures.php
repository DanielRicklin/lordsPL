<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Guild;
use App\Entity\Image;
use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        
        $user = new User();
        $user->setEmail($faker->email);
        $user->setPassword('$2y$13$mwRHVQ1.KkSsNTDTkVlBlOVLd2uCz4j3BAJkYapylq7X5iMxYtwnK');
        $user->setRoles(["ROLE_USER"]);
        $manager->persist($user);

        for($i = 1; $i <= 3; $i++){
            $guild = new Guild();
            $guild->setName($faker->word);
            $guild->setTag($faker->randomNumber(3));

            $manager->persist($guild);

            $manager->persist($guild);
            for($j = 1; $j <= 10; $j++){
                $account = new Account();
                $account->setGuild($guild);
                $account->setName($faker->word);
                $account->setDate(new \DateTime());
                $account->setMight($faker->numberBetween($min = 10000000, $max = 1000000000));

                $manager->persist($account);

                for($k = 1; $k <= 3; $k++){
                    $image = new Image();
                    $image->setAccount($account);
                    $image->setlink("https://via.placeholder.com/150");

                    $manager->persist($image);
                }
            }
        }

        $manager->flush();
    }
}
