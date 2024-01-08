<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userJL = new User();
        $userJL->setUsername("Jeanloutre007")
            ->setEmail("jean.loutre@centrale-med.fr")
            ->setPromo(2006)
            ->setPassword("leS4umonC3stBon!")
            ->setFirstName("Jean")
            ->setLastName("Loutre")
            ->setRoles(['ROLE_USER']);
        $manager->persist($userJL);
        $userM = new User();
        $userM->setUsername("Lucmoineaudu91")
            ->setEmail("luc.moineau@centrale-med.fr")
            ->setPromo(2007)
            ->setPassword("piouPi0u!")
            ->setFirstName("Luc")
            ->setLastName("Moineau")
            ->setRoles(['ROLE_USER']);
        $manager->persist($userM);

        $A1 = new Course();
        $A1->setName("1A")
            ->setIsUE(false)
            ->setIsOptionGroup(false);
        $manager->persist($A1);
        $alpha = new Course();
        $alpha->setParent($A1)
            ->setIsUE(false)
            ->setIsOptionGroup(false)
            ->setName("Alpha");
        $manager->persist($alpha);
        $langues = new Course();
        $langues->setName("Langues")
            ->setParent($A1)
            ->setIsUE(false)
            ->setIsOptionGroup(true);
        $manager->persist($langues);
        $info = new Course();
        $info->setName("Informatique")
            ->setParent($alpha)
            ->setIsUE(true)
            ->setIsOptionGroup(false);
        $manager->persist($info);
        $meca = new Course();
        $meca->setName("Mécanique")
            ->setParent($alpha)
            ->setIsUE(true)
            ->setIsOptionGroup(false);
        $manager->persist($meca);
        $eco = new Course();
        $eco->setName("Economie-Gestion")
            ->setParent($alpha)
            ->setIsUE(true)
            ->setIsOptionGroup(false);
        $manager->persist($eco);
        $phi = new Course();
        $phi->setName("Physique")
            ->setParent($alpha)
            ->setIsUE(true)
            ->setIsOptionGroup(false);
        $manager->persist($phi);

        $feeback = new Feedback();
        $feeback->setCourse($phi)
            ->setAuthor($userJL)
            ->setComment("Faut travailler !")
            ->setTitle("Physique")
            ->setDifficulty(5)
            ->setInterest(4)
            ->setOverall(3)
            ->setValid(true);
        $manager->persist($feeback);
        $feeback = new Feedback();
        $feeback->setCourse($meca)
            ->setAuthor($userJL)
            ->setComment("La méca flu c'est trop dur !")
            ->setTitle("Méca")
            ->setDifficulty(2)
            ->setInterest(2)
            ->setOverall(2)
            ->setValid(false);
        $manager->persist($feeback);
        $feeback = new Feedback();
        $feeback->setCourse($eco)
            ->setAuthor($userM)
            ->setComment("Quelqu'un a compris ce qu'on faisait en éco ?")
            ->setTitle("Eco-G")
            ->setDifficulty(2)
            ->setInterest(1)
            ->setOverall(1)
            ->setValid(false);
        $manager->persist($feeback);

        $manager->flush();
    }
}
