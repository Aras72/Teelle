<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Collections\PublicCollectionCatalog;
use App\Models\EditorialCollection;
use App\Models\Game;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class CollectionCoverController extends Controller
{
    public function __invoke(EditorialCollection $collection, Game $game, PublicCollectionCatalog $catalog): StreamedResponse
    {
        $asset = $catalog->cover($collection, $game);
        abort_unless($asset, 404);

        return Storage::disk($asset->disk)->response($asset->path, null, [
            'Content-Type' => $asset->mime, 'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'public, max-age=300',
        ]);
    }
}
