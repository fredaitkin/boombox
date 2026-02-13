# Local Development: DDEV and Lando

This project is a Drupal 10 codebase created from `drupal/recommended-project`.

## Option A: DDEV

### Prerequisites
- Docker Desktop running
- DDEV installed
- drush installed - ddev composer require drush/drush
- On Windows, WSL2-enabled Docker is recommended

### Setup
1. From the project root:
   ```powershell
   ddev config --project-type=drupal10 --docroot=web --create-docroot=false
   ```
2. Start the environment:
   ```powershell
   ddev start
   ```
3. Install dependencies inside DDEV (avoids local PHP extension issues):
   ```powershell
   ddev composer install
   ```
4. (Optional) Install Drupal site:
   ```powershell
   ddev drush site:install standard --account-name=admin --account-pass=admin --site-name=Boombox -y
   ```
5. Open the site:
   ```powershell
   ddev launch
   ```

### Useful DDEV commands
```powershell
ddev describe
ddev ssh
ddev drush status
ddev stop
```

## Option B: Lando

### Prerequisites
- Docker Desktop running
- Lando installed
- On Windows, WSL2-enabled Docker is recommended

### Setup
1. Initialize Lando config in the project root:
   ```powershell
   lando init --source cwd --recipe drupal10 --webroot web --name boombox
   ```
   If prompted, accept defaults for services unless you need custom versions.
2. Start services:
   ```powershell
   lando start
   ```
3. Install dependencies in the app container:
   ```powershell
   lando composer install
   ```
4. (Optional) Install Drupal site:
   ```powershell
   lando drush site:install standard --account-name=admin --account-pass=admin --site-name=Boombox -y
   ```
5. Get app URLs:
   ```powershell
   lando info
   ```

### Useful Lando commands
```powershell
lando ssh
lando drush status
lando restart
lando stop
```

## Notes
- Use one tool at a time for a project (DDEV or Lando), not both simultaneously.
- If you previously ran Composer locally with `--ignore-platform-req=ext-gd`, re-run Composer inside DDEV/Lando for a clean container-managed install.