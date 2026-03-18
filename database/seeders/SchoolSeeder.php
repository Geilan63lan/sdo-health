<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            ['school_id' => '114457', 'name' => 'Albay CS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114435', 'name' => 'Arimbay ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '310201', 'name' => 'Arimbay High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502955', 'name' => 'Bagacay Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114435', 'name' => 'Bagong Abre ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114466', 'name' => 'Bagumbayan CS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '306323', 'name' => 'Bagumbayan-East Washington National High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114467', 'name' => 'Banquerohan ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302257', 'name' => 'Banquerohan Natl High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '100182', 'name' => 'Banquerohan Resettlement ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502625', 'name' => 'Bariis Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502954', 'name' => 'Bigaa Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114475', 'name' => 'Bitano ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114453', 'name' => 'Bogna ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502952', 'name' => 'Bogtong Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114463', 'name' => 'Buenavista ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114476', 'name' => 'Buraguis ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114433', 'name' => 'Buyoan ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '306332', 'name' => 'Buyoan National High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114477', 'name' => 'Cabagnan ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302260', 'name' => 'Cabanagan High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114470', 'name' => 'Cagbacong ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '310206', 'name' => 'Cagbacong High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114478', 'name' => 'Dagdap ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114439', 'name' => 'Dita ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '175001', 'name' => 'Division SPED Center', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114471', 'name' => 'Ems Barrio PS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502950', 'name' => 'Estanza Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114490', 'name' => 'Gogon CS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302262', 'name' => 'Gogon High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114460', 'name' => 'Homapon ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302259', 'name' => 'Homapon High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114479', 'name' => 'Ibalon CS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114461', 'name' => 'Imalined ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114420', 'name' => 'Lamba ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302261', 'name' => 'Legazpi City National HS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '310202', 'name' => 'Legazpi City Science High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114481', 'name' => 'Legazpi Port ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114462', 'name' => 'Mabinit ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114463', 'name' => 'Mariawa ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114482', 'name' => 'Maslog ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '310205', 'name' => 'Maslog High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114491', 'name' => 'Matanag ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302258', 'name' => 'Oro Site High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114492', 'name' => 'Padang ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114464', 'name' => 'Pawa ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '302263', 'name' => 'Pawa High School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502949', 'name' => 'Puro Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502951', 'name' => 'Rawis Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '502624', 'name' => 'San Francisco Integrated School', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114484', 'name' => 'San Joaquin ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114485', 'name' => 'San Roque ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '310203', 'name' => 'SPED Center', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '175003', 'name' => 'SPED Center-Banquerohan Extn.', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114486', 'name' => 'Tamaayan ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114474', 'name' => 'Taysain ES', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '500169', 'name' => 'Taysain Resettlement IS', 'address' => 'Legazpi City, Albay'],
            ['school_id' => '114424', 'name' => 'Victory Village ES', 'address' => 'Legazpi City, Albay'],
        ];

        foreach ($schools as $school) {
            // Determine level based on school name
            $level = 'elementary'; // default
            $name = strtoupper($school['name']);
            
            if (strpos($name, 'HIGH SCHOOL') !== false || strpos($name, ' HS') !== false) {
                $level = 'shs';
            } elseif (strpos($name, 'INTEGRATED') !== false || strpos($name, ' IS') !== false) {
                $level = 'integrated';
            } elseif (preg_match('/\sJHS\s|\sJunior/', $name)) {
                $level = 'jhs';
            }

            School::updateOrCreate(
                ['school_id' => $school['school_id']],
                ['name' => $school['name'], 'address' => $school['address'], 'level' => $level]
            );
        }
    }
}
