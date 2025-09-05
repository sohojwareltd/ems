<?php

namespace App\Http\Controllers;

use App\Models\AudioBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserAudioBookController extends Controller
{
    // Show the user's audiobook library
    public function index()
    {
        $user = Auth::user();
        $latestBook = $user->audioBooks()->latest()->first();
        $audiobooks = $user->audioBooks()->with('products')->get();
        return view('user.audiobooks', compact('audiobooks', 'latestBook'));
    }

    // Securely stream an audio file
    public function stream(Request $request, AudioBook $audiobook)
    {
        $user = Auth::user();
        if (!$user->audioBooks->contains($audiobook)) {
            abort(403);
        }
        $file = $request->query('file');
        if (!$file || !self::fileInAudioBook($audiobook, $file)) {
            abort(404);
        }
        $disk = config('filesystems.default', 'public');
        if (!Storage::disk($disk)->exists($file)) {
            abort(404);
        }
        return response()->file(Storage::disk($disk)->path($file));
    }

    // Securely download an audio file
    public function download(Request $request, AudioBook $audiobook)
    {
        $user = Auth::user();
        if (!$user->audioBooks->contains($audiobook)) {
            abort(403);
        }
        $file = $request->query('file');
        if (!$file || !self::fileInAudioBook($audiobook, $file)) {
            abort(404);
        }
        $limit = $audiobook->download_limit ?? config('audiobooks.download_limit'); // per-audiobook or fallback
        $count = $user->getAudioBookDownloadCount($audiobook->id, $file);
        if ($limit !== null && $count >= $limit) {
            return response('Download limit reached for this file.', 429);
        }
        $user->incrementAudioBookDownloadCount($audiobook->id, $file);
        $disk = config('filesystems.default', 'public');
        if (!Storage::disk($disk)->exists($file)) {
            abort(404);
        }
        // Return file directly for download
        return response()->download(Storage::disk($disk)->path($file));
    }

    // Publicly stream a trial audio file
    public function trialStream(Request $request, AudioBook $audiobook)
    {
        $file = $request->query('file');
        if (!$file || !self::fileInAudioBook($audiobook, $file, true)) {
            abort(404);
        }
        $disk = config('filesystems.default', 'public');
        if (!Storage::disk($disk)->exists($file)) {
            abort(404);
        }
        return response()->file(Storage::disk($disk)->path($file));
    }

    // Download full audiobook as ZIP
    public function downloadZip(AudioBook $audiobook)
    {
        $user = Auth::user();
        if (!$user->audioBooks->contains($audiobook)) {
            abort(403);
        }

        // Check download limit for ZIP
        $limit = $audiobook->download_limit ?? config('audiobooks.download_limit');
        $count = $user->getAudioBookDownloadCount($audiobook->id, 'zip');
        if ($limit !== null && $count >= $limit) {
            return response('Download limit reached for this audiobook.', 429);
        }

        $user->incrementAudioBookDownloadCount($audiobook->id, 'zip');

        $disk = config('filesystems.default', 'public');
        $zipName = 'audiobook_' . $audiobook->id . '_' . now()->format('Y-m-d_H-i-s') . '.zip';
        
        return response()->streamDownload(function () use ($audiobook, $disk) {
            $zip = new \ZipArchive();
            $tempFile = tempnam(sys_get_temp_dir(), 'audiobook_zip_');
            $zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
            
            // Add cover image if exists
            if ($audiobook->cover_image && Storage::disk($disk)->exists($audiobook->cover_image)) {
                $zip->addFromString('cover.jpg', Storage::disk($disk)->get($audiobook->cover_image));
            }
            
            // Add audio files
            foreach ($audiobook->audio_files ?? [] as $index => $track) {
             
                if (isset($track['file']) && Storage::disk($disk)->exists($track['file'])) {
                    $fileName = sprintf('%02d_%s.mp3', $index + 1, $track['title'] ?? 'track_' . ($index + 1));
                    $zip->addFromString($fileName, Storage::disk($disk)->get($track['file']));
                }
            }
            
            $zip->close();
            
            // Output the ZIP file
            readfile($tempFile);
            unlink($tempFile); // Clean up
        }, $zipName, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename="' . $zipName . '"'
        ]);
    }

    // Helper: check if file is in the audiobook's audio_files JSON
    private static function fileInAudioBook(AudioBook $audiobook, $file, $trialOnly = false)
    {
        foreach ($audiobook->audio_files ?? [] as $track) {
            if (isset($track['file']) && $track['file'] === $file) {
                if ($trialOnly && empty($track['trial'])) continue;
                return true;
            }
        }
        return false;
    }
} 