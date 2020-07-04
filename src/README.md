# API
You can find the routes in `routes/web.php`
```php
    $router->get('notes', ['uses' => 'NoteController@showAllNotes']);
    $router->get('notes/{id}', ['uses' => 'NoteController@showOneNote']);
    $router->post('notes', ['uses' => 'NoteController@create']);
    $router->delete('notes/{id}', ['uses' => 'NoteController@delete']);
    $router->put('notes/{id}', ['uses' => 'NoteController@update']);
    $router->post('notes/{id}/upload', ['uses' => 'NoteController@upload']);
    $router->get('notes/{id}/download', ['uses' => 'NoteController@download']);
```
