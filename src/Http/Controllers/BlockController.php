<?php

namespace Yormy\TripwireLaravel\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Routing\Controller;
use Yormy\TripwireLaravel\Http\Controllers\Resources\BlockCollection;
use Yormy\TripwireLaravel\Repositories\BlockRepository;

class BlockController extends controller
{
    public function index()
    {
        $blockRepository = new BlockRepository();
        $blocks = $blockRepository->getAll();

        $blocks = (new BlockCollection($blocks))->toArray(null);

        return response()->json($blocks);
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
