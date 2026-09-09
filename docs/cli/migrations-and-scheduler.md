# Migrations, rollback, and scheduler commands

Implementation: src/Console/Commands/MigrateCommand.php, RollbackCommand.php, src/Console/Scheduling/ScheduleRunCommand.php, and the package provider.

| Inherited command | OLIV behavior |
| --- | --- |
| migrate | Adds enabled plugin migration directories |
| migrate:rollback | Adds the same directories to migration-file lookup |
| schedule:run | Registers a subclass without an active override |

The old oliv:plugins-migrate and oliv:plugins-migrate-rollback signatures are comments, not registered commands.

## Migration paths

Without --path, OLIV combines registered migrator paths, enabled plugins' existing migrations directories, and Laravel's normal migration directory. Plugin paths are **migrations**, not database/migrations.

With --path, only supplied paths are returned, relative to the app unless --realpath is set. Other options come from the installed framework.

~~~bash
php artisan migrate --help
php artisan migrate:rollback --help
php artisan migrate --path=plugins/Opoink/Email/migrations --no-interaction
~~~

The last example mutates the database. Rollback can drop tables/data. Removing a plugin from the registry can make its rollback files unavailable to default lookup.

The [bundled schema](../reference/database.md) includes a users-table alteration whose down() is empty. Its 2014 filename can run before the host create-users migration; resolve this ordering before initialization.

## Scheduler

~~~bash
php artisan schedule:run --no-interaction
~~~

The subclass's proposed handle() is entirely commented out. Plugin schedules load via installed routes/console.php, generated routes/plugin_console.php, and each plugin's console routes. See [routes and scheduling](../plugins/routes-and-scheduling.md).

The console template also registers inspire. No OLIV email worker command is supplied.

---
[Installation](../getting-started/installation.md) · [Documentation index](../README.md)
