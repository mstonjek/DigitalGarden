# Digital Garden

<img src="https://github.com/user-attachments/assets/b5cf37fa-e2e0-4900-8d13-47d7dea916d0" alt="image description" width="65%"/>


Welcome to the **Digital Garden** project! This is a virtual garden where anyone can plant a flower. All you need is a GitHub account and a bit of imagination. But there's a catch—each user can plant only one flower! 

This project has the potential to grow into a vast herbal collection 🌻, but it needs some dedicated gardeners to make that happen. If you're reading this, why not plant a flower? If you want to, of course.

**Please note**: This is a very early demo, so the entire site isn't fully designed yet. You might encounter some bugs, and there's a slight chance I could accidentally delete the database—hopefully not, but accidents happen!

- **Web**: [Digital Garden](https://digital-garden.mstonjek.cz/www/)
- **GitHub Repository**: [Digital Garden Repo](https://github.com/mstonjek/DigitalGarden)

Migrations
------------
- Create from Entity: `php bin/console migrations:diff`
- Migrate: `php bin/console migrations:migrate`
- Previous migration: `php bin/console migrations:migrate prev`
- Load fixtures: ` php bin/console doctrine:fixtures:load`

First Instalation
------------
`docker-compose up --build`

Create `local.neon` for these .env vars: 

`parameters:
    github_client_id:
    github_client_secret:
    github_redirect_uri:

    doctrine:
        host:
        user:
        password:
        dbname: `

Launch
------------
`docker-compose up -d` Parametr -d -> it won't spam log

Permissions
------------
- `chmod -R 777 ./temp`
- `chmod -R 777 ./log`

Entrance to docker for composer
-
`docker-compose exec www /bin/bash`

Php cs fixer
-
1) `docker-compose exec www /bin/bash`
2) `php vendor/bin/php-cs-fixer fix app`

Psalm
-
- `php vendor/bin/psalm`

Clear cache
-

- `php bin/clearcache`
