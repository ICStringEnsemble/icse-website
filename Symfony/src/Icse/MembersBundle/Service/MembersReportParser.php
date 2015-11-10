<?php

namespace Icse\MembersBundle\Service;


use Common\Tools;
use Icse\MembersBundle\Entity\Member;

class MembersReportParser
{
    private function heading_to_indices($csv_row, $line_n)
    {
        $indices = [];
        $headings = array_flip($csv_row);
        try {
            $indices['date'] = Tools::arrayGet($headings, 'Date');
            $indices['login'] = Tools::arrayGet($headings, 'Login');
            $indices['first_name'] = Tools::arrayGet($headings, 'First Name');
            $indices['last_name'] = Tools::arrayGet($headings, 'Last Name');
            $indices['email'] = Tools::arrayGet($headings, 'Email');
        } catch (\UnexpectedValueException $e) {
            throw new \Exception("Line $line_n: CSV headings not as expected.");
        }
        return $indices;
    }

    private function parseDate($date_str)
    {
        if (strlen($date_str) != 10) throw new \UnexpectedValueException("Unexpected date format " . strlen($date_str));
        return \DateTime::createFromFormat('!d/m/Y', $date_str);
    }

    /**
     * @param $file_path
     * @return \Iterator
     * @throws \Exception
     */
    public function generateMembersFromCSV($file_path)
    {
        $file = fopen($file_path, 'r');
        $expect_heading = true;
        $indices = [];

        for ($csv_row = fgetcsv($file), $line_n = 1; $csv_row ; $csv_row = fgetcsv($file), $line_n++)
        {
            if (count($csv_row) <= 1 || !$csv_row[1])
            {
                $expect_heading = true;
                continue;
            }

            if ($expect_heading)
            {
                $indices = $this->heading_to_indices($csv_row, $line_n);
                $expect_heading = false;
            }
            else
            {
                $member = new Member();

                try
                {
                    $member->setFirstName($csv_row[$indices['first_name']]);
                    $member->setLastName($csv_row[$indices['last_name']]);
                    $member->setUsername($csv_row[$indices['login']]);
                    $member->setEmail($csv_row[$indices['email']]);
                    $member->setPassword(null);
                    $member->setSalt(null);
                    $member->setPasswordOperation(Member::PASSWORD_IMPERIAL);
                    $member->setLastPaidMembershipOn($this->parseDate($csv_row[$indices['date']]));
                    $member->setActive(true);
                }
                catch (\UnexpectedValueException $e)
                {
                    throw new \Exception("Error at line $line_n: " . $e->getMessage());
                }

                yield $member;
            }
        }

        if ($line_n <= 2) throw new \Exception("Nothing in file");
    }

} 