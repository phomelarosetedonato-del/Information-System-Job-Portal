<?php

namespace App\Http\Controllers\Accessibility;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class AccessibilityController extends Controller
{
    /**
     * Show accessibility settings page
     */
    public function settings()
    {
        $preferences = self::getPreferences();
        return view('accessibility.settings', compact('preferences'));
    }

    /**
     * Update accessibility preferences
     */
    public function updatePreferences(Request $request)
    {
        $preferences = [
            'font_size' => $request->input('font_size', 'medium'),
            'contrast' => $request->input('contrast', 'normal'),
            'simplified_layout' => $request->boolean('simplified_layout', false),
            'reduced_motion' => $request->boolean('reduced_motion', false),
            'high_visibility_focus' => $request->boolean('high_visibility_focus', true),
            'screen_reader_optimized' => $request->boolean('screen_reader_optimized', false),
            'language' => $request->input('language', 'en'), // Add language preference
        ];

        $cookie = cookie('accessibility_preferences', json_encode($preferences), 60 * 24 * 30); // 30 days

        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', __('Accessibility preferences updated successfully!'));
    }

    /**
     * Reset accessibility preferences
     */
    public function resetPreferences()
    {
        $cookie = Cookie::forget('accessibility_preferences');

        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', __('Accessibility preferences reset to default.'));
    }

    /**
     * Get accessibility preferences (static method for use in layouts)
     */
    public static function getPreferences()
    {
        $preferences = request()->cookie('accessibility_preferences');

        if ($preferences) {
            return json_decode($preferences, true);
        }

        return [
            'font_size' => 'medium',
            'contrast' => 'normal',
            'simplified_layout' => false,
            'reduced_motion' => false,
            'high_visibility_focus' => true,
            'screen_reader_optimized' => false,
            'language' => 'en', // Default language
        ];
    }

    /**
     * Apply accessibility classes to body
     */
    public static function getBodyClasses()
    {
        $preferences = self::getPreferences();
        $classes = [];

        if ($preferences['font_size'] !== 'medium') {
            $classes[] = 'font-size-' . $preferences['font_size'];
        }

        if ($preferences['contrast'] !== 'normal') {
            $classes[] = 'contrast-' . $preferences['contrast'];
        }

        if ($preferences['simplified_layout']) {
            $classes[] = 'simplified-layout';
        }

        if ($preferences['reduced_motion']) {
            $classes[] = 'reduced-motion';
        }

        if ($preferences['high_visibility_focus']) {
            $classes[] = 'high-visibility-focus';
        }

        if ($preferences['screen_reader_optimized']) {
            $classes[] = 'screen-reader-optimized';
        }

        // Add language class
        if (isset($preferences['language'])) {
            $classes[] = 'lang-' . $preferences['language'];
        }

        return implode(' ', $classes);
    }

    /**
     * Quick accessibility tools
     */
    public function quickTool(Request $request)
    {
        // Support both formats: {tool: 'language', language: 'tl'} OR just {language: 'tl'}
        $tool = $request->input('tool');
        $action = $request->input('action');
        
        // If language is provided directly without tool parameter, handle it
        if (!$tool && $request->has('language')) {
            return $this->handleLanguage($request->input('language'));
        }

        switch ($tool) {
            case 'font_size':
                return $this->handleFontSize($action);
            case 'contrast':
                return $this->handleContrast($action);
            case 'read_aloud':
                return $this->handleReadAloud($request);
            case 'language':
                return $this->handleLanguage($request->input('language'));
            default:
                return response()->json(['error' => 'Invalid tool'], 400);
        }
    }

    private function handleFontSize($action)
    {
        $sizes = ['small', 'medium', 'large', 'xlarge'];
        $preferences = self::getPreferences();
        $currentSize = $preferences['font_size'];
        $currentIndex = array_search($currentSize, $sizes);

        if ($action === 'increase' && $currentIndex < count($sizes) - 1) {
            $newSize = $sizes[$currentIndex + 1];
        } elseif ($action === 'decrease' && $currentIndex > 0) {
            $newSize = $sizes[$currentIndex - 1];
        } else {
            $newSize = $currentSize;
        }

        $preferences['font_size'] = $newSize;
        $cookie = cookie('accessibility_preferences', json_encode($preferences), 60 * 24 * 30);

        return response()->json(['size' => $newSize])->withCookie($cookie);
    }

