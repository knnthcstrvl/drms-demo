<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataFeed;

class AuditLogsController extends Controller
{
    public function index()
    {
        $dataFeed = new DataFeed();

        return view('pages.audit_logs.audit-logs', compact('dataFeed'));
    }
}
