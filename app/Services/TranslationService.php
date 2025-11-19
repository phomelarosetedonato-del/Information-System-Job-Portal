<?php

namespace App\Services;

class TranslationService
{
    /**
     * Comprehensive English to Tagalog translations
     */
    private static $translations = [
        // Navigation
        'Home' => 'Bahay',
        'About' => 'Tungkol sa Amin',
        'About Us' => 'Tungkol sa Amin',
        'Contact' => 'Makipag-ugnayan',
        'Contact Us' => 'Makipag-ugnayan sa Amin',
        'Jobs' => 'Mga Trabaho',
        'Training' => 'Pagsasanay',
        'Trainings' => 'Mga Pagsasanay',
        'Employers' => 'Mga Employer',
        'Success Stories' => 'Mga Kwento ng Tagumpay',
        'Login' => 'Mag-login',
        'Register' => 'Magrehistro',
        'Logout' => 'Mag-logout',
        'Dashboard' => 'Dashboard',
        'Profile' => 'Profile',
        'My Profile' => 'Aking Profile',
        'Settings' => 'Mga Setting',
        'Notifications' => 'Mga Notipikasyon',
        'Messages' => 'Mga Mensahe',
        'Help' => 'Tulong',
        'Support' => 'Suporta',

        // Lowercase navigation variants
        'home' => 'Bahay',
        'about_us' => 'Tungkol sa Amin',
        'contact_us' => 'Makipag-ugnayan sa Amin',
        'login' => 'Mag-login',
        'register' => 'Magrehistro',
        'logout' => 'Mag-logout',

        // Homepage Hero Section
        'your_abilities' => 'Ang Iyong Kakayahan',
        'our_priority' => 'Ang Aming Prayoridad',
        'hero_description' => 'Kumonekta sa mga inclusive na employer na pinahahalagahan ang iyong natatanging talento. Makakapag-access ng espesyalisadong pagsasanay at makakahanap ng makabuluhang oportunidad sa trabaho na dinisenyo para sa mga Persons with Disabilities.',

        // Homepage Features
        'empowering_pwd' => 'Nagbibigay ng Kapangyarihan sa mga Propesyonal na PWD',
        'fully_accessible' => 'Ganap na Accessible na Platform',
        'screen_reader_compatible' => 'Compatible sa screen reader, keyboard navigation',
        'pwd_friendly_employers' => 'PWD-Friendly na mga Employer',
        'prevetted_companies' => 'Pre-vetted na inclusive companies',
        'free_skills_training' => 'Libreng Pagsasanay sa Kasanayan',
        'enhance_employability' => 'Pahusayin ang iyong employability',

        // Homepage CTA Buttons
        'start_journey' => 'Simulan ang Iyong Paglalakbay',
        'view_jobs' => 'Tingnan ang mga Trabaho',

        // Homepage Trust Indicators
        'free_100' => '100% Libre',
        'for_job_seekers' => 'para sa mga Naghahanap ng Trabaho',
        'data_protected' => 'Protektado ang Data',
        'and_secure' => '& Secure',
        'success_count' => '1000+',
        'success_stories' => 'Mga Kwento ng Tagumpay',

        // Homepage Floating Badges
        'verified_employers' => 'Verified na mga Employer',
        'government_recognized' => 'Kinikilala ng Gobyerno',

        // Accessibility Features Banner
        'accessibility_first' => 'Accessibility Una',
        'built_with_pwd_needs' => 'Ginawa para sa mga pangangailangan ng PWD',
        'keyboard_navigation' => 'Keyboard Navigation',
        'screen_reader' => 'Screen Reader',
        'adjustable_text' => 'Nababagong Teksto',
        'high_contrast' => 'Mataas na Contrast',

        // Statistics/Counters
        'active_jobs' => 'Aktibong mga Trabaho',
        'training_programs' => 'Mga Programang Pagsasanay',

        // Common Actions
        'Save' => 'I-save',
        'Cancel' => 'Kanselahin',
        'Edit' => 'I-edit',
        'Delete' => 'Tanggalin',
        'Create' => 'Gumawa',
        'View' => 'Tingnan',
        'Search' => 'Maghanap',
        'Filter' => 'Salain',
        'Actions' => 'Mga Aksyon',
        'Submit' => 'Ipasa',
        'Back' => 'Bumalik',
        'Next' => 'Susunod',
        'Previous' => 'Nakaraan',
        'Close' => 'Isara',
        'Apply' => 'Mag-apply',
        'Download' => 'I-download',
        'Upload' => 'Mag-upload',
        'Browse' => 'Mag-browse',
        'Send' => 'Ipadala',
        'Send Message' => 'Ipadala ang Mensahe',

        // Accessibility
        'Accessibility' => 'Accessibility',
        'accessibility' => 'Accessibility',
        'Accessibility Options' => 'Mga Opsyon sa Accessibility',
        'Customize your viewing experience' => 'Ipasadya ang iyong karanasan sa pagtingin',
        'customize_experience' => 'Ipasadya ang iyong karanasan sa pagtingin',
        'Language' => 'Wika',
        'language' => 'Wika',
        'english' => 'English',
        'tagalog' => 'Tagalog',
        'Text Size' => 'Laki ng Teksto',
        'text_size' => 'Laki ng Teksto',
        'Color & Contrast' => 'Kulay at Kontrast',
        'contrast' => 'Kulay at Kontrast',
        'Quick Presets' => 'Mabilisang Preset',
        'quick_presets' => 'Mabilisang Preset',
        'Additional Features' => 'Karagdagang Mga Tampok',
        'additional' => 'Karagdagang Mga Tampok',
        'Quick Actions' => 'Mabilisang Mga Aksyon',
        'quick_actions' => 'Mabilisang Mga Aksyon',

        // Sizes
        'Small' => 'Maliit',
        'small' => 'Maliit',
        'Medium' => 'Katamtaman',
        'medium' => 'Katamtaman',
        'Large' => 'Malaki',
        'large' => 'Malaki',
        'X-Large' => 'Napakalaki',
        'xlarge' => 'Napakalaki',

        // Contrast
        'Normal' => 'Normal',
        'normal' => 'Normal',
        'High' => 'Mataas',
        'high' => 'Mataas',
        'Very High' => 'Napakataas',
        'very_high' => 'Napakataas',

        // Presets
        'Low Vision' => 'Mahinang Paningin',
        'low_vision' => 'Mahinang Paningin',
        'Dyslexia Friendly' => 'Angkop para sa Dyslexia',
        'dyslexia' => 'Angkop para sa Dyslexia',
        'Motor Assistance' => 'Tulong sa Motor',
        'motor' => 'Tulong sa Motor',
        'Larger text, high contrast' => 'Mas malaking teksto, mataas na kontrast',
        'low_vision_desc' => 'Mas malaking teksto, mataas na kontrast',
        'OpenDyslexic font, spacing' => 'OpenDyslexic na font, spacing',
        'dyslexia_desc' => 'OpenDyslexic na font, spacing',
        'Large buttons, keyboard nav' => 'Malalaking butones, keyboard navigation',
        'motor_desc' => 'Malalaking butones, keyboard navigation',

        // Features
        'Reduce Animations' => 'Bawasan ang mga Animasyon',
        'reduce_animations' => 'Bawasan ang mga Animasyon',
        'Highlight Focus' => 'I-highlight ang Focus',
        'highlight_focus' => 'I-highlight ang Focus',
        'Simplify Layout' => 'Pasimplehin ang Layout',
        'simplify_layout' => 'Pasimplehin ang Layout',
        'Read Aloud' => 'Basahin nang Malakas',
        'read_aloud' => 'Basahin',
        'Reset All' => 'I-reset ang Lahat',
        'reset_all' => 'I-reset',
        'Print Page' => 'I-print ang Pahina',
        'print' => 'I-print',
        'Shortcuts' => 'Mga Shortcut',
        'shortcuts' => 'Shortcuts',
        'Keyboard Shortcuts' => 'Mga Keyboard Shortcut',

        // Messages
        'Success!' => 'Tagumpay!',
        'Error!' => 'May Mali!',
        'Warning!' => 'Babala!',
        'Information' => 'Impormasyon',
        'Loading...' => 'Naglo-load...',
        'Please wait...' => 'Pakihintay...',
        'No results found.' => 'Walang nahanap na resulta.',

        // Forms
        'Email' => 'Email',
        'Password' => 'Password',
        'Confirm Password' => 'Kumpirmahin ang Password',
        'Name' => 'Pangalan',
        'Full Name' => 'Buong Pangalan',
        'Phone Number' => 'Numero ng Telepono',
        'Address' => 'Address',
        'Description' => 'Deskripsyon',
        'Message' => 'Mensahe',

        // Page Titles
        'Welcome to PWD Job Portal' => 'Maligayang pagdating sa Portal ng Trabaho para sa PWD',
        'Your Abilities, Our Priority' => 'Ang Iyong Kakayahan, Aming Prioridad',
        'Start Your Journey' => 'Simulan ang Iyong Paglalakbay',
        'View Jobs' => 'Tingnan ang mga Trabaho',
        'View All Jobs' => 'Tingnan ang Lahat ng Trabaho',

        // Job Portal
        'Job Opportunities' => 'Mga Oportunidad sa Trabaho',
        'Training Programs' => 'Mga Programa ng Pagsasanay',
        'Partner Companies' => 'Mga Kumpanyang Kasosyo',
        'Apply Now' => 'Mag-apply Ngayon',
        'Learn More' => 'Alamin pa',
        'Job Applications' => 'Mga Aplikasyon sa Trabaho',
        'Applications' => 'Mga Aplikasyon',
        'My Applications' => 'Aking mga Aplikasyon',
        'Job Postings' => 'Mga Trabahong Naka-post',
        'Available Jobs' => 'Mga Magagamit na Trabaho',
        'View All' => 'Tingnan Lahat',
        'View Details' => 'Tingnan ang mga Detalye',
        'Application Status' => 'Kalagayan ng Aplikasyon',
        'Enrolled' => 'Naka-enroll',
        'Enrollments' => 'Mga Enrollment',
        'My Enrollments' => 'Aking mga Enrollment',
        'Documents' => 'Mga Dokumento',
        'My Documents' => 'Aking mga Dokumento',
        'Upload Document' => 'Mag-upload ng Dokumento',
        'Job Title' => 'Pamagat ng Trabaho',
        'Company' => 'Kumpanya',
        'Location' => 'Lokasyon',
        'Salary' => 'Sahod',
        'Salary Range' => 'Hanay ng Sahod',
        'Requirements' => 'Mga Kinakailangan',
        'Qualifications' => 'Mga Kwalipikasyon',
        'Deadline' => 'Deadline',
        'Posted' => 'Naka-post',
        'Posted on' => 'Naka-post noong',

        // Contact
        'Get In Touch' => 'Makipag-ugnayan',
        'Contact Our Support Team' => 'Makipag-ugnayan sa Aming Koponan ng Suporta',
        'Send us a message' => 'Magpadala sa amin ng mensahe',
        'Our Office' => 'Aming Opisina',
        'Phone' => 'Telepono',
        'Email Address' => 'Email Address',
        'Office Hours' => 'Oras ng Opisina',

        // About
        'Empowering PWD Professionals' => 'Pagbibigay Kapangyarihan sa mga Propesyonal na PWD',
        'Our Mission' => 'Aming Misyon',
        'Our Vision' => 'Aming Vision',
        'What We Do' => 'Ano ang Aming Ginagawa',
        'Our Values' => 'Aming mga Halaga',

        // Statistics
        'Jobs Posted' => 'Mga Trabahong Na-post',

        // Status
        'Active' => 'Aktibo',
        'Inactive' => 'Hindi Aktibo',
        'Pending' => 'Naghihintay',
        'Approved' => 'Aprubado',
        'Rejected' => 'Tinanggihan',

        // Time
        'Today' => 'Ngayon',
        'Yesterday' => 'Kahapon',
        'This Week' => 'Ngayong Linggo',
        'This Month' => 'Ngayong Buwan',
        'Last Month' => 'Nakaraang Buwan',

        // Language specific
        'English' => 'English',
        'Tagalog' => 'Tagalog',
        'Current language: English' => 'Current language: English',
        'Kasalukuyang wika: Tagalog' => 'Kasalukuyang wika: Tagalog',

        // Dashboard specific
        'Welcome back' => 'Maligayang pagbabalik',
        'Overview' => 'Pangkalahatang-ideya',
        'Statistics' => 'Istatistika',
        'Recent Activity' => 'Kamakailang Aktibidad',
        'Quick Stats' => 'Mabilisang Istatistika',
        'Total' => 'Kabuuan',
        'New' => 'Bago',
        'Available Opportunities' => 'Mga Magagamit na Oportunidad',
        'Explore Training' => 'Tuklasin ang Pagsasanay',
        'Complete Profile' => 'Kumpletuhin ang Profile',

        // Common words
        'All' => 'Lahat',
        'None' => 'Wala',
        'Yes' => 'Oo',
        'No' => 'Hindi',
        'Welcome' => 'Maligayang pagdating',
        'Congratulations' => 'Binabati kita',
        'Thank you' => 'Salamat',
        'Please' => 'Pakiusap',
        'Sorry' => 'Paumanhin',
        'Confirm' => 'Kumpirmahin',
        'Continue' => 'Magpatuloy',
        'Finish' => 'Tapusin',
        'Start' => 'Simulan',
        'Stop' => 'Ihinto',
        'Pause' => 'I-pause',
        'Resume' => 'Ipagpatuloy',
        'Refresh' => 'I-refresh',
        'Update' => 'I-update',
        'Updating' => 'Nag-u-update',
        'Remove' => 'Alisin',
        'Add' => 'Idagdag',
        'Clear' => 'I-clear',
        'Reset' => 'I-reset',
        'Restore' => 'Ibalik',
        'Export' => 'I-export',
        'Import' => 'Mag-import',
        'Print' => 'I-print',
        'Share' => 'Ibahagi',
        'Copy' => 'Kopyahin',
        'Cut' => 'I-cut',
        'Paste' => 'I-paste',
        'Undo' => 'Ibalik',
        'Redo' => 'Ulitin',
        'Select' => 'Pumili',
        'Select All' => 'Piliin Lahat',
        'Deselect' => 'Alisin ang Pagpili',

        // Status messages
        'Successfully saved' => 'Matagumpay na nai-save',
        'Successfully updated' => 'Matagumpay na na-update',
        'Successfully deleted' => 'Matagumpay na natanggal',
        'Successfully created' => 'Matagumpay na nagawa',
        'Successfully submitted' => 'Matagumpay na naipasa',
        'Operation successful' => 'Matagumpay ang operasyon',
        'Operation failed' => 'Nabigo ang operasyon',
        'Are you sure?' => 'Sigurado ka ba?',
        'This action cannot be undone' => 'Hindi na maaaring bawiin ang aksyong ito',

        // User related
        'User' => 'User',
        'Users' => 'Mga User',
        'Account' => 'Account',
        'My Account' => 'Aking Account',
        'Personal Information' => 'Personal na Impormasyon',
        'First Name' => 'Pangalan',
        'Last Name' => 'Apelyido',
        'Middle Name' => 'Gitnang Pangalan',
        'Date of Birth' => 'Petsa ng Kapanganakan',
        'Gender' => 'Kasarian',
        'Male' => 'Lalaki',
        'Female' => 'Babae',
        'Other' => 'Iba pa',
        'Prefer not to say' => 'Ayaw sabihin',

        // Accessibility widget specific
        'customize your viewing experience' => 'ipasadya ang iyong karanasan sa pagtingin',
        'Mga Opsyon sa Accessibility' => 'Mga Opsyon sa Accessibility',
        'Ipasadya ang iyong karanasan sa pagtingin' => 'Ipasadya ang iyong karanasan sa pagtingin',

        // Notifications
        'You have' => 'Mayroon kang',
        'notification' => 'notipikasyon',
        'notifications' => 'mga notipikasyon',
        'No new notifications' => 'Walang bagong notipikasyon',
        'Mark as read' => 'Markahan bilang nabasa',
        'Mark all as read' => 'Markahan lahat bilang nabasa',
        'Read more' => 'Magbasa pa',

        // Date and time
        'Date' => 'Petsa',
        'Time' => 'Oras',
        'From' => 'Mula',
        'To' => 'Hanggang',
        'Duration' => 'Tagal',
        'Start Date' => 'Petsa ng Pagsisimula',
        'End Date' => 'Petsa ng Pagtatapos',
        'Created at' => 'Nilikha noong',
        'Updated at' => 'Na-update noong',

        // Additional common phrases
        'Click here' => 'Mag-click dito',
        'More information' => 'Higit pang impormasyon',
        'Show more' => 'Ipakita ang higit pa',
        'Show less' => 'Ipakita ang mas kaunti',
        'Expand' => 'Palawakin',
        'Collapse' => 'Paliitin',
        'Details' => 'Mga Detalye',
        'Summary' => 'Buod',
        'Options' => 'Mga Opsyon',
        'Preferences' => 'Mga Kagustuhan',
        'Configure' => 'I-configure',
        'Manage' => 'Pamahalaan',
        'Administration' => 'Pamamahala',
        'Tools' => 'Mga Tool',
        'Resources' => 'Mga Mapagkukunan',
        'Category' => 'Kategorya',
        'Categories' => 'Mga Kategorya',
        'Tag' => 'Tag',
        'Tags' => 'Mga Tag',
        'Type' => 'Uri',
        'Types' => 'Mga Uri',
        'Level' => 'Antas',
        'Priority' => 'Prioridad',
        'Importance' => 'Kahalagahan',
        'Required' => 'Kinakailangan',
        'Optional' => 'Opsyonal',
        'Recommended' => 'Inirerekomenda',
    ];

    /**
     * Translate text from English to Tagalog
     */
    public static function translate(string $text, string $targetLang = 'tl'): string
    {
        if ($targetLang === 'en') {
            return $text;
        }

        // Try exact match first
        if (isset(self::$translations[$text])) {
            return self::$translations[$text];
        }

        // Try case-insensitive match
        foreach (self::$translations as $en => $tl) {
            if (strcasecmp($en, $text) === 0) {
                return $tl;
            }
        }

        // Return original text if no translation found
        return $text;
    }

    /**
     * Translate multiple texts
     */
    public static function translateBatch(array $texts, string $targetLang = 'tl'): array
    {
        $translated = [];
        foreach ($texts as $key => $text) {
            $translated[$key] = self::translate($text, $targetLang);
        }
        return $translated;
    }

    /**
     * Check if translation exists
     */
    public static function hasTranslation(string $text): bool
    {
        return isset(self::$translations[$text]);
    }

    /**
     * Get all translations
     */
    public static function getAllTranslations(): array
    {
        return self::$translations;
    }

    /**
     * Add new translation
     */
    public static function addTranslation(string $english, string $tagalog): void
    {
        self::$translations[$english] = $tagalog;
    }
}
