<?php

declare(strict_types=1);

namespace App\Http\Controllers\Play;

use App\Http\Controllers\Controller;
use App\Models\MatchSession;
use App\Play\PublicMatchAccess;
use App\Play\PublishedResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ResultCoverController extends Controller
{
    public function __invoke(Request $request, MatchSession $match, int $rank, PublicMatchAccess $access, PublishedResult $published): StreamedResponse
    {
        $access->assertMatch($request, $match);
        $result = $match->results()->where('rank', $rank)->with('game')->firstOrFail();
        abort_unless($published->hasCompleteSet($match) && $published->isAvailable($result), 404);
        $asset = DB::table('game_media')->join('media_assets', 'media_assets.id', '=', 'game_media.media_asset_id')
            ->where('game_media.game_version_id', $result->game_version_id)->where('game_media.role', 'cover')
            ->where('media_assets.status', 'reviewed')->whereIn('media_assets.mime', ['image/jpeg', 'image/png', 'image/webp'])
            ->first(['media_assets.disk', 'media_assets.path', 'media_assets.mime']);
        abort_unless($asset, 404);

        return Storage::disk($asset->disk)->response($asset->path, null, [
            'Content-Type' => $asset->mime,
            'Content-Disposition' => 'inline',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'private, max-age=300',
        ]);
    }
}
