Atrinium
==============

First, clone this repository:

```bash
https://github.com/CarlosGude/Atrinium.git
```

Run `cd symfony-project && composer install` for install symfony and dependencies.  
Make sure you adjust `database_host` in `parameters.yml` to the database container alias "db" (for Symfony < 4).  
Make sure you adjust `DATABASE_URL` in `env` to the database container alias "db" (for Symfony >= 4).  

Then, run:

```bash
$ docker-compose up -d
$ docker-compose exec php php bin/console d:d:c //Create database
$ docker-compose exec php php bin/console d:m:m -n //Execute migrations
$ docker-compose exec php php bin/console d:f:l -n //Load fixtures
```

Optional:  
Load the BCE rate data executing:  
```bash
$ docker-compose exec php php bin/console app:ba //Load BCE data
```

You have 2 users:

```bash
| email              |password  |role   |
|--------------------|----------|-------|
| admin@atrinium.wip | password |Admin  |
| user@atrinium.wip  | password |Client |
```