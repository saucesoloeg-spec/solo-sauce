<?php

namespace Database\Seeders;

use App\Models\ImportFileType;
use App\Models\Rawdata;
use App\Models\RawdataInputAssistance;
use App\Models\RawdataName;
use App\Models\RawdataOptions;
use App\Models\RawdataPattern;
use App\Models\RawdataQuestionSet;
use App\Models\RawdataSet;
use Illuminate\Database\Seeder;

class SparkQuestionSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ja_name = 'SPARK テンプレート to be deleted';
        $en_name = 'SPARK Template to be deleted';

        ImportFileType::updateOrCreate([
            'name'    => $ja_name,
            'name_en' => $en_name,
        ], [
            'name'               => $ja_name,
            'name_en'            => $en_name,
            'sort'               => 0,
            'is_internal_format' => '1',
        ]);

        $question_set = [
            'referrer_id'             => config('constants.DTI_REFERRER_ID'),
            'sales_id'                => null,
            'display_name'            => $ja_name,
            'display_name_en'         => $en_name,
            'name'                    => 'dti_spark',
            'visibility'              => 1,
            'set_type'                => 2,
            'note'                    => null,
            'question_set_group'      => -5,
            'default_select'          => 1,
            'listed_flag'             => 0,
            'sdsc_flg'                => 0,
            'allow_evidence'          => 1,
            'display_order'           => 1,
            'button_type'             => 'radio',
            'data_entry_interface'    => 'DefaultDataEntry',
            'scoring_logic_interface' => 'NO_SCORE',
            'result_screen'           => null,
        ];

        $rawdata_q_set = RawdataQuestionSet::updateOrCreate([
            'name' => $question_set['name'],
        ], $question_set);

        $rawdata_pattern = RawdataPattern::updateOrCreate([
            'rawdata_question_set_id' => $rawdata_q_set->id,
            'pattern_id'              => $rawdata_q_set->id,
        ]);

        $slugify = function ($value): string {
            $value = mb_strtolower(trim((string) $value));
            $value = preg_replace('/[^a-z0-9]+/u', '_', $value);
            $value = trim($value, '_');

            return preg_replace('/_+/', '_', $value) ?: 'item';
        };

        $csvPath = database_path('data/dti/sedg_export_sample_with_data.csv');
        if (! file_exists($csvPath)) {
            throw new \RuntimeException('CSV file not found: ' . $csvPath);
        }

        $handle = fopen($csvPath, 'r');
        if ($handle === false) {
            throw new \RuntimeException('Unable to open CSV file: ' . $csvPath);
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            throw new \RuntimeException('CSV file is empty: ' . $csvPath);
        }

        $records = [];
        $usedSlugs = [];
        $priorityMap = [
            'BASIC'        => 1,
            'INTERMEDIATE' => 2,
            'ADVANCED'     => 3,
        ];
        $dataTypeMap = [
            'IndicatorText'   => 'text',
            'IndicatorNumber' => 'number',
            'IndicatorChoice' => 'switch',
            'IndicatorTable'  => 'text',
        ];

        while (($row = fgetcsv($handle)) !== false) {
            $record = array_combine($headers, array_pad($row, count($headers), ''));
            if ($record === false) {
                continue;
            }

            $largeKey = $slugify($record['large_key'] ?? '');
            $middleKey = $slugify($record['middle_key'] ?? '');
            $smallBase = $slugify($record['small_key'] ?? '');
            $smallKey = $smallBase;
            $counter = 2;
            while (isset($usedSlugs[$smallKey])) {
                $smallKey = $smallBase . '_' . $counter;
                $counter++;
            }
            $usedSlugs[$smallKey] = true;

            $priority = $priorityMap[strtoupper(trim((string) ($record['priority'] ?? '')))] ?? 1;
            $dataType = $dataTypeMap[$record['input_type'] ?? ''] ?? 'text';
            $unit = trim((string) ($record['unit'] ?? ''));
            $value = trim((string) ($record['value'] ?? ''));
            $placeholder = $value !== '' && $value !== 'N/A' ? $value : null;

            $records[] = [
                'large_key'      => $largeKey,
                'large_name'     => (string) ($record['large_key'] ?? ''),
                'middle_key'     => $middleKey,
                'middle_name'    => (string) ($record['middle_key'] ?? ''),
                'small_key'      => $smallKey,
                'name'           => (string) ($record['small_key'] ?? ''),
                'name_en'        => (string) ($record['small_key'] ?? ''),
                'explanation'    => (string) ($record['small_key'] ?? ''),
                'explanation_en' => (string) ($record['small_key'] ?? ''),
                'priority'       => $priority,
                'data_type'      => $dataType,
                'unit'           => $unit,
                'placeholder'    => $placeholder,
            ];
        }

        fclose($handle);

        $createdNames = [];
        foreach ($records as $record) {
            foreach ([
                ['key' => $record['large_key'], 'kbn' => 1, 'name' => $record['large_name']],
                ['key' => $record['middle_key'], 'kbn' => 2, 'name' => $record['middle_name']],
            ] as $category) {
                $cacheKey = $category['kbn'] . ':' . $category['key'];
                if (! isset($createdNames[$cacheKey])) {
                    RawdataName::updateOrCreate([
                        'key' => $category['key'],
                        'kbn' => $category['kbn'],
                    ], [
                        'name'    => $category['name'],
                        'name_en' => $category['name'],
                    ]);
                    $createdNames[$cacheKey] = true;
                }
            }
        }

        RawdataSet::whereIn('pattern_id', [$rawdata_pattern->pattern_id])->delete();

        foreach ($records as $index => $record) {
            $rawdata = Rawdata::where([
                'large_key'  => $record['large_key'],
                'middle_key' => $record['middle_key'],
                'small_key'  => $record['small_key'],
            ])->first();

            if (! $rawdata) {
                $rawdata = new Rawdata();
                $rawdata->large_key = $record['large_key'];
                $rawdata->middle_key = $record['middle_key'];
                $rawdata->small_key = $record['small_key'];
            }

            $rawdata->forceFill([
                'priority'    => $record['priority'],
                'calc_flg'    => 0,
                'data_type'   => $record['data_type'],
                'unit'        => $record['unit'],
                'unit_en'     => $record['unit'],
                'placeholder' => $record['placeholder'],
            ])->save();

            RawdataName::updateOrCreate([
                'key' => $rawdata->small_key,
                'kbn' => 3,
            ], [
                'name'    => $record['name'],
                'name_en' => $record['name_en'],
            ]);

            RawdataInputAssistance::updateOrCreate([
                'rawdata_id' => $rawdata->id,
            ], [
                'assistance_type'     => 1,
                'explanation_text'    => $record['explanation'],
                'explanation_text_en' => $record['explanation_en'],
            ]);

            if ($rawdata->data_type === 'switch') {
                RawdataOptions::updateOrCreate([
                    'rawdata_id'  => $rawdata->id,
                    'option_text' => '無',
                ], [
                    'rawdata_id'     => $rawdata->id,
                    'option_text'    => '無',
                    'option_text_en' => 'No',
                    'option_value'   => 0,
                    'sort'           => 1,
                ]);

                RawdataOptions::updateOrCreate([
                    'rawdata_id'  => $rawdata->id,
                    'option_text' => '有',
                ], [
                    'rawdata_id'     => $rawdata->id,
                    'option_text'    => '有',
                    'option_text_en' => 'Yes',
                    'option_value'   => 1,
                    'sort'           => 2,
                ]);
            }

            RawdataSet::insert([
                'pattern_id' => $rawdata_pattern->pattern_id,
                'rawdata_id' => $rawdata->id,
                'required'   => 0,
                'sort'       => $index,
            ]);
        }
    }
}
