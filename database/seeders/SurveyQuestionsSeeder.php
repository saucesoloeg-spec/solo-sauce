<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;

class SurveyQuestionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $questions = [
            [
                'question_en' => 'How satisfied are you with the continuous availability of our products?',
                'question_ar' => "ما مدى رضاك عن توافر منتجاتنا بشكل مستمر؟",
                'type'        => 'rate',
                'options'    => json_encode([
                    "en" => [
                        "0" => "Very Satisfied",
                        "1" => "Satisfied",
                        "2" => "Neutral",
                        "3" => "Unsatisfied",
                        "4" => "Very Unsatisfied"
                    ],
                    "ar" => [
                        "0" => "راضٍ جداً",
                        "1" => "راضٍ",
                        "2" => "محايد",
                        "3" => "غير راضٍ",
                        "4" => "غير راضٍ تماماً"
                    ]
                ]),
                'order'       => 1,
                'is_required' => true,
            ],
            [
                'question_en' => 'How effective was the product usage guidance provided to your barista/team?',
                'question_ar' => "ما مدى فعالية الإرشادات المقدمة للبارستا/فريق العمل بشأن طريقة استخدام المنتج؟",
                'type'        => 'radio',
                'options'     => json_encode([
                    "en" => [
                        "0" => "Very Clear and Helpful",
                        "1" => "Clear",
                        "2" => "Somewhat Clear",
                        "3" => "Not Clear",
                        "4" => "No Guidance Provided"
                    ],
                    "ar" => [
                        "0" => "واضحة ومفيدة جداً",
                        "1" => "واضحة",
                        "2" => "واضحة إلى حد ما",
                        "3" => "غير واضحة",
                        "4" => "لم يتم تقديم إرشادات"
                    ]
                ]),
                'order'       => 2,
                'is_required' => true,
            ],
            [
                'question_en' => 'How satisfied are you with the product packaging (design, durability, ease of use)?',
                'question_ar' => "ما مدى رضاك عن عبوة المنتج (التصميم، المتانة، سهولة الاستخدام)؟",
                'type'        => 'radio',
                'options'     => json_encode([
                    "en" => [
                        "0" => "Very Satisfied",
                        "1" => "Satisfied",
                        "2" => "Neutral",
                        "3" => "Unsatisfied",
                        "4" => "Very Unsatisfied"
                    ],
                    "ar" => [
                        "0" => "راضٍ جداً",
                        "1" => "راضٍ",
                        "2" => "محايد",
                        "3" => "غير راضٍ",
                        "4" => "غير راضٍ تماماً"
                    ]
                ]),
                'order'       => 3,
                'is_required' => true,
            ],
            [
                'question_en' => "Based on your customers’ feedback, how do they rate the quality of our sauces?",
                'question_ar' => "بناءً على ملاحظات عملائك، كيف يقيمون جودة صلصاتنا؟",
                'type'        => 'rate',
                'options'    => json_encode([
                    "en" => [
                        "0" => "Excellent Feedback",
                        "1" => "Mostly Positive",
                        "2" => "Neutral Feedback",
                        "3" => "Some Complaints",
                        "4" => "Frequent Complaints"
                    ],
                    "ar" => [
                        "0" => "ملاحظات ممتازة",
                        "1" => "معظمها إيجابية",
                        "2" => "ملاحظات محايدة",
                        "3" => "بعض الشكاوى",
                        "4" => "شكاوى متكررة"
                    ]
                ]),
                'order'       => 4,
                'is_required' => true,
            ],
            [
                'question_en' => "Which suppliers do you currently deal with for sauces or similar products?",
                'question_ar' => "ما هم الموردين الذين تتعامل معهم حالياً للحصول على الصلصات أو المنتجات المشابهة؟",
                'type'        => 'text',
                'options'     => null,
                'order'       => 5,
                'is_required' => false,
            ],
            [
                'question_en' => "How satisfied are you with your current supplier in terms of product quality and availability?",
                'question_ar' => "ما مدى رضاك عن المورد الحالي من حيث جودة المنتج وتوافره؟",
                'type'        => 'rate',
                'options'     => json_encode([
                    "en" => [
                        "0" => "Very Satisfied",
                        "1" => "Satisfied",
                        "2" => "Neutral",
                        "3" => "Unsatisfied",
                        "4" => "Very Unsatisfied"
                    ],
                    "ar" => [
                        "0" => "راضٍ جداً",
                        "1" => "راضٍ",
                        "2" => "محايد",
                        "3" => "غير راضٍ",
                        "4" => "غير راضٍ تماماً"
                    ],
                ]),
                'order'       => 6,
                'is_required' => true,
            ]
        ];

        foreach ($questions as $question) {
            Survey::create($question);
        }
    }
}
