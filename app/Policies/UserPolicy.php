<?php

namespace App\Policies;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

/**
 * Class LearnerPolicy
 * 尚未使用
 *
 * @package App\Policies
 */
class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User | \App\User $user, Applicant $applicant)
    {
        dd(11);
        return $user->id === $applicant->id;
        $user->ability('', '\App\Models\Applicant.read', ['validate_all' => false])
                    ? Response::allow()
                    : Response::deny('You do not have permission to view any applicants.', 403);
    }

    public function view(User $user, Applicant $applicant): bool
    {
        return $user->can('\App\Models\Applicant.read');
    }

    public function create(User $user): bool
    {
    }

    public function update(User $user, Applicant $applicant): bool
    {
    }

    public function delete(User $user, Applicant $applicant): bool
    {
    }

    public function restore(User $user, Applicant $applicant): bool
    {
    }

    public function forceDelete(User $user, Applicant $applicant): bool
    {
    }
}
