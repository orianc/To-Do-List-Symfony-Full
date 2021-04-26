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
### La page d'accueil

Le controller va récup notre premier enregirstrement de la table To DO et le passer à la vue `todo/index`
La mise en forme est gérée par des tables Bootstrap

### La page détail

1. Une méthode et sa route

    ```php
        /**
         * @Route("/todo/{id}", name="app_todo_show")
         */
        public function show($id, TodoRepository $todoRepository): Response
        {
            //dd($id);
            $todo = $todoRepository->find($id);
            return $this->render('todo/show.html.twig', [
                'todo' => $todo
            ]);
        }
    ```
2. Une vue dans template Todo
3. Le lien au niveau du bouton


### Generate Form

#### Install
```bash
    composer require form validator
```

#### Generate Form
##### Etape 1 :

Générer la class `TodoFormType`
```bash
    symfony console make:form
```

##### Etape 2 :
Création de la méthode `TodoController@create()`.
On va créer le lien du bouton 'New Todo List' pour tester le chemin  jsuqu'à la view `'todo/create.html.twig'`
Si besoin installer le profiler pour bugfix

##### Si problème de route :
1.  Voir la forme des urls et des routes.
```bash
composer require --dev symfony/profiler-pack
# Si problème de route :
symfonny console debug:router
```

1. L'ordre de placement des méthodes peut influer.
2. La possibilité d'ajouter un paramètre `priority = int`. 


##### Etape 3 :
Gestion du formulaire dans la méthode adéquate du controller.
```php
    /**
     * @Route("/todo/create", name="app_todo_create")
     * @return void
     */
    public function create(): Response
    {
        $todo = new Todo;
        $form = $this->createForm(TodoFormType::class, $todo);
        return $this->render('todo/create.html.twig', [
            'newTodoForm' => $form->createView()
        ]);
    }

```
Affichage du fromulaire dans la view

Améliorer le visuel avec : 
```yaml
form_themes: ['bootstrap_4_layout.html.twig]
```

#### Problème du champ catégory
Il fait référence à une relation avec une autre entity
On va ajouter des types la classe `TodoFormType`

#### Création de la méthode `update()`
On utilise cette fois ci une construction de méthode différente qui nécessite le bundle : 
```bash
composer req sensio/framework-extra-bundle
```
Son rôle est de faire la correspondance entre une url avec l'id d'un objet et l'objet passé en paramètre.