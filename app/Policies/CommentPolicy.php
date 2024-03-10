<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;

class CommentPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return $user->is($comment->user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Comment $comment): bool
    {
        $isPassOneHour = Carbon::now()->diffInMinutes($comment->created_at) > 60;

        return !$isPassOneHour && $user->is($comment->user);
    }
}