    private function handleContrast($action)
{
    $levels = ['normal', 'high', 'very-high'];
    $preferences = self::getPreferences();
    $currentContrast = $preferences['contrast'];
    $currentIndex = array_search($currentContrast, $levels);

    if ($action === 'increase' && $currentIndex < count($levels) - 1) {
        $newContrast = $levels[$currentIndex + 1];
    } elseif ($action === 'decrease' && $currentIndex > 0) {
        $newContrast = $levels[$currentIndex - 1];
    } else {
        $newContrast = $currentContrast;
    }

    $preferences['contrast'] = $newContrast;
    $cookie = cookie('accessibility_preferences', json_encode($preferences), 60 * 24 * 30);

    return response()->json([
        'contrast' => $newContrast,
        'message' => $this->getContrastMessage($newContrast)
    ])->withCookie($cookie);
}

private function getContrastMessage($contrast)
{
    switch($contrast) {
        case 'very-high':
            return 'Very high contrast mode activated. Text is white on black background.';
        case 'high':
            return 'High contrast mode activated.';
        default:
            return 'Normal contrast mode activated.';
    }
}
    private function handleReadAloud(Request $request)
    {
        $text = $request->input('text', '');
        // This would integrate with a text-to-speech service
        return response()->json(['status' => 'success', 'text' => $text]);
    }

    /**
     * Handle language translation
     */
    private function handleLanguage($language)
    {
        $supportedLanguages = ['en', 'tl'];
        $preferences = self::getPreferences();

        if (in_array($language, $supportedLanguages)) {
            $preferences['language'] = $language;
            $cookie = cookie('accessibility_preferences', json_encode($preferences), 60 * 24 * 30);

            // Set Laravel application locale
            app()->setLocale($language);

            return response()->json([
                'success' => true,
                'language' => $language,
                'message' => $language === 'tl' ? 'Ang wika ay na-update sa Tagalog' : 'Language updated to English'
            ])->withCookie($cookie);
        }

        return response()->json(['error' => 'Unsupported language'], 400);
    }

    /**
     * Translate text dynamically
     */
    public function translateText(Request $request)
    {
        $text = $request->input('text');
        $targetLang = $request->input('target_lang', 'tl');

        // Use Translation Service
        $translationService = new \App\Services\TranslationService();
        $translated = $translationService::translate($text, $targetLang);

        return response()->json([
            'success' => true,
            'original' => $text,
            'translated' => $translated,
            'target_lang' => $targetLang
        ]);
    }

    /**
     * Translate batch of texts
     */
    public function translateBatch(Request $request)
    {
        $texts = $request->input('texts', []);
        $targetLang = $request->input('target_lang', 'tl');

        // Use Translation Service
        $translationService = new \App\Services\TranslationService();
        $translated = $translationService::translateBatch($texts, $targetLang);

        return response()->json([
            'success' => true,
            'translations' => $translated,
            'target_lang' => $targetLang
        ]);
    }

    /**
     * Get current language
     */
    public static function getCurrentLanguage()
    {
        $preferences = self::getPreferences();
        return $preferences['language'] ?? 'en';
    }

    /**
     * Toggle between English and Tagalog
     */
    public function toggleLanguage()
    {
        $currentLanguage = self::getCurrentLanguage();
        $newLanguage = $currentLanguage === 'en' ? 'tl' : 'en';

        $preferences = self::getPreferences();
        $preferences['language'] = $newLanguage;

        $cookie = cookie('accessibility_preferences', json_encode($preferences), 60 * 24 * 30);

        // Set Laravel application locale
        app()->setLocale($newLanguage);

        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', $newLanguage === 'tl'
                ? 'Ang wika ay na-update sa Tagalog'
                : 'Language updated to English'
            );
    }
}
