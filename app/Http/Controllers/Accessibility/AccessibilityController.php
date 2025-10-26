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
        return view('accessibility.settings');
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
        ];

        $cookie = cookie('accessibility_preferences', json_encode($preferences), 60 * 24 * 30); // 30 days

        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', 'Accessibility preferences updated successfully!');
    }

    /**
     * Reset accessibility preferences
     */
    public function resetPreferences()
    {
        $cookie = Cookie::forget('accessibility_preferences');

        return redirect()->back()
            ->withCookie($cookie)
            ->with('success', 'Accessibility preferences reset to default.');
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

        return implode(' ', $classes);
    }

    /**
     * Quick accessibility tools
     */
    public function quickTool(Request $request)
    {
        $tool = $request->input('tool');
        $action = $request->input('action');

        switch ($tool) {
            case 'font_size':
                return $this->handleFontSize($action);
            case 'contrast':
                return $this->handleContrast($action);
            case 'read_aloud':
                return $this->handleReadAloud($request);
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

        return response()->json(['contrast' => $newContrast])->withCookie($cookie);
    }

    private function handleReadAloud(Request $request)
    {
        $text = $request->input('text', '');
        // This would integrate with a text-to-speech service
        return response()->json(['status' => 'success', 'text' => $text]);
    }
}
