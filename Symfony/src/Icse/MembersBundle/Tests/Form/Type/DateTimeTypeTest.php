<?php

namespace Icse\MembersBundle\Tests\Form\Type;

use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Icse\MembersBundle\Form\Type\DateTimeType;
use Icse\MembersBundle\Form\Type\TimeType;


class DateTimeTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $time_type = new TimeType();
        return [new PreloadedExtension([
            $time_type->getName() => $time_type,
        ], [])];
    }

    public function getTestData()
    {
        return [
            [
                [
                    'date' => '11/06/2010',
                    'time' => '3:30 pm',
                ],
                true,
                true,
                new \DateTime("11th June 2010 3:30 pm")
            ],
            [
                [
                    'date' => '11/06/2010',
                    'time' => '12:00 am',
                ],
                true,
                true,
                new \DateTime("11th June 2010 12:00:00 am")
            ],
            [
                [
                    'date' => '11/06/2010',
                    'time' => '',
                ],
                true,
                true,
                new \DateTime("11th June 2010 12:00:01 am")
            ],
            [
                [
                    'date' => '',
                    'time' => '3:30 pm',
                ],
                true,
                true,
                null
            ],
            [
                [
                    'date' => '',
                    'time' => '',
                ],
                true,
                true,
                null
            ],
            [
                [
                    'date' => '11/06/2010',
                    'time' => '3:30',
                ],
                false,
                true,
                null
            ],
        ];
    }

    /**
     * @dataProvider getTestData
     */
    public function testSubmitData($input_data, $should_sync, $should_be_valid, $expected_result)
    {
        $type = new DateTimeType();
        $form = $this->factory->create($type, null, [
            'date_widget' => 'single_text',
            'time_widget' => 'single_text',
            'date_format' => 'dd/MM/yy'
        ]);

        // submit the data to the form directly
        $form->submit($input_data);

        $this->assertEquals($form->isSynchronized(), $should_sync, "Form synchronisation is incorrect");
        $this->assertEquals($form->isValid(), $should_be_valid, "Form validity is incorrect");
        $this->assertEquals($form->getData(), $expected_result);
    }
}
