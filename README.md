# Projet : toDoList
- Création du projet et installation du package Symfony mini via :
```bash
symfony new TodoList --version=5.2
```
- Voir les composants requis au fur et a mesure
- Gestion de données avec système CRUD
- Class `Formulaires` et contrôles (contraintes)
- 2 `Entity` aved relation `many to one`
- Déploiement du `Heroku`

### Etape 1 : DB

- S'assurer que la config est bonne via : 
```bash
symfony check:requirements
```

- Voir les commandes disponibles via un :
```bash
symfony console
```

- Installer des Doctrines ORM et les maker/bundle qui ne sont pas présent à la base dans la version mini de Symfony :
doc : https://symfony.com/doc/current/doctrine.html#installing-doctrine
```Bash
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle
```
Le fichier `.env` a été modifié
Je modifie la ligne MySQL pour la renomer comme suit :
```bash
# DATABASE_URL="mysql://root:@127.0.0.1:3306/db_todolist"
```
- Création de la DB : `db_todolist`
```bash
symfony console doctrine:database:create
```

### Etape 2 : ENTITY

- Une `Todo` appartient à une catégorie
- Une `Category` contient 0 ou plusieurs `Todo`

    - Category(name)
    - ToDo(title(string), content(text), created_at(datetime), update_at(datetime), date_for(datetime), category)

#### Make it :

```bash
symfony console make:entity
# Pour ToDo et le champ category qui représente la relation à la table mère catégorie :
type = relation
```

#### Migration :
```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

#### Fixtures:
```bash
composer require --dev orm-fixtures
composer require --dev doctrine/doctrine-fixtures-bundle
```

#### Alimenter les tables
- Voir comment définir les dates de création et de maj dès la création d'une ToDo.
- Constructeur de la classe Entity `ToDo` 

### Analyse
1.  La table Category doit être remplie en premier
    1.  On part d'un tableau de catégories
    2.  Pour chaque catégorie je veux l'enregistrer dans la table physique
        1.  Sous Symfony tout passe par l'objet -> class `Category`
2.  On créer un Objet `Todo`.
    1.  La méthode `setCategory()` qui a besoin d'un objet `Category` comme argument.

### Controllers

#### Test Controllers
- L'objectif est de voir le format de rendu que propose le Controller, sachant que **Twig n'est pas installé.**

```bash
symfony console make:controller Test
```

#### Install Twig 
```bash
composer require twig
```
### Controller Todo
```bash
symfony console make:controller Todo
# Maintenant il créer un View dans un nouveau dossier `Template`
```
