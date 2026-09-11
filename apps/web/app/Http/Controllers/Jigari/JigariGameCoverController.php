<?php

declare(strict_types=1);

namespace App\Http\Controllers\Jigari;

use App\Http\Controllers\Controller;
use App\Jigari\JigariCatalog;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class JigariGameCoverController extends Controller
{
    public function __invoke(Game $game, JigariCatalog $catalog): StreamedResponse
    {
        $asset = $catalog->cover($game);
        abort_unless($asset, 404);

        return Storage::disk($asset->disk)->response($asset->path, null, [
            'Content-Type' => $asset->mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}
