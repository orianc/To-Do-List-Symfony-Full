- [Projet : toDoList](#projet--todolist)
    - [Etape 1 : DB](#etape-1--db)
    - [Etape 2 : ENTITY](#etape-2--entity)
      - [Make it :](#make-it-)
      - [Migration :](#migration-)
      - [Fixtures:](#fixtures)
      - [Alimenter les tables](#alimenter-les-tables)
    - [Analyse](#analyse)
    - [Etape 3 : Controllers](#etape-3--controllers)
      - [Test Controllers](#test-controllers)
      - [Install Twig](#install-twig)
      - [Controller Todo](#controller-todo)
      - [La page d'accueil](#la-page-daccueil)
      - [La page détail](#la-page-détail)
    - [Etape 4 : Générer un formulaire](#etape-4--générer-un-formulaire)
      - [Install](#install)
      - [Generate Form](#generate-form)
        - [Etape 1 :](#etape-1-)
        - [Etape 2 :](#etape-2-)
        - [Si problème de route :](#si-problème-de-route-)
        - [Etape 3 :](#etape-3-)
      - [Problème du champ catégory](#problème-du-champ-catégory)
      - [Création de la méthode `update()`](#création-de-la-méthode-update)
      - [Création de la méthode `delete()`](#création-de-la-méthode-delete)
      - [Concernant la protection CSRF](#concernant-la-protection-csrf)
      - [Concernant la protection CSRF](#concernant-la-protection-csrf-1)
      - [Ajout de contrainte de champs](#ajout-de-contrainte-de-champs)
          - [Dans l'entité `Todo`](#dans-lentité-todo)
          - [Dans le `TodoFormType`](#dans-le-todoformtype)
    - [Etape 5 : Création d'une navbar](#etape-5--création-dune-navbar)
      - [Pour la partie dropdown des catégories](#pour-la-partie-dropdown-des-catégories)
    - [Version de l'api avec Sql Lite](#version-de-lapi-avec-sql-lite)
    - [Version de l'api avec PostGreSQL](#version-de-lapi-avec-postgresql)
    - [Migration et fixtures en mode prod](#migration-et-fixtures-en-mode-prod)
    - [Heroku](#heroku)
      - [ReWritting des urls pour Heroku](#rewritting-des-urls-pour-heroku)
      - [Créé un fichier `Procfile`](#créé-un-fichier-procfile)
      - [Compte Heroku](#compte-heroku)
      - [Créer une API](#créer-une-api)


# Projet : toDoList
- Création du projet et installation du package Symfony mini via :
```bash
symfony new TodoList --version=5.2

 php -S localhost:5000 -t public
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

### Etape 3 : Controllers

#### Test Controllers
- L'objectif est de voir le format de rendu que propose le Controller, sachant que **Twig n'est pas installé.**

```bash
symfony console make:controller Test
```

#### Install Twig 
```bash
composer require twig
```
#### Controller Todo
```bash
symfony console make:controller Todo
# Maintenant il créer un View dans un nouveau dossier `Template`
```
#### La page d'accueil

Le controller va récup notre premier enregirstrement de la table To DO et le passer à la vue `todo/index`
La mise en forme est gérée par des tables Bootstrap

#### La page détail

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


### Etape 4 : Générer un formulaire

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
Son rôle est de faire la **correspondance entre une url avec l'id d'un objet et l'objet passé en paramètre.**

La méthode met à jour la date du champ "Mis à jour le :" grace à :
```php
$todo->setUpdateAt(new DateTime('now'));
```
Ajout d'un message d'alerte de confirmation d'update après validation en rechargeant la même page. 
Dans la vue :
```php
		{% for label, messages in app.flashes %}
			{% for message in messages %}
				<div class="alert alert-{{ label }} my-3">
					{{ message }}
				</div>
			{% endfor %}

		{% endfor %}
```
Dans la méthode du controller :
```php

    # Création d'un msg flash
    $this->addFlash('info', 'ToDo liste modifiée !');
    # Return sur la même page (GET)
    return $this->redirectToRoute('app_todo_update', [ 'id' => $todo->getId()  ]);

```
#### Création de la méthode `delete()`
```php

    /**
     * Function delete
     *
     * 
     * @Route("/todo/delete/{id}", name="app_todo_delete")
     * 
     * @param Todo $todo
     * @param EntityManagerInterface $em
     * @return void
     */
    public function delete(Todo $todo, EntityManagerInterface $em)
    {
        $em->remove($todo);
        $em->flush();
        return $this->redirectToRoute('app_todo');
    }
```

#### Concernant la protection CSRF

```bash
    composer require symfony/security-csrf
```
```php

    public function delete2(Todo $todo, EntityManagerInterface $em, Request $request)
    {
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $em->remove($todo);
            $em->flush();
        }

        return $this->redirectToRoute('app_todo');
    }
```
#### Concernant la protection CSRF
Création d'un message de confirmation de suppression en JS sur la View d'Update.

```js
// Le script doit être placé en bas du body, voir `base.html.twig`
<script>
const deleteForm = document.querySelector('.deleteForm');
deleteForm.addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Supprimer ?')) {
        this.submit()
    }
});
</script>
```


#### Ajout de contrainte de champs

###### Dans l'entité `Todo`

Ne pas oublier d'importer le composant `Validator` : 
```php
use Symfony\Component\Validator\Constraints as Assert;
```

On ajoute par exemple les méthodes suivante grace à l'objet `Assert` sur les champs voulu.
```php
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide !")
     * @Assert\Length(min=4, minMessage = "Au minimum {{ limit }} caractères")
```

###### Dans le `TodoFormType`
```php
    'empty_data' => '',
```

Et dans la méthode `configureOptions()`
```php
    'novalidate' => 'novalidate'
```

### Etape 5 : Création d'une navbar

- Un fichier `navbar.html.twig` avec une navbar BS
- Elle comprendra les boutons `titre` `accueil` `dropdown` pour les catégories.
- Inclure dans `base.html.twig` dans un block `{% block navbar %}{% endblock %}`

#### Pour la partie dropdown des catégories
On inclue au controller la méthode `__construct` et un attribut `private $all_categories;`
```php
private $all_categories;
    // Appel du repo de category
    function __construct(CategoryRepository $categoryRepository)
    {
        $this->all_categories = $categoryRepository->findAll();
    }
```



### Version de l'api avec Sql Lite

1. Installer SqlLiteStudio
2. Définir la collection dans le fichier `.env`
    
```bash
# ------ Connexion SQL Lite
# 
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```
3. Création de la DB : `db_todolist_lite`
```bash
symfony console doctrine:database:create
```

4. Puis fair la migration pour la bdd SQLite en supprimant l'ancienne version ou en choisisant correctement la version à migrer.
```bash
symfony console make:migration
symfony console doctrine:migrations:migrate

```

5. Fixtures load

### Version de l'api avec PostGreSQL
```yaml
url : https://www.enterprisedb.com/
```
DLL wamp --> php.ini --> Dynamic Extension --> lever le `;` à : `extension=pdo_sqlite` et
`extension=pgsql` .

Installer l'interface pgAdmin

Configurer Symfony
```yaml
# Dans config/packages/doctrine.yaml, ajouter !
dbal:
    driver: 'pdo_pgsql'
    charset: utf8
```
Connexion à PostgreSql dans le fichier `.env`

### Migration et fixtures en mode prod

Aller voir dans `config/bundles.php` , on passe fixture load en mode 'all' pour pouvoir avoir le chargement des fixtures par défault au déploiement.
```php
    Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle::class => ['all' => true],
```
Puis dans `composer.json` on place cette ligne de `require-dev` vers `require`.
```php
    "doctrine/doctrine-fixtures-bundle": "^3.4",
```
Puis on ajoute une structure dans scripts qui serviront lors du déploiement :
```js
        "compile" : [
            "php bin/console doctrine:migration:migrate",
            "php bin/console doctrine:fixtures:load --no-interacton --env=PROD"
        ],
```
__NB__ Comme nous avons modifié le composer.json
```bash
composer update
```

### Heroku
Permet de déployer des API de toute forme. Mise en ligne. 

#### ReWritting des urls pour Heroku

```bash
composer req symfony/apache-pack
```

#### Créé un fichier `Procfile`
Le fichier créé suivant va dire à Heroku quel web server  est utilisé.

```bash
echo 'web: heroku-php-apache2 public/' > Procfile
```

#### Compte Heroku

1. Créer un compte
2. Installer la cmd line de Heroku
3. Depuis un terminal, nous avons accès aux commandes Heroku

#### Créer une API

1. Heroku create
2. Config de l'env PROD
3. PostgreSql
On doit di à Heroku quel SGBD utiliser. On trouve ça dans les addons chez Heroku, mais nous alllons continuer en cmd line.
Après l'install, il a créé un varibale d'environnement DATABASE_URL connue dans Symfony.
4. Deploiment.

```bash
# 1
heroku create
# faire un login si besoin

# 2
heroku config:set APP_ENV=prod

# 3
heroku addons:create heroku-postgresql:hobby-dev

# 4
git push heroku
```