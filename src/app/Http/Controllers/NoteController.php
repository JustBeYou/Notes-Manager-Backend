<?php

namespace App\Http\Controllers;

use App\Note;
use App\NoteType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoteController extends Controller
{

    public function showAllNotes()
    {
        return response()->json(Note::all());
    }

    public function showOneNote($id)
    {
        return response()->json(Note::find($id));
    }

    public function create(Request $request)
    {
        $data = $request->all();
        if ($data['type'] === NoteType::FILE) {
            $data['filename'] = Str::uuid()->toString();
        }
        $Note = Note::create($data);

        return response()->json($Note, 201);
    }

    public function update($id, Request $request)
    {
        $Note = Note::findOrFail($id);
        $Note->update($request->all());

        return response()->json($Note, 200);
    }

    public function delete($id)
    {
        Note::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function upload($id, Request $request) {
        if ($request->hasFile('note')) {
            $noteFile = $request->file('note');
            $note = Note::find($id);
            $noteFile->storeAs('notes', $note->filename);
        } else {
            return response('No file sent', 500);
        }
        return response('Uploaded Successfully', 200);
    }

    public function download($id, Request $request) {
        $note = Note::find($id);
        $headers = ['Content-Type' => 'text/plain'];
        return response()->download(
            storage_path('app/notes/' . $note->filename),
            $note->original_filename,
            $headers
        );
    }
}
