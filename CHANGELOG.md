# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/).

## [Unreleased]

Sections: (`Added`, `Changed`, `Deprecated`, `Removed`, `Fixed`, `Security`)

## [3.0.0] - TBD

### Please note
The database schema and models for 3.0 are incompatible with the schema from 2.9 and no migration is possible.

### Added
- **JobMeta**
    - `->id(): string`
    - `->created(): TimePoint`
    - `->parentID(): string`
    - `->name(): string`
    - `->value(): string`
- **SystemSetting**
    - `->id(): string`
    - `->name(): string`
    - `->value(): string`
- Added the following migrations:
    - Add **Initial** - `20170130010000`
    - Add **InitialSchemaFor3** - `20170130020000`
    - Add **InitialIndexesFor3** - `20170130030000`

### Removed
- **AuditLog** removed and replaced with **AuditEvent**.
- **Deployment** removed and replaced with **Target**.
- **DeploymentPool** removed and replaced with **TargetPool**.
- **DeploymentView** removed and replaced with **TargetView**.
- **EventLog** removed and replaced with **JobEvent**.
- **Group** removed and replaced with **Organization**.
- **Process** removed and replaced with **JobProcess**.
- **Push** removed and replaced with **Release**.
- **Server** removed and replaced with **Group**.
- **Token** removed and replaced with **UserToken**.
- **UserType** removed and combined into **UserPermission**.
- Removed **DoctrinePersistListener**
    - **IDs** and **Created Times** are now added in entity constructors instead of in a doctrine listener.
- Removed all previous migrations

### Changed
- Primary IDs
    - All entities now use 32-character random UUID for primary keys.
    - Builds and Releases use a 10-character ID based on the date and random salt.
- **DoctrineChangeListener** renamed to **DoctrineChangeListener**.
    - Currently AuditEvents are only created for changes to the following entities:
        - **Application**
        - **Environment**
        - **Organization**
- Default to PostgreSQL adapters for database connections.
- Crypto
    - Remove asymmetric crypto (public key).
    - Combine **SymmetricDecrypter** and **SymmetricEncrypter** into `Hal\Core\Crypto\Encryption`.
- Types
    - **AuditActionEnum** (Used by AuditEvent)
        - options: `create, update, delete`
    - **JobStatusEnum** (Used by Build, Release)
        - options: `pending, running, deploying, success, failure, removed`
    - **CredentialEnum** (Used by Credential)
        - options: `aws, privatekey`
    - **GroupEnum** (Used by Group)
        - options: `rsync, cd, eb, s3, script`
    - **JobEventStageEnum** (Used by JobEvent)
        - options: `unknown`
        - options: `build.created, build.start, build.running, build.end, build.success, build.failure`
        - options: `release.created, release.start, release.deploying, release.end, release.success, release.failure`
    - **JobEventStatusEnum** (Used by JobEvent)
        - options: `info, success, failure`
    - **JobProcessStatusEnum** (Used by JobProcess)
        - options: `pending, aborted, launched`
    - **UserPermissionEnum** (Used by UserPermission)
        - options: `member, owner, admin, super`
- **Application**
    - Added `->gitHub(): GitHubApplication`.
        - `->gitHub()->owner(): string`
        - `->gitHub()->repository(): string`
    - Changed `->key()` to `->identifier()`.
    - Changed `->group(): Group` to `->organization(): ?Organization`.
    - Removed `->email()`.
    - Removed `->githubOwner()`.
    - Removed `->githubRepo()`.
- **AuditEvent** replaces **AuditLog**.
    - Changed `->user(): User` to `->owner(): string`.
    - Changed `->action(): string` enum to **AuditActionEnum**.
- **Build**
    - Added `->inProgress(): bool`
    - Changed `->branch()` to `->reference(): string`.
    - Changed `->application()` and `->environment()` to nullable.
    - Changed `->status(): string` enum to **JobStatusEnum**.
    - Removed `->logs()`.
    - Removed `->isPending()`.
- **Credential**
    - Changed `->type(): string` enum to **CredentialEnum**.
- **Group** replaces **Server**.
    - Changed `->type(): string` enum to **GroupEnum**.
    - Removed `->deployments()`.
