<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * @OA\Info(
 *     title="Translation API",
 *     version="1.0.0",
 *     description="API for managing translations in the application",
 *     @OA\Contact(
 *         email="support@yourapp.com"
 *     ),
 * )
 */
class TranslationController extends Controller
{
    /**
     * Store a new translation.
     *
     * @OA\Post(
     *     path="/api/translations",
     *     summary="Create a new translation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"key", "content"},
     *             @OA\Property(property="key", type="string", example="welcome_message"),
     *             @OA\Property(property="content", type="object", example={"en": "Welcome", "fr": "Bienvenue"}),
     *             @OA\Property(property="tag", type="string", example="web")
     *         )
     *     ),
     *     @OA\Response(response="201", description="Translation created"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:translations',
            'content' => 'required|json',
            'tag' => 'nullable|string',
        ]);

        $translation = Translation::create($request->only('key', 'content', 'tag'));
        return response()->json($translation, 201);
    }

    /**
     * Update an existing translation.
     *
     * @OA\Put(
     *     path="/api/translations/{id}",
     *     summary="Update a translation",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"content"},
     *             @OA\Property(property="content", type="object", example={"en": "Welcome", "fr": "Bienvenue"}),
     *             @OA\Property(property="tag", type="string", example="web")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Translation updated"),
     *     @OA\Response(response="404", description="Translation not found"),
     *     @OA\Response(response="422", description="Validation error")
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|json',
            'tag' => 'nullable|string',
        ]);

        $translation = Translation::findOrFail($id);
        $translation->update($request->only('content', 'tag'));
        return response()->json($translation);
    }

    /**
     * Get a single translation by ID.
     *
     * @OA\Get(
     *     path="/api/translations/{id}",
     *     summary="Get a translation by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Translation found"),
     *     @OA\Response(response="404", description="Translation not found")
     * )
     */
    public function show($id)
    {
        $translation = Translation::findOrFail($id);
        return response()->json($translation);
    }

    /**
     * List translations with pagination and filtering.
     *
     * @OA\Get(
     *     path="/api/translations",
     *     summary="List translations",
     *     @OA\Parameter(
     *         name="key",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="tag",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Translations found"),
     *     @OA\Response(response="404", description="No translations found")
     * )
     */
    public function index(Request $request)
    {
        $query = Translation::query();

        // Apply filters if provided
        if ($request->has('key')) {
            $query->where('key', 'like', '%' . $request->key . '%');
        }

        if ($request->has('tag')) {
            $query->where('tag', $request->tag);
        }

        if ($request->has('content')) {
            $query->where('content', 'like', '%' . $request->content . '%');
        }

        // Paginate results (100 records per page)
        $translations = $query->paginate(100);
        return response()->json($translations);
    }

    /**
     * Export all translations as JSON.
     *
     * @OA\Get(
     *     path="/api/translations/export",
     *     summary="Export translations as JSON",
     *     @OA\Response(response="200", description="Translations exported"),
     *     @OA\Response(response="500", description="Server error")
     * )
     */
    public function export()
    {
        // Cache translations for 60 seconds to improve performance
         $translations = Cache::remember('translations_export', 60, function () {
            return Translation::all();
        });

        return response()->stream(function () use ($translations) {
            echo $translations->toJson();
        }, 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Search translations by key, tag, or content with pagination.
     *
     * @OA\Get(
     *     path="/api/translations/search",
     *     summary="Search translations",
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Translations found"),
     *     @OA\Response(response="404", description="No translations found")
     * )
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = Translation::query();

        // Search by key, tag, or content
        $query->where('key', 'like', '%' . $request->query . '%')
              ->orWhere('tag', 'like', '%' . $request->query . '%')
              ->orWhere('content', 'like', '%' . $request->query . '%');

        // Paginate results (100 records per page)
        $translations = $query->paginate(100);
        return response()->json($translations);
    }
}
