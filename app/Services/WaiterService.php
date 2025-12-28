<?php

namespace App\Services;

use App\Http\Requests\WaiterRequest;
use Exception;
use App\Enums\Ask;
use App\Models\User;
use App\Enums\Role as EnumRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Requests\UserChangePasswordRequest;


class WaiterService
{
    public object $waiter;
    public array $phoneFilter = ['phone'];
    public array $roleFilter = ['role_id'];
    public array $waiterFilter = ['name', 'email', 'username', 'branch_id', 'status', 'phone'];
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

            return User::with('media', 'addresses', 'messages')->role(EnumRole::WAITER)->where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->waiterFilter)) {
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
    public function store(WaiterRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->waiter = User::create([
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
                $this->waiter->assignRole(EnumRole::WAITER);
            });
            return $this->waiter;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(WaiterRequest $request, User $waiter)
    {
        try {
            if (!in_array(EnumRole::WAITER, $this->blockRoles)) {
                DB::transaction(function () use ($waiter, $request) {
                    $this->waiter               = $waiter;
                    $this->waiter->name         = $request->name;
                    $this->waiter->email        = $request->email;
                    $this->waiter->phone        = $request->phone;
                    $this->waiter->status       = $request->status;
                    $this->waiter->country_code = $request->country_code;
                    if ($request->password) {
                        $this->waiter->password = Hash::make($request->password);
                    }
                    $this->waiter->branch_id     = $request->branch_id;
                    $this->waiter->save();
                });
                return $this->waiter;
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
    public function show(User $waiter): User
    {
        try {
            if (!in_array(EnumRole::WAITER, $this->blockRoles)) {
                return $waiter;
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
    public function destroy(User $waiter)
    {
        try {
            if (!in_array(EnumRole::WAITER, $this->blockRoles)) {
                if ($waiter->hasRole(EnumRole::WAITER)) {
                    DB::transaction(function () use ($waiter) {
                        $waiter->addresses()->delete();
                        $waiter->delete();
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
    public function changePassword(UserChangePasswordRequest $request, User $waiter): User
    {
        try {
            if (!in_array(EnumRole::WAITER, $this->blockRoles)) {
                $waiter->password = Hash::make($request->password);
                $waiter->save();
                return $waiter;
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
    public function changeImage(ChangeImageRequest $request, User $waiter): User
    {
        try {
            if (!in_array(EnumRole::WAITER, $this->blockRoles)) {
                if ($request->image) {
                    $waiter->clearMediaCollection('profile');
                    $waiter->addMediaFromRequest('image')->toMediaCollection('profile');
                }
                return $waiter;
            } else {
                throw new Exception(trans('all.message.permission_denied'), 422);
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}
