<?php

use App\Http\Controllers\PrivilegeController;
use App\Models\FollowupOfficer;
use App\Models\menu;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

    if(!function_exists("is_officer")){
    /**
     * Checks if there are any FollowupOfficer records matching the given user ID and action.
     *
     * @param int $userId The ID of the user to check for existence.
     * @param string $action The action to match, which may end with a '%' wildcard for partial matching.
     * @return bool Returns true if any records exist matching the criteria, otherwise false.
     */
        function is_officer(int $userId, string $action){
            if(str_ends_with($action, "%")){
                $isExists = FollowupOfficer::whereLike('action', $action)->where('user_id', $userId)->exists();
            }else{
                $isExists = FollowupOfficer::where('action', $action)->where('user_id', $userId)->exists();
            }
            return $isExists;
        }
    }

    if(!function_exists("is_users_exist_in_officer")){
    /**
     * Checks if there are any FollowupOfficer records matching the given user IDs and action.
     *
     * @param array $userIds An array of user IDs to check for existence.
     * @param string $action The action to match, which may end with a '%' wildcard for partial matching.
     * @return bool Returns true if any records exist matching the criteria, otherwise false.
     */
        function is_users_exist_in_officer(array $userIds, $action){
            if(str_ends_with($action, "%")){
                $isExist = FollowupOfficer::whereLike('action', $action)->whereIn('user_id', $userIds)->exists();
            }else{
                $isExist = FollowupOfficer::where('action', $action)->whereIn('user_id', $userIds)->exists();
            }
            return $isExist;
        }
    }

    if(!function_exists("get_officers_by_action")){
    /**
     * Retrieves all FollowupOfficer records that match the given action.
     *
     * @param string $action The action to match, which may end with a '%' wildcard for partial matching.
     * @return \Illuminate\Database\Eloquent\Collection A collection of FollowupOfficer records.
     */
        function get_officers_by_action(string $action){
            if(str_ends_with($action, "%")){
                return FollowupOfficer::whereLike('action', $action)->get();
            }
            return FollowupOfficer::where('action', $action)->get();
        }
    }

    if(!function_exists("get_users_officer_by_action")){
    /**
     * Get all users who have the given action in FollowupOfficer, and matches the given user ID.
     *
     * @param string $action The action to match, which may end with a '%' wildcard for partial matching.
     * @param int $userId The ID of the user to filter by.
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\User[]
     */
        function get_users_officer_by_action(string $action){
            $officers = get_officers_by_action($action);
            return User::whereIn('id', $officers->pluck('user_id'))->get();
        }
    }

    if(!function_exists("is_have_privilege")){
    /**
     * Determines if the current user has the specified privilege for a given route.
     *
     * @param string $routename The URL route name to check privileges against.
     * @param int $mode The privilege mode to check, defaults to 0. 
     *                  Possible values are:
     *                  0 - list
     *                  1 - add
     *                  2 - edit
     *                  4 - view
     *                  8 - delete
     * @return bool Returns true if the user has the specified privilege, otherwise false.
     */
        function is_have_privilege($routename,$mode = 0){
            try{
                return PrivilegeController::privilege_check(menu::where('url', $routename)->get()->pluck('id'), $mode);
            }catch(Exception $e){
                return false;
            }
        }
    }

    if(!function_exists("is_superuser")){
    /**
     * Determines if the current user or the specified user is a superuser.
     *
     * @param int|null $userId The ID of the user to check, or null to check the current user.
     * @return bool Returns true if the user is a superuser, otherwise false.
     */
        function is_superuser(int|null $userId=null):bool{
            if($userId) return User::find($userId)->privilege_id == 1;
            return Auth::user()->privilege_id == 1;
        }
    }

    if(!function_exists("cleanStr")){
    /**
     * Remove all non-alphanumeric characters from a string and uppercase the result.
     * This is useful for cleaning up strings used in URLs or identifiers.
     *
     * @param string $str The string to clean.
     * @return string The cleaned string.
     */
        function cleanStr($str){
            return strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $str));
        }
    }
    if(!function_exists("uploadFile")){
        function uploadFile($file, $path){
            if($file){
                $fileName = time(). '.' . $file->getClientOriginalExtension();
                $file->move(public_path($path), $fileName);
                return $path . '/' . $fileName;
            }
            return null;
        }
    }