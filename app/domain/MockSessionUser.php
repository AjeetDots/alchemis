<?php

class MockSessionUser {
    public function hasPermission($permission) {
        // For now, grant all permissions to avoid errors
        return true;
    }
}