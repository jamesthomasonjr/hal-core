# HAL 9000 Core

This library contains Common HAL 9000 dependencies between the frontend and agent.

- HAL 9000 Entities
- Kraken Entities
- Crypto for Encrypted Properties
- Database Migrations

## Doctrine

- Do not cache one-to-many relations!

## Domain Model

#### HAL 9000

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

#### Kraken

- [Application](src-kraken/Entity/Application.php)
    - [Target](src-kraken/Entity/Target.php)
    - [Schema](src-kraken/Entity/Schema.php)
        - [Property](src-kraken/Entity/Property.php)
    - [Configuration](src-kraken/Entity/Configuration.php)
    - [Snapshot](src-kraken/Entity/Snapshot.php)
- [Environment](src-kraken/Entity/Environment.php)
- [AuditLog](src-kraken/Entity/AuditLog.php)
