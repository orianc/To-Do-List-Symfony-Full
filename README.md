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

### Etape 1

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
Le fichier .env a été modifier
Je modifie la ligne MySQL pour la renomer comme suit :
```bash
# DATABASE_URL="mysql://root:@127.0.0.1:3306/db_todolist"
```
- Création de la DB : `db_todolist`
```bash
symfony console doctrine:database:create
```
