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
class LearnerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, Applicant $applicant)
    {
//        $user->ability('', '\App\Models\Applicant.read', ['validate_all' => false])
        return 1;
//                    ? Response::allow()
//                    : Response::deny('You do not have permission to view any applicants.', 403);
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
