# Custom events

Implementation: src/Lib/Plugin/UpdatePlugin.php and src/resources/plugins/Opoink/Liv/Lib/Event.php.

OLIV's event dispatcher is separate from Laravel's event dispatcher. [Plugin update](plugin-update.md) includes EventListeners/EventList.php, groups entries by name, sorts each list by numeric sort_order, and stores it forever under **plugin_event_listeners** in Laravel cache.

~~~php
// plugins/Vendor/Plugin/EventListeners/EventList.php
return [
    [
        'name' => 'example_changed',
        'listener' => \Plugins\Vendor\Plugin\EventListeners\ExampleChanged::class,
        'sort_order' => 10,
    ],
];
~~~

Required fields are name, listener, and sort_order; no schema validation or duplicate-listener suppression occurs.

~~~php
namespace Plugins\Vendor\Plugin\EventListeners;

class ExampleChanged
{
    public function handle(\Opoink\Oliv\Lib\DataObject $data): void
    {
        $model = $data->getData('model');
        // Act on the supplied model.
    }
}
~~~

## Public API

~~~php
app(\Plugins\Opoink\Liv\Lib\Event::class)
    ->dispatch('example_changed', ['model' => $model]);
~~~

dispatch(string $eventName, array $data = []) has no declared return type and returns nothing. It resolves each listener through app(), constructs a **new DataObject per listener**, and invokes handle($dataObject). Changing scalar DataObject entries does not feed later listeners or return to the caller. Referenced objects such as request/model can be mutated.

getEvents() returns the cached mapping or a missing-cache value; the instance memoizes a truthy result. No runtime filesystem discovery rebuilds a cleared cache. Recompile after clearing it; see [cache lifecycle](../reference/caching.md).

## Built-in hooks

| Event | Payload |
| --- | --- |
| db_<table>_save_before / after / commit_after | model object |
| db_<table>_delete_before / after | model object |
| db_<table>_delete_commit_after | model attributes array captured before deletion |
| db_model_commit_after | model object for save; attributes array for delete |
| Plugins_Opoink_Liv_Lib_Facades_Event_Login_authUser | request object |
| Plugins_Opoink_Liv_Http_Middleware_AdminAuthenticated_handle_before | Empty array |
| Opoink_Oliv_Middleware_HandleInertiaRequests | adminUser object |

[Model hooks](models.md) define transaction timing. [Authentication listeners](../admin/authentication.md) can establish the guard user or mutate the shared user. Listener exceptions normally propagate; HandleInertiaRequests suppresses Throwable around its event.

---
[Plugin registration](plugin-structure.md) · [Documentation index](../README.md)
