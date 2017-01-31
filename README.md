# Hal Core Components

Core domain entities and shared resources for Hal UI and Agent.

- Hal Doctrine Entities
- Phinx Database Migrations
- Crypto for Encrypted Properties

## Doctrine

- Do not cache one-to-many relations!

### Set up DB for development

- `createdb hal`
- `createuser hal --superuser`
- `bin/phinx migrate`

## Hal Domain Model

- [Group](src/Entity/Group.php)
    - [Application](src/Entity/Application.php)
        - [EncryptedProperty](src/Entity/EncryptedProperty.php)
        - [Deployment](src/Entity/Deployment.php)
            - [Credential](src/Entity/Credential.php)
            - [DeploymentView](src/Entity/DeploymentView.php)
                - [DeploymentPool](src/Entity/DeploymentPool.php)
- [Environment](src/Entity/Environment.php)
    - [Server](src/Entity/Server.php)
- [Credential](src/Entity/Credential.php)
    - [Credential.AWS](src/Entity/Credential/AWSCredential.php)
    - [Credential.PrivateKey](src/Entity/Credential/PrivateKeyCredential.php)
- [Build](src/Entity/Build.php)
    - [EventLog](src/Entity/EventLog.php)
    - [Process](src/Entity/Process.php)
- [Push](src/Entity/Push.php)
    - [EventLog](src/Entity/EventLog.php)
    - [Process](src/Entity/Process.php)
- [User](src/Entity/User.php)
    - [AuditLog](src/Entity/AuditLog.php)
    - [Token](src/Entity/Token.php)
    - [UserPermission](src/Entity/UserPermission.php)
    - [UserSettings](src/Entity/UserSettings.php)
    - [UserType](src/Entity/UserType.php)
- [AuditLog](src/Entity/AuditLog.php)
