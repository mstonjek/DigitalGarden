# 🌱 Digital Garden - We Need Your Flower 🌷
### 🪷  [Plant a flower today](https://digital-garden.mstonjek.cz/www/) — each one makes a difference! 🌼🌿

<p align="center">
    <img width="45%" alt="Screenshot 2024-08-23 at 16 42 25" src="https://github.com/user-attachments/assets/99fc158b-e978-436f-9879-ec148e180205">
    <img width="45%" alt="Screenshot 2024-08-23 at 16 42 35" src="https://github.com/user-attachments/assets/07979b59-247b-433b-b8e3-1a1ad12f9312">
</p>

Welcome to the **Digital Garden** project! 🌹 This is a virtual garden where anyone can plant a flower. All you need is a GitHub account and a bit of imagination. But there's a catch—each user can plant only one flower! 🌸

This project has the potential to blossom into a vast herbal collection 🌻, but it needs some dedicated gardeners to help it grow. If you're reading this, why not plant a flower? 🌺 If you want to, of course.

⚠️ **Please note**: This is a very early demo, so the entire site isn't fully designed yet. You might encounter some bugs 🐛, and there's a slight chance I could accidentally delete the database—hopefully not, but accidents happen! 😅

- **Web**: [Digital Garden](https://digital-garden.mstonjek.cz/www/) 🌐
- **GitHub Repository**: [Digital Garden Repo](https://github.com/mstonjek/DigitalGarden) 🐙

## 🚀 Migrations

- Create from Entity: `php bin/console migrations:diff` 
- Migrate: `php bin/console migrations:migrate` 
- Previous migration: `php bin/console migrations:migrate prev` 
- Load fixtures: `php bin/console doctrine:fixtures:load`

## 🛠️ First Installation

1. Run: `docker-compose up --build`
2. Create a `local.neon` file with these `.env` variables:

    ```
    parameters:
        github_client_id:
        github_client_secret:
        github_redirect_uri:

        doctrine:
            host:
            user:
            password:
            dbname:
    ```

## 🌍 Launch

- Start the project with: `docker-compose up -d` (the `-d` flag keeps it running in the background without spamming logs)

## 🔒 Permissions

- Set permissions for `temp` and `log` directories:
  ```bash
  chmod -R 777 ./temp
  chmod -R 777 ./log
  ```

## 🐳 Docker Commands

- Enter Docker for Composer:
  ```bash
  docker-compose exec www /bin/bash
  ```

## 🎨 PHP Tools

- **PHP CS Fixer**:
  ```bash
  docker-compose exec www /bin/bash
  php vendor/bin/php-cs-fixer fix app
  ```

- **Psalm**:
  ```bash
  php vendor/bin/psalm
  ```

## 🧹 Clear Cache

- To clear the cache, run:
  ```bash
  php bin/clearcache
  ```

---