- **JobEvent** replaces **EventLog**.
    - Added `->parent(): string` to replace `->build()` and `->push()`.
    - Changed `->event()` to `->stage(): string` enum as **JobEventStageEnum**.
    - Changed `->status(): string` enum to **JobEventStatusEnum**.
    - Removed `->build()`.
    - Removed `->push()`.
- **JobProcess** replaces **Process**.
    - Changed `->type(): string` enum to **JobProcessStatusEnum**.
    - Changed `->context()` to `->parameters(): array`.
    - Changed `->parent()` to `->parentID(): string`.
    - Changed `->child()` to `->childID(): string`.
    - Removed `->parentType()`.
- **Organization** replaces **Group**.
    - Changed `->key()` to `->identifier()`.
- **Release** replaces **Push**.
    - Changed `->status(): string` enum to **JobStatusEnum**.
    - Changed `->deployment()` to `->target(): ?Target`.
    - Changed `->application()` and `->target()` to nullable.
    - Removed `->logs()`.
- **Target** replaces **Deployment**.
    - Changed `->push()` to `->release(): ?Release`.
    - Changed `->server()` to `->group(): ?Group`.
    - Added `->parameters(): array`.
        - Group-specific config is now encapsulated within parameters:
            - `group` (CodeDeploy)
            - `configuration` (CodeDeploy)
            - `application` (CodeDeploy, Elastic Beanstalk)
            - `environment` (Elastic Beanstalk)
            - `bucket` (S3, CodeDeploy, Elastic Beanstalk)
            - `path` (S3, CodeDeploy, Elastic Beanstalk)
            - `context` (Script)
    - Added `->parameter($name): ?string`
    - Removed `->path()`.
    - Removed `->cdName()`.
    - Removed `->cdGroup()`.
    - Removed `->cdConfiguration()`.
    - Removed `->ebName()`.
    - Removed `->ebEnvironment()`.
    - Removed `->s3bucket()`.
    - Removed `->s3file()`.
    - Removed `->s3file()`.
    - Removed `->scriptContext()`.
- **TargetPool** replaces **DeploymentPool**.
    - Changed `->deployments()` to `->targets()`.
- **TargetView** replaces **DeploymentView**.
- **User**
    - Changed `->handle()` to `->username(): string`.
    - Changed `->isActive()` to `->isDisabled(): bool`.
    - Changed `->settings()` to not nullable.
    - Removed `->githubToken()`.
- **UserPermission**
    - Added `->type(): string` enum to **UserPermissionEnum**.
    - Added `->organization(): ?Organization`.
    - Added `->environment(): ?Environment`.
    - Changed `->application()` to nullable.
    - Removed `->isProduction()`
- **UserToken** replaces **Token**.
    - Added `->organization(): ?Organization`.
    - Changed `->label()` to `->name()`.
    - Changed `->user()`  to nullable.

## [2.10.0] - 2017-01-19

### Entity Changes
- **Deployment**
    - Remove `$deployment->ec2Pool()`
    - Add `$deployment->scriptContext()`
- **Server**
    - Add `$server->isAWS()`

### Changed
- Remove EC2 AutoScale Deployment support.
- Remove `ServerEnum::TYPE_EC2`.
- Remove schema constraint that enforces `server + deployment path` uniqueness (For rsync-based deployments).
- Add `ServerEnum::TYPE_SCRIPT`.
- Limited audit changelogging to only certain types of entities.

### Added
- **Migrations**
    - Add **RemoveDeploymentUniquenessIndex** - `20170109143814`
    - Add **RemoveEc2AutoScaleDeployment** - `20170109172411`
    - Add **AddScriptDeploymentType** - `20170117140533`

## [2.9.0] - 2016-02-12

### Entity Changes
- **Build**
    - Add `$build->isPending()`
    - Add `$build->isFinished()`
    - Add `$build->isSuccess()`
    - Add `$build->isFailure()`
- **Push**
    - Add `$build->isPending()`
    - Add `$build->isFinished()`
    - Add `$build->isSuccess()`
    - Add `$build->isFailure()`
- **Process**
    - Add `$process->id()`
    - Add `$process->created()`
    - Add `$process->user()`
    - Add `$process->status()`
    - Add `$process->message()`
    - Add `$process->context()`
    - Add `$process->parent()`
    - Add `$process->parentType()`
    - Add `$process->child()`
    - Add `$process->childType()`
- **Deployment**
    - Add `$deployment->push()` (may be `null`)
