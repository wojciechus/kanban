# Kanban board for Github issues

## About

This is a simple, read-only, Kanban-board for Github issues.

### Concepts and workflow

* `Queued:` are open issues, in a milestone with no one assigned
* `Active:` are any open issue, in a milestone with someone assigned
   * Active issues can, optionally, be paused by adding any of the configured "pause labels" to the issue
* `Completed:` are any issues in a milestone that is closed

#### Required environment variables

* `GH_CLIENT_ID`
* `GH_CLIENT_SECRET`
* `GH_ACCOUNT`
* `GH_REPOSITORIES`

## Installation
#### Under docker : 
1. Clone from github

2. Create .env file in main app directory

    `GH_CLIENT_ID=>clientIdString`

    `GH_CLIENT_SECRET=clientSecretString`

    `GH_ACCOUNT=account-only-name`

    `GH_REPOSITORIES=myRepo1|myRepo2`   
    

3. In `app/Environment` rename file `ConfigDefault.dist` -> `ConfigDefault.php`

4. Open `ConfigDefault.php` in editor, set proper values in array const `DEFAULTS`

5. Run `docker compose build`

6. Run `docker compose up`

7. Install inside php container dependencies from composer.json `usr/local/bin/composer install`
  
#### In old style - when defining system envs could be impossible:
1. Clone from github

2. Try to set system envs from console

 `export GH_CLIENT_ID=clientIdString`
 
 `export GH_CLIENT_SECRET=clientSecretString`
 
 `export GH_ACCOUNT=account-only-name`
 
 `export GH_REPOSITORIES='myRepo1|myRepo2'
`

3. In `app/Environment` rename file `ConfigDefault.dist` -> `ConfigDefault.php`

4. Open `ConfigDefault.php` in editor, set proper values in array const `DEFAULTS`

5. Install dependencies from composer.json `usr/local/bin/composer install`

----

_Originally a "fork" of the [Kanban Board](https://github.com/ellislab/kanban-board) plugin to [ExpressionEngine](https://ellislab.com/expressionengine) then more or less completely rewritten._
