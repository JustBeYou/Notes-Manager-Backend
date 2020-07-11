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

    public function create(Request $request)
    {
        $data = $request->only(['type', 'original_filename', 'name', 'link', 'text']);
        if ($data['type'] >= NoteType::INVALID) {
            return response()->json([
                'status' => 'Invalid note type',
            ], 500);
        }

        if ($data['type'] === NoteType::FILE) {
            $data['filename'] = Str::uuid()->toString();
        }
        $Note = Note::create($data);

        return response()->json($Note, 201);
    }

    public function update($id, Request $request)
    {
        $Note = Note::findOrFail($id);
        $data = $request->only(['type', 'original_filename', 'name', 'link', 'text']);
        $Note->update($data);

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
            return response()->json([
                'status' => 'No file sent'
            ], 500);
        }
        return response()->json([
            'status' => 'Uploaded Successfully'
        ], 200);
    }

    public function download($id) {
        $note = Note::find($id);
        $headers = ['Content-Type' => 'text/plain'];

        $path = storage_path('app/notes/' . $note->filename);

        if (!\Storage::disk('local')->exists('notes/' . $note->filename)) {
            return response()->json([
                'status' => 'File not found.',
            ], 500);
        }

        return response()->download(
            $path,
            $note->original_filename,
            $headers
        );
    }
}
