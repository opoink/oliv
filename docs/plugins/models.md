# Models and transactional hooks

Implementation: src/resources/plugins/Opoink/Liv/Models/Model.php and bundled model subclasses.

Extending **Plugins\Opoink\Liv\Models\Model** opts into OLIV transaction wrapping and [custom events](events.md). It is a bundled convention, not a compiler requirement. AdminUser extends Laravel Authenticatable directly and does not inherit these hooks.

~~~php
namespace Plugins\Vendor\Plugin\Models;

class Record extends \Plugins\Opoink\Liv\Models\Model
{
    protected $table = 'example_records';
}
~~~

Set the table explicitly: event names use the protected table property, not getTable().

## save(array $options = [])

The method returns parent save's boolean and performs:

~~~text
DB::beginTransaction
beforeSave()
db_<table>_save_before
parent::save()
db_<table>_save_after
afterSave()
DB::commit
db_<table>_save_commit_after
db_model_commit_after
~~~

beforeSave() and afterSave() are public empty extension hooks. The parent operation remains ordinary Eloquent persistence.

Caught Exception triggers rollback and a new Exception with code 500 and the original message. Throwable outside Exception is not covered. Exceptions after DB::commit cannot undo a completed outermost transaction, even though catch still attempts rollback. With an enclosing transaction, these "commit_after" hooks do not guarantee final outer commit.

## delete()

beforeDelete(), custom before event, parent deletion, custom after event, afterDelete(), commit, and commit events follow the analogous order. The post-commit payload is an attributes array, not the deleted model object.

The implementation does not return parent delete's result despite its PHPDoc. Query-builder mass updates/deletes do not invoke these overridden instance methods.

## Quiet operations

saveQuietly(array $options = []) and deleteQuietly() use Eloquent withoutEvents around OLIV's own methods. They suppress Laravel model events but **do not suppress OLIV's explicit custom dispatches**.

There is no generic OLIV repository layer, model discovery, or automatic attribute-table behavior. The [attributes migration](../reference/database.md) exists without a corresponding bundled attribute model/service.

---
[Events](events.md) · [Database reference](../reference/database.md) · [Documentation index](../README.md)
