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
- [Environment](src/Entity/Environment.php)
    - [Server](src/Entity/Server.php)
- [Build](src/Entity/Build.php)
    - [EventLog](src/Entity/EventLog.php)
- [Push](src/Entity/Push.php)
    - [EventLog](src/Entity/EventLog.php)
- [User](src/Entity/User.php)
    - [AuditLog](src/Entity/AuditLog.php)
    - [Token](src/Entity/Token.php)
    - [UserPermission](src/Entity/UserPermission.php)
    - [UserType](src/Entity/UserType.php)

#### Kraken

- [Application](src-kraken/Entity/Application.php)
    - [Target](src-kraken/Entity/Target.php)
    - [Schema](src-kraken/Entity/Schema.php)
        - [Property](src-kraken/Entity/Property.php)
    - [Configuration](src-kraken/Entity/Configuration.php)
    - [Snapshot](src-kraken/Entity/Snapshot.php)
- [Environment](src-kraken/Entity/Environment.php)
- [AuditLog](src-kraken/Entity/AuditLog.php)
