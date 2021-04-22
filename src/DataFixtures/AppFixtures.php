<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Todo;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        #1 Tableau des catégories
        $category = ['Pro', 'Perso', 'Important'];
        # Je stock tous les Obj créé dans la boucle dans un tableau :
        $tabObjCategory = [];
        foreach ($category as $c) {
            $cat = new Category();
            $cat->setName($c);
            $manager->persist($cat);
            $tabObjCategory[] = $cat;
        }
        #2 Créer autant d'objet de type Category qu'il y en a dans le tableau


        #3 Créer une ou plusieurs ToDO
        $todo = new Todo();
        $todo
            ->setTitle('Init projet')
            ->setContent('Détail du projet')
            ->setDateFor(new DateTime('Europe/paris'))
            ->setCategory($tabObjCategory[0])
            ;
            $manager->persist($todo);

    $manager->flush();
    }
}
