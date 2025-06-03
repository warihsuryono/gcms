# GWS Workspace

## Instalaltion

1. Git Clone

```bash
git clone git@github.com:warihsuryono/gcms.git
```

### Installation Laravel

2. Install Composer Package on project directory

```bash
composer install
```

3. Setup `.env`

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env` file
5. Migrate & Seed Database

```bash
php artisan migrate --seed
```

6. Running development URL

```bash
php artisan serve
```

7. Running job queue service

```bash
php artisan queue:listen --memory=1024 --timeout=3600 --tries=3
```

## Deployment

## Configure

1. Add git remote URL
    ```bash
    git remote add live ssh://root@212.1.213.168/home/workspace/repository/workspace.gws.co.id
    ```
2. Create `production` branch if not exists
    ```bash
    git branch production
    ```

## Deployment Step

1. After push to `main` branch github you must checkout to `production` branch and merge with `main` branch
    ```bash
    git checkout production
    git merge main
    ```
2. Push to remote `live` (production server)
   `bash
git push live production
`
   [Referensi Git Auto Deploy](https://medium.com/biji-inovasi/git-simple-auto-deploy-langung-live-di-server-vps-3bf450c4b86c)
