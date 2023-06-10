<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Yormy\TripwireLaravel\Http\Controllers\Resources\LogCollection;
use Yormy\TripwireLaravel\Repositories\LogRepository;


class LogController extends controller
{
    public function index()
    {
        $logRepository = new LogRepository();
        $logs = $logRepository->getAll();

        $logs = (new LogCollection($logs))->toArray(null);

        dd($logs);
//
//        return inertia('bedrock-users::Admin/Users/Members/Index', [
//            'users' => $members,
//            'roles' => $roles,
//            'memberships' => $memberships,
//            'impersonateAllowed' => config('bedrock-users.login.impersonation_allow_by_admin')
//        ]);
    }

    public function generateDropdownRoles(Collection $roleModels): array
    {
        $roles = array();
        foreach ($roleModels as $roleModel) {
            $role['name'] = $roleModel->name;
            $role['key'] = $roleModel->name;
            $roles[] = $role;
        }

        $role = array();
        $role['name'] = __('bedrock-users::misc.none');
        $role['key'] = __('bedrock-users::misc.no_roles');
        $roles[] = $role;

        return $roles;
    }

    public function generateDropdownMembership(Collection $billingPlans): array
    {
        $memberships = array();
        foreach ($billingPlans as $plan) {
            $period = $plan->billing_period ==="MONTHLY"
                ? " ". __('bedrock-users::billing.dash_month')
                : " ". __('bedrock-users::billing.dash_year');
            ;
            $membership['name'] = $plan->name . $period;
            $membership['key'] = $plan->xid;
            $memberships[] = $membership;
        }

        $membership = array();
        $membership['name'] = __('bedrock-users::misc.none');
        $membership['key'] = __('bedrock-users::misc.no_membership');
        $memberships[] = $membership;

        return $memberships;
    }
}
