<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class Controller
{
    /**
     * Menentukan apakah request meminta response JSON (API).
     */
    protected function wantsJson(Request $request): bool
    {
        return $request->wantsJson() || $request->is('api/*') || $request->query('format') === 'json';
    }
}
