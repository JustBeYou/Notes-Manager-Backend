<?php

use App\Note;
use Laravel\Lumen\Testing\DatabaseMigrations;

class ApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreate() {
        $this->json('POST', '/api/notes', [
           'name' => 'test note',
            'type' => \App\NoteType::INVALID,
        ])->seeStatusCode(500)->seeJson([
            'status' => 'Invalid note type',
        ]);

        $this->json('POST', '/api/notes', [
            'name' => 'test note',
            'type' => \App\NoteType::TEXT,
            'text' => 'test text',
        ])->seeStatusCode(201)->seeJson([
            'name' => 'test note',
            'text' => 'test text',
        ]);
    }

    public function testGetAll() {
        $textNote = factory(Note::class)->create([
            'type' => \App\NoteType::TEXT,
        ]);
        $linkNote = factory(Note::class)->create([
            'type' => \App\NoteType::LINK,
        ]);
        $fileNote = factory(Note::class)->create([
            'type' => \App\NoteType::FILE,
        ]);

        $this->json('GET', '/api/notes')->seeJson([
            'name' => $textNote->name,
            'text' => $textNote->text,
            'link' => $linkNote->link,
            'original_filename' => $fileNote->original_filename,
        ]);
    }

    public function testDelete() {
        $textNote = factory(Note::class)->create([
            'type' => \App\NoteType::TEXT,
        ]);

        $id = $textNote->id;
        $this->json('DELETE', '/api/notes/' . $id)->seeStatusCode(200);

        $this->assertFalse(Note::where('id', '=', $id)->exists());
    }

    public function testUpdate() {
        $textNote = factory(Note::class)->create([
            'type' => \App\NoteType::TEXT,
        ]);

        $this->json('PUT', '/api/notes/' . $textNote->id, [
            'text' => 'sample text',
        ]);
        $this->assertTrue(Note::where('text', '=', 'sample text')->exists());
    }

    public function testUpload() {
        $file = \Illuminate\Http\UploadedFile::fake()->create('test.jpg', 100);
        $fileNote = factory(Note::class)->create([
            'type' => \App\NoteType::FILE,
            'original_filename' => 'test.jpg',
            'filename' => 'test.jpg',
        ]);

        $this->call('POST', '/api/notes/' . $fileNote->id . '/upload', [], [], [
           'note' => $file,
        ], []);

        $this->assertTrue(\Storage::disk('local')->exists('notes/test.jpg'));
    }
}
