<?php

namespace App\Services;

use App\Http\Requests\ChefRequest;
use Exception;
use App\Enums\Ask;
use App\Models\User;
use App\Enums\Role as EnumRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\UserChangePasswordRequest;


class ChefService
{
    public object $chef;
    public array $phoneFilter = ['phone'];
    public array $roleFilter = ['role_id'];
    public array $chefFilter = ['name', 'email', 'username', 'branch_id', 'status', 'phone'];
    public array $blockRoles = [EnumRole::ADMIN];


    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return User::with('media', 'addresses', 'messages')->role(EnumRole::CHEF)->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->chefFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(ChefRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->chef = User::create([
                    'name'              => $request->name,
                    'email'             => $request->email,
                    'phone'             => $request->phone,
                    'username'          => $this->username($request->email),
                    'password'          => bcrypt($request->password),
                    'branch_id'         => $request->branch_id,
                    'email_verified_at' => now(),
                    'status'            => $request->status,
                    'country_code'      => $request->country_code,
                    'is_guest'          => Ask::NO,
                ]);
                $this->chef->assignRole(EnumRole::CHEF);
            });
            return $this->chef;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(ChefRequest $request, User $chef)
    {
        try {
            if (!in_array(EnumRole::CHEF, $this->blockRoles)) {
                DB::transaction(function () use ($chef, $request) {
                    $this->chef               = $chef;
                    $this->chef->name         = $request->name;
                    $this->chef->email        = $request->email;
                    $this->chef->phone        = $request->phone;
                    $this->chef->status       = $request->status;
                    $this->chef->country_code = $request->country_code;
                    if ($request->password) {
                        $this->chef->password = Hash::make($request->password);
                    }
                    $this->chef->branch_id     = $request->branch_id;
                    $this->chef->save();
                });
                return $this->chef;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show(User $chef): User
    {
        try {
            if (!in_array(EnumRole::CHEF, $this->blockRoles)) {
                return $chef;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy(User $chef)
    {
        try {
            if (!in_array(EnumRole::CHEF, $this->blockRoles)) {
                if ($chef->hasRole(EnumRole::CHEF)) {
                    DB::transaction(function () use ($chef) {
                        $chef->addresses()->delete();
                        $chef->delete();
                    });
                } else {
                    throw new Exception(trans('all.message.permission_denied'), 422);
                }
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            DB::rollBack();
            throw new Exception($exception->getMessage(), 422);
        }
    }

    private function username($email): string
    {
        $emails = explode('@', $email);
        return $emails[0] . mt_rand();
    }

    /**
     * @throws Exception
     */
    public function changePassword(UserChangePasswordRequest $request, User $chef): User
    {
        try {
            if (!in_array(EnumRole::CHEF, $this->blockRoles)) {
                $chef->password = Hash::make($request->password);
                $chef->save();
                return $chef;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function changeImage(ChangeImageRequest $request, User $chef): User
    {
        try {
            if (!in_array(EnumRole::CHEF, $this->blockRoles)) {
                if ($request->image) {
                    $chef->clearMediaCollection('profile');
                    $chef->addMediaFromRequest('image')->toMediaCollection('profile');
                }
                return $chef;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
