<?php

if (!function_exists('success')) {
    function success($message = null, $data = [], $status = true, $status_code = 200, $meta = [])
    {
        $response = [
            'success' => $status,
            'message' => $message ?? 'Operation completed successfully',
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        // Add request ID for debugging in non-production environments
        if (!app()->environment('production')) {
            $response['request_id'] = request()->header('X-Request-ID', uniqid());
        }

        return response()->json($response, $status_code);
    }
}

if (!function_exists('failed')) {
    function failed($message = null, $data = null, $status = false, $status_code = 400, $errors = [])
    {
        $response = [
            'success' => $status,
            'message' => $message ?? 'Operation failed',
            'timestamp' => now()->toISOString(),
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        // Add debugging information for development
        if (!app()->environment('production')) {
            $response['debug'] = [
                'request_id' => request()->header('X-Request-ID', uniqid()),
                'endpoint' => request()->fullUrl(),
                'method' => request()->method(),
                'user_id' => auth()->id(),
                'user_roles' => auth()->check() ? auth()->user()->getRoleNames() : [],
            ];
        }

        // Log errors for monitoring
        if ($status_code >= 500) {
            \Illuminate\Support\Facades\Log::error('Server Error', [
                'message' => $message,
                'status_code' => $status_code,
                'data' => $data,
                'user_id' => auth()->id(),
                'endpoint' => request()->fullUrl(),
                'method' => request()->method(),
            ]);
        } elseif ($status_code === 403) {
            \Illuminate\Support\Facades\Log::warning('Access Denied', [
                'message' => $message,
                'data' => $data,
                'user_id' => auth()->id(),
                'user_roles' => auth()->check() ? auth()->user()->getRoleNames() : [],
                'endpoint' => request()->fullUrl(),
                'method' => request()->method(),
            ]);
        }

        return response()->json($response, $status_code);
    }
}

if (!function_exists('validation_failed')) {
    function validation_failed($message = 'Validation failed', $errors = [])
    {
        return failed(
            $message,
            ['validation_errors' => $errors],
            false,
            422,
            $errors
        );
    }
}

if (!function_exists('unauthorized')) {
    function unauthorized($message = 'Authentication required', $data = [])
    {
        return failed(
            $message,
            array_merge($data, ['hint' => 'Please login to access this resource']),
            false,
            401
        );
    }
}

if (!function_exists('forbidden')) {
    function forbidden($message = 'Access denied', $data = [])
    {
        $user = auth()->user();
        
        return failed(
            $message,
            array_merge($data, [
                'hint' => 'You do not have permission to perform this action',
                'user_permissions' => $user ? $user->getAllPermissions()->pluck('name') : [],
                'user_roles' => $user ? $user->getRoleNames() : [],
            ]),
            false,
            403
        );
    }
}

if (!function_exists('not_found')) {
    function not_found($message = 'Resource not found', $resource = null)
    {
        return failed(
            $message,
            [
                'resource' => $resource,
                'hint' => 'The requested resource could not be found',
            ],
            false,
            404
        );
    }
}

if (!function_exists('server_error')) {
    function server_error($message = 'Internal server error', $data = [])
    {
        return failed(
            $message,
            array_merge($data, [
                'hint' => 'An unexpected error occurred. Please try again later.',
            ]),
            false,
            500
        );
    }
}

if (!function_exists('created')) {
    function created($message, $data = [])
    {
        return success(
            $message ?? 'Resource created successfully',
            $data,
            true,
            201
        );
    }
}

if (!function_exists('updated')) {
    function updated($message, $data = [])
    {
        return success(
            $message ?? 'Resource updated successfully',
            $data,
            true,
            200
        );
    }
}

if (!function_exists('deleted')) {
    function deleted($message = 'Resource deleted successfully')
    {
        return success($message, [], true, 200);
    }
}

if (!function_exists('paginated_success')) {
    function paginated_success($message, $data, $pagination_info = [])
    {
        return success(
            $message,
            $data,
            true,
            200,
            [
                'pagination' => $pagination_info,
            ]
        );
    }
}

if (!function_exists('no_content')) {
    function no_content()
    {
        return response()->json(null, 204);
    }
}