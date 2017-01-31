# Hal Core Components

Core domain entities and shared resources for Hal UI and Agent.

- Hal Doctrine Entities
- Phinx Database Migrations
- Crypto for Encrypted Properties

## Database

### Doctrine Protips
- Do not cache one-to-many relations!

### Set up DB for development

- `createdb hal`
- `createuser hal --superuser`
- `bin/phinx migrate`

## Hal Domain Model

### Applications

- [Application](src/Entity/Application.php)
    > An application in the system. Contains information about source code location.

- [Organization](src/Entity/Organization.php)
    > Applications are owned by Organizations.

- [Encrypted Property](src/Entity/EncryptedProperty.php)
    > Value stored in Hal's encrypted configuration dictionary.

### System

- [AuditEvent](src/Entity/AuditEvent.php)
    > Denormalized event for record-keeping.

- [System Setting](src/Entity/SystemSetting.php)
    > Configuration value used by the Hal platform. For Hal internal system use only.

### Jobs

- [Build](src/Entity/Build.php)
    > Build job. Builds are run with a `commit` for a specific `environment`.

- [Release](src/Entity/Release.php)
    > Release job. Releases are run with a `build` for a specific `target`.

- [Job Event](src/Entity/JobEvent.php)
    > Any event that occurs during a build or release. Contains parameters or data
      such as shell output or configuration parameters.

- [Job Meta](src/Entity/JobMeta.php)
    > Metadata published by a build or release. Information that may be
      searched or analyzed for further use.

- [Job Process](src/Entity/JobProcess.php)
    > An action or process that can be triggered by a job. Currently only used
      to auto-kickoff a release after a successful build.

### Deployment Destinations

- [Environment](src/Entity/Environment.php)
    > An environment in the system.

- [Group](src/Entity/Group.php)
    > Groups are collection of targets, used to control permissions or simply naturally organize them.
    > Groups typically have a `type` and `name` such as "Group `rsync` for server `mydevserver`"

- [Target](src/Entity/Target.php)
    > Configuration for a group in a environment. Applications can have many targets for a single group or environment.
    > Example: "Target for CodeDeploy `group` with CodeDeploy application name `test-app` and S3 bucket `dev-releases`".

- [Target Pool](src/Entity/TargetPool.php)
    > Used in the UI the help users organize targets.

- [Target View](src/Entity/TargetView.php)
    > Used in the UI the help users organize targets.

- [Credential](src/Entity/Credential.php)
    > Security credentials used to connect to a target such as access tokens or private keys.

### User Management

- [User](src/Entity/User.php)
    > A user in the system.

- [User Permission](src/Entity/UserPermission.php)
    > User permission. Permissions can be assigned per environment, application, organization, or globally.

- [User Settings](src/Entity/UserSettings.php)
    > User specific settings such as their favorite applications.

- [User Token](src/Entity/UserToken.php)
    > Tokens can be created by users to access the API.
