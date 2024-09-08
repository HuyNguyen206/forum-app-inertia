<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggleLike(Request $request, string $type, int $modelId)
    {
        /**
         * @var class-string<Model>|null $class
         */
        $class = Relation::getMorphedModel($type);
        if (null === $class) {
            throw new AuthorizationException();
        }

        $model = $class::findOrFail($modelId);

        $likeModel = $request->user()->likes()->where('likes.likeable_type', $type)->where('likes.likeable_id', $modelId)->first();
        $isLike = true;

        if ($likeModel) {
//            $this->authorize('create', [Like::class, $model]);
            $isLike = false;
            $likeModel->delete();
            $model->decrement('likes_count');
        } else {
            $request->user()->likes()->create([
                'likeable_type' => $type,
                'likeable_id' => $model->id,
            ]);
            $model->increment('likes_count');
        }

        return back()->with('success', $isLike ? "You like this $type" : "You dislike this $type");
    }
}
