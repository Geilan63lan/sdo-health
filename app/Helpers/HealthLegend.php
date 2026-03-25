<?php

namespace App\Helpers;

class HealthLegend
{
    public static function getMap(): array
    {
        return [
            'ns_bmi' => [
                'a' => 'Normal Weight',
                'c' => 'Severely Wasted/Underweight',
                'd' => 'Overweight',
                'e' => 'Obese',
            ],
            'ns_height' => [
                'f' => 'Normal Height',
                'g' => 'Stunted',
                'h' => 'Severely Stunted',
                'i' => 'Tall',
            ],
            'screenings' => [
                'a' => 'Passed',
                'b' => 'Failed',
            ],
            'skin_scalp' => [
                'a' => 'Normal',
                'b' => 'Presence of Lice',
                'c' => 'Redness of Skin',
                'd' => 'White Spots',
                'e' => 'Flaky Skin',
                'f' => 'Impetigo/Boil',
                'g' => 'Hematoma',
                'h' => 'Bruises/Injuries',
                'i' => 'Itchiness',
                'j' => 'Skin Lesions',
                'k' => 'Acne/Pimple',
                'l' => 'Capillary refill > 3 sec',
                'm' => 'Others, specify',
            ],
            'eyes_ears_nose' => [
                'a' => 'Normal',
                'b' => 'Inflamed Eye Lid',
                'c' => 'Eye Redness',
                'd' => 'Ocular Misalignment',
                'e' => 'Pale Conjunctiva',
                'f' => 'Matted Eyelashes',
                'g' => 'Eye Discharge',
                'h' => 'Ear Discharge',
                'i' => 'Impacted Cerumen',
                'j' => 'Mucus Discharge',
                'k' => 'Nose Bleeding (Epistaxis)',
                'l' => 'Others, specify',
            ],
            'mouth_neck_throat' => [
                'a' => 'Normal',
                'b' => 'Enlarged Tonsils',
                'c' => 'Presence of Lesions',
                'd' => 'Inflamed Pharynx',
                'e' => 'Enlarged Lymphnodes',
                'f' => 'Others, specify',
            ],
            'lungs_heart' => [
                'a' => 'Normal',
                'b' => 'Rales',
                'c' => 'Wheeze',
                'd' => 'Murmur',
                'e' => 'Irregular Heart Rate',
                'f' => 'Colds',
                'g' => 'Cough',
                'h' => 'Others, specify',
            ],
            'abdomen' => [
                'a' => 'Normal',
                'b' => 'Distended',
                'c' => 'Abdominal Pain',
                'd' => 'Tenderness',
                'e' => 'Dysmenorrhea',
                'f' => 'Others, specify',
            ],
            'deformities' => [
                'a' => 'Acquired (Specify)',
                'b' => 'Congenital (Specify)',
            ],
        ];
    }

    /**
     * Helper to get a specific label by category and key
     */
    public static function label($category, $key): string
    {
        return self::getMap()[$category][$key] ?? $key ?? 'N/A';
    }
}
