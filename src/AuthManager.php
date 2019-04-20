<?php

namespace Bendt\auth;

use Illuminate\Support\Facades\Auth;

class AuthManager
{
    public static $ROLE_SESSION_KEY = "bendtroles";
    private static $_ROLES = null;

    public static function userInAnyRole($roles)
    {
        if(self::userIsRoot()) return true;

        $roles_from_session = self::getRoles();

        if(is_string($roles)) $roles = [$roles];

        if($roles_from_session) {
            foreach ($roles as $role)
            {
                if(in_array($role, $roles_from_session)) return true;
            }
        }

        return false;
    }

    public static function userInAllRoles($roles)
    {
        if(self::userIsRoot()) return true;

        $roles_from_session = self::getRoles();

        if(is_string($roles)) $roles = [$roles];

        if($roles_from_session) {
            foreach ($roles as $role)
            {
                if(!in_array($role, $roles_from_session)) return false;
            }

            return true;
        }

        return false;
    }

    public static function userIsRoot()
    {
        return Auth::user()->is_root;
    }

    public static function getRoles()
    {
        if(is_null(self::$_ROLES)) {
            self::$_ROLES = self::getRolesFromSession();
        }

        return self::$_ROLES;
    }

    private static function getRolesFromSession()
    {
        if(session()->has(self::$ROLE_SESSION_KEY)) {
            return session(self::$ROLE_SESSION_KEY);
        }
        else {
            $roles = Auth::user()->getRoleList();
            self::saveRolesToSession($roles);
            return $roles;
        }
    }

    public static function saveRolesToSession($roles)
    {
        return session([self::$ROLE_SESSION_KEY => $roles]);
    }

}
