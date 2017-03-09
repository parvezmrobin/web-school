<?php

namespace App\Policies;

use App\User;
use App\Editor;
use App\ClassSectionYear;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
    * Create a new policy instance.
    *
    * @return void
    */
    public function __construct(User $user)
    {

    }

    public function before($user, $ability)
    {
        if ($user->isInRole('admin')) {
            return true;
        }
    }

    public function transact(User $user, ClassSectionYear $csy)
    {
        if (!$user->isInRole('editor')) {
            return false;
        }

        $editor = Editor::where('user_id', $user->id)->first;
        $count = \DB::table('transaction_auth')
            ->where('editor_id', $editor->id)
            ->where('class_section_year_id', $csy->id)
            ->count();
        if($count === 0)
            return false;

        return true;
    }

    
}
