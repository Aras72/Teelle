<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Collections\PublicCollectionCatalog;
use App\Models\EditorialCollection;
use Illuminate\View\View;

final class CollectionController extends Controller
{
    public function index(PublicCollectionCatalog $catalog): View
    {
        return view('collections.index', ['collections' => $catalog->collections()]);
    }

    public function show(EditorialCollection $collection, PublicCollectionCatalog $catalog): View
    {
        return view('collections.show', ['collection' => $catalog->collection($collection)]);
    }
}
