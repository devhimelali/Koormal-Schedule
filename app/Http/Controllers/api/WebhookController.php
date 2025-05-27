<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ColumnName;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function handleWebhook(Request $request): JsonResponse
    {
        Log::info('Webhook received', ['data' => $request->all()]);

        $category = $request->input('hidden_category');
        $formData = $request->except('hidden_category');

        // Generate a unique table name dynamically based on the category
        $maxTableNameLength = 64;
        $prefix = 'forms_';

        // Truncate the category name if it exceeds the maximum length
        $maxCategoryLength = $maxTableNameLength - strlen($prefix);
        $tableName = $prefix.substr(str_replace('-', '_', strtolower($category)), 0, $maxCategoryLength);

        $maxColumnNameLength = 64;
        $sanitizedFormData = [];
        foreach ($formData as $key => $value) {
            $sanitizedKey = str_replace('-', '_', Str::slug($key, '_'));

            if (strlen($sanitizedKey) > $maxColumnNameLength - 3) {
                $sanitizedKey = substr($sanitizedKey, 0, $maxColumnNameLength - 3);
            }

            ColumnName::updateOrCreate(
                ['column_name' => $sanitizedKey],
                ['column_title' => $key]
            );

            if (is_null($value) || $value === '') {
                $value = 'Not answered';
            }

            $sanitizedFormData[$sanitizedKey] = $value;
        }

        $sanitizedFormData['created_at'] = now();
        $sanitizedFormData['updated_at'] = now();

        try {
            if (!Schema::hasTable($tableName)) {
                Schema::create($tableName, function ($table) {
                    $table->id();
                    $table->timestamps(); // Default timestamp columns
                });
            }

            $existingColumns = Schema::getColumnListing($tableName);

            $newColumns = array_diff(array_keys($sanitizedFormData), $existingColumns);

            foreach ($newColumns as $column) {
                Schema::table($tableName, function ($table) use ($column) {
                    $table->longText($column)->nullable(); // Adding a new column
                });
            }

            DB::table($tableName)->insert($sanitizedFormData);

        } catch (\Exception $e) {
            Log::error('Error handling webhook data', [
                'error' => $e->getMessage(),
                'data' => $sanitizedFormData,
                'category' => $category,
                'table' => $tableName
            ]);

            return response()->json([
                'message' => 'Failed to handle webhook data',
                'status' => false,
                'error' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'message' => 'success',
            'status' => true
        ], 200);
    }
}
