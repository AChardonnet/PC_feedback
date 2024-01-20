<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    private $userPaswordUserInterface;

    public function __construct(UserPasswordHasherInterface $userPaswordUserInterface)
    {
        $this->userPaswordUserInterface = $userPaswordUserInterface;
    }
    public function load(ObjectManager $manager): void
    {
        $userJL = new User();
        $userJL->setUsername("Jeanloutre007")
            ->setEmail("jean.loutre@centrale-med.fr")
            ->setPromo(2006)
            ->setPassword(
                $this->userPaswordUserInterface->hashPassword(
                    $userJL, "leS4umonC3stBon!"))
            ->setFirstName("Jean")
            ->setLastName("Loutre")
            ->setRoles(['ROLE_USER']);
        $manager->persist($userJL);
        $userM = new User();
        $userM->setUsername("Lucmoineaudu91")
            ->setEmail("luc.moineau@centrale-med.fr")
            ->setPromo(2007)
            ->setPassword(
                $this->userPaswordUserInterface->hashPassword(
                    $userJL,"piouPi0u!"))
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
            ->setDate(new \DateTime('2011-06-05 12:15:00'))
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
            ->setDate(new \DateTime('2009-01-22 01:23:24'))
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
            ->setDate(new \DateTime('2013-08-18 15:33:33'))
            ->setDifficulty(2)
            ->setInterest(1)
            ->setOverall(1)
            ->setValid(false);
        $manager->persist($feeback);

        $manager->flush();
    }
}