- **User**
    - Add `$user->settings()` (may be `null`)
    - All Users should have UserSettings. This will automatically backfill when users log in or set their favorites.
- Add **UserSettings** entity to **User**.
    - Add `$settings->id()`
    - Add `$settings->favoriteApplications()`
    - Add `$settings->user()`

### Changed
- **ProcessStatusEnum** datatype
    - Note: this enum is a **string** database type, and other enums should be moved to this style eventually.
- **Repositories**
    - Add `BuildRepository->getByApplicationForEnvironment()`
    - Add `PushRepository->getByApplicationForEnvironment()`
    - Remove `PushRepository->getMostRecentByDeployments()`

### Added
- **Migrations**
    - Add **AddChildProcess** - `20151221182132`
    - Add **AddLatestPushToDeployment** - `20151222195126`
        - When pushes are created, they should be set as the active push on the deployment.
        - This will not be enforced, as we will let this backfill over time.

Move kraken components to [hal/kraken-core](http://git/hal/kraken-core). See hal/hal#210

## [2.8.3] - 2015-09-22

### Changed
- Add **Cache ID** to cacheable queries to allow them to be flushed.

## [2.8.2] - 2015-09-22

### Entity Changes
- **Application**
    - Remove `$application->ebName()`
- **Deployment**
    - Add `$deployment->ebName()`
    - Add `$deployment->cdName()`
    - Add `$deployment->cdGroup()`
    - Add `$deployment->cdConfiguration()`
- **Server**
    - Add `$deployment->formatHumanType()`

### Changed
- Update **ServerEnum**
    - Change `elasticbeanstalk` to `eb`
    - Add `cd` for CodeDeploy

### Added
- **Migrations**
    - Add **AddCodeDeployDeploymentType** - `20150915192949`

## [2.8.1] - 2015-08-13

### Entity Changes
- **User**
    - Remove `$user->pictureUrl()`
    - Remove `$user->withPictureUrl()`
- **Deployment**
    - Add `$deployment->formatPretty($withDetails)`
    - Add `$deployment->formatMeta()`
- **Server**
    - Add `$deployment->formatPretty()`

### Added
- Add Kraken **ConfigurationRepository** for paged configuration history
- Add Kraken **SortingTrait**

### Removed
- Remove `HttpUrlType`

## [2.8.0] - 2015-07-23

### Changed
- Update **Deployment**
    - Add `$deployment->name()`
- Doctrine Repositories
    - Added **ServerRepository**
        - `getPaginatedServers($limit, $page)`
    - Updated **UserRepository**
        - `getPaginatedUsers($limit, $page)`
- Update dependencies
    - Add `ext-pdo`
    - Add `ext-pdo_sqlite`
    - Add `ext-pdo_mysql`
    - Remove `ql/mcp-corp-account`

### Added
- Add **Credential**
    - `$credential->id()`
    - `$credential->type()`
    - `$credential->name()`
    - `$credential->aws()->key()`
    - `$credential->aws()->secret()` (Encrypted)
    - `$credential->privatekey()->username()`
    - `$credential->privatekey()->path()`
    - `$credential->privatekey()->file()` (Encrypted)
- Add **DeploymentView**
    - `$view->id()`
    - `$view->name()`
    - `$view->application()`
    - `$view->environment()`
    - `$view->user()` | `null`
    - `$view->pools()`
- Add **DeploymentPool**
    - `$pool->id()`
    - `$pool->name()`
    - `$pool->view()`
    - `$pool->deployments()`
- Add Deployment type `s3`
    - `$deployment->s3bucket()`
    - `$deployment->s3file()`
    - `$deployment->credential()` | `null`
- Add migrations
    - Add `Create Deployment Views`
    - Add `Add S3 Deployment`
    - Add `Add Credential`

## [2.7.1] - 2015-07-09

### Changed
- Increase server `name` size from 24 to 60 chars.

### Added
- Add qks properties to KrakenEnvironment.
    - Change `consulServer` to `consulServiceURL`.
    - Add `qksServiceURL`.
    - Add `qksClientID`.
    - Add `qksClientSecret`.
- Added migrations
    - `AddQksPropertiesToKrakenEnvironment`
    - `IncreaseHostNameSize`

## [2.7.0] - 2015-06-15

### Changed
- Update entity getter/setters to use `$model->property()` and `$model->withProperty($value)`.
    - Entities now have fluent interfaces for setters.
- Update name of **Repository** to **Application**.
- Change token id to GUID.
- Disable entity change logging of **Build** and **Push**.
    - These entities already have auditing info on their model, and so additional audit logs is just noise.

### Added
- Add **Kraken** entities in `QL\Kraken\Core` namespace.
- Add cache settings for Doctrine 2.5.
- Add **UserType** and **UserPermission** models for managing permissions.
- Add proxy generator that doesn't actually connect to DB (uses mock sqlite).

## [2.6.1] - 2015-03-13

### Changed
- Fix incorrect repository class names in `hal-core.yml`

### Added
- Entities now implement `JsonSerializable`

## [2.6.0] - 2015-03-06

### Changed
- Repositories moved out of Entity namespace
    > Previously: `QL\Hal\Core\Entity\Repository\...`
    > Now: `QL\Hal\Core\Repository\...`
- Types moved out of Entity namespace
    > Previously: `QL\Hal\Core\Entity\Type\...`
    > Now: `QL\Hal\Core\Type\...`

### Removed
- Doctrine annotations removed
    - YAML Doctrine mapping is included in `config/doctrine` dir

## [2.5.0] - 2015-02-19

### Changed
- Rename `di.yml` to `hal-core.yml`.

### Added
- Add phinx migrations for all database schema changes.
- Add crypto utilities for symmetric/asymmetric openssl crypto.
- Added `ext-openssl` to dependencies.
- Add several convenience queries to entity repositories.

### Entity Changes
- **EncryptedProperty**
    - New entity
- **Push**
    - Add `repository`
- **Deployment**
    - Add `ebEnvironment` to support EB deployments
    - Add `ec2Pool` to support EC2 deployments
- **Server**
    - Add `type` to support multiple deployment types
- **Repository**
    - Add `ebName` to support EB deployments
    - Commands can no longer be null (The default value is empty string)

### Migration

The migration required is slightly more complicated than usual, as this is initiation to using phinx for all migrations.

1. BACK UP ALL TABLES
2. Add fake phinx migrations in the `PhinxLog` table for:
    - **InitialSchemaVersion23** (`20150104133423`)
    - **InitialAddIndexesAndForeignKeys** (`20150104163932`)
3. Rollback to **InitialSchemaVersion23** (`20150104133423`)
4. Run the commented out alter table queries in **InitialAddIndexesAndForeignKeys**
5. Migrate forward all remaining migrations.

## [2.4.1] - 2014-12-05

### Changed
- RandomGenerator uses `GUID::create`  to create a random 40 character hash, rather than a random sha based on current time.

## [2.4.0] - 2014-12-02

### Added
- Add api token entity and repository.
- Add event log entity and repository.
- Add shared ID generator utils.

### Schema Changes
- Add new table `Tokens`.
- Requires new column `isActive` on `Consumers` table.
- Change table `Logs` to `AuditLogs`.
- Add new table `EventLogs`.
- Remove table `Sessions`.
- PushId must be a unique string, it will no longer autogenerate.
- URL added to `Deployments`

### Technical

Database migration script.
```
-- Add tokens
CREATE TABLE Tokens (
    TokenId                 INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    TokenValue              CHAR(64)        NOT NULL,
    TokenLabel              CHAR(128)       NOT NULL,
    UserId                  INT UNSIGNED,
    ConsumerId              INT UNSIGNED,
    PRIMARY KEY             (TokenId),
    UNIQUE                  (TokenValue),
    CONSTRAINT              TokenToUser
      FOREIGN KEY (UserId) REFERENCES Users (UserId)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    CONSTRAINT              TokenToConsumer
      FOREIGN KEY (ConsumerId) REFERENCES Consumers (ConsumerId)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    INDEX                   (TokenValue)                        -- WHERE TokenValue = ?
)
  ENGINE                    InnoDB
  CHARACTER SET             utf8;

-- Add active flag to consumers
ALTER TABLE Consumers ADD COLUMN ConsumerIsActive TINYINT(1) NOT NULL DEFAULT 1;

-- Updates to Log tables
RENAME TABLE Logs TO AuditLogs;
ALTER TABLE AuditLogs CHANGE LogId AuditLogId BIGINT

CREATE TABLE EventLogs (
    EventLogId              CHAR(40)        NOT NULL,
    Event                   ENUM('build.created', 'build.start', 'build.building', 'build.end', 'build.success', 'build.failure', 'push.created', 'push.pushing', 'push.start', 'push.end', 'push.success', 'push.failure', 'unknown') NOT NULL DEFAULT 'unknown',
    EventOrder              INT(11)         DEFAULT NULL,
    EventLogCreated         DATETIME        NOT NULL,
    EventLogMessage         VARCHAR(255)    DEFAULT NULL,
    EventLogStatus          ENUM('info','success','failure') NOT NULL DEFAULT 'info',
    BuildId                 CHAR(40)        DEFAULT NULL,
    PushId                  CHAR(40)        DEFAULT NULL,
    EventLogData            MEDIUMBLOB      DEFAULT NULL,
    CONSTRAINT              EventToBuild
      FOREIGN KEY (BuildId) REFERENCES Builds (BuildId)
      ON UPDATE CASCADE,
    CONSTRAINT              EventToPush
      FOREIGN KEY (PushId) REFERENCES Pushes (PushId)
      ON UPDATE CASCADE,
    PRIMARY KEY             (EventLogId),
    INDEX                   (BuildId),                        -- WHERE BuildId = ?
    INDEX                   (PushId)                          -- WHERE PushId = ?
)
    ENGINE                  InnoDB
    CHARACTER SET           utf8;

-- Remove Sessions
DROP TABLE Sessions;

-- Update BuildId and PushId
ALTER TABLE Pushes DROP FOREIGN KEY PushToBuild;
ALTER TABLE Pushes DROP INDEX BuildId;

ALTER TABLE Pushes MODIFY BuildId CHAR(40);
ALTER TABLE Builds MODIFY BuildId CHAR(40) NOT NULL;

ALTER TABLE Pushes ADD INDEX(BuildId);
ALTER TABLE Pushes ADD CONSTRAINT PushToBuild
    FOREIGN KEY (BuildId) REFERENCES Builds (BuildId)
    ON UPDATE CASCADE;

ALTER TABLE Pushes MODIFY PushId CHAR(40) NOT NULL;

-- Make Push deployment nullable
ALTER TABLE Pushes MODIFY DeploymentId INT(10) UNSIGNED NULL;
ALTER TABLE Pushes DROP FOREIGN KEY PushToDeployment;
ALTER TABLE Pushes DROP INDEX DeploymentId;
ALTER TABLE Pushes ADD CONSTRAINT PushToDeployment
    FOREIGN KEY (DeploymentId) REFERENCES Deployments(DeploymentId)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

-- Add URL to deployments
ALTER TABLE Deployments ADD COLUMN DeploymentUrl CHAR(255);
```

Flush Doctrine APC cache.
```
http://$haldomain/superadmin?clear_doctrine=1
```

## [2.3.0] - 2014-10-01

### Added
- Add build transform command to repositories.

### Schema Changes
- Requires new column `RepositoryBuildTransformCmd` varchar on the Repositories table.

### Technical

Database migration script.
```
ALTER TABLE Repositories ADD COLUMN RepositoryBuildTransformCmd varchar(255) AFTER RepositoryBuildCmd;
```

Flush doctrine APC Cache:
```
http://$haldomain/superadmin?clear_doctrine_cache=1
```

## [2.2.0] - 2014-08-15

### Added
- Add created date to builds.
- Add created date to pushes.
- Add active flag to users.

### Schema Changes
- Requires new column `BuildCreated` Datetime on the Builds table.
- Requires new column `PushCreated` Datetime on the Pushes table.
- Requires new column `UserIsActive` int on the Users table.

### Technical

Database migration script.
```
ALTER TABLE Builds ADD COLUMN BuildCreated Datetime AFTER BuildId;
ALTER TABLE Pushes ADD COLUMN PushCreated Datetime AFTER PushId;
UPDATE Builds SET BuildCreated = BuildStart;
UPDATE Pushes SET PushCreated = PushStart;

ALTER TABLE Users ADD COLUMN UserIsActive TINYINT(1) NOT NULL;
UPDATE Users SET UserIsActive = 1;
```

## [2.1.0] - 2014-06-17

### Added
- Add support for Log entities.
- Add empty event manager to DI for use in subscribing to Doctrine events.

### Schema Changes
- Requires addition of the Logs table as defined in `config/initial.mysql`.

## [2.0.0] - 2014-05-29

Initial release
