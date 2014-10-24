<?php

namespace Icse\MembersBundle\Tests\Service;

use Icse\MembersBundle\Entity\Member;
use Icse\MembersBundle\Service\MembersReportParser;
use org\bovigo\vfs\vfsStream,
    org\bovigo\vfs\vfsStreamDirectory;

class MembersReportParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var  vfsStreamDirectory
     */
    private static $root;

    private static $data = [
        'bad' => [
            'empty' =>
'',
            'missing_heading' =>
'Full Members,,,,
"Date","Order No","CID","Login","First Name","Last Name","not_email"
"04/08/2014","1234","00123456","aa10","A","Ay","a.ay@imperial.ac.uk"
',
        ],
        'good' => [
            '0' =>
'"Date","Order No","CID","Login","First Name","Last Name","Email"
"04/08/2014","1234","00123456","aa10","A","Ay","a.ay@imperial.ac.uk"
"05/09/2014","1235","00123457","bb10","B","Bee","b.bee@imperial.ac.uk"
"07/02/2010","1236","00123458","cc10","C","Cee","c.cee@imperial.ac.uk"
',
            '1' =>
'Full Members
"Date","Order No","CID","Login","First Name","Last Name","Email"
"04/08/2014","1234","00123456","aa10","A","Ay","a.ay@imperial.ac.uk"
"05/09/2014","1235","00123457","bb10","B","Bee","b.bee@imperial.ac.uk"

Life / Associate
"Date","Order No","CID/Card Number","Login","First Name","Last Name","Email"
"07/02/2010","1236","00123458","cc10","C","Cee","c.cee@imperial.ac.uk"
',
            '2' =>
'Full Members,,,,
"Date","Order No","CID","Login","First Name","Last Name","Email"
"04/08/2014","1234","00123456","aa10","A","Ay","a.ay@imperial.ac.uk"
"05/09/2014","1235","00123457","bb10","B","Bee","b.bee@imperial.ac.uk"
,,,,
Life / Associate,,,,
"Date","Order No","CID/Card Number","Login","First Name","Last Name","Email"
"07/02/2010","1236","00123458","cc10","C","Cee","c.cee@imperial.ac.uk"
',
        ]
    ];

    private static $expected_data = [
        ['aa10', 'A', 'Ay', 'a.ay@imperial.ac.uk', '2014-08-04'],
        ['bb10', 'B', 'Bee', 'b.bee@imperial.ac.uk', '2014-09-05'],
        ['cc10', 'C', 'Cee', 'c.cee@imperial.ac.uk', '2010-02-07'],
    ];


    private static $expected_members = [];

    public static function setupBeforeClass()
    {
        foreach (self::$expected_data as $d)
        {
            $m = new Member;
            $m->setUsername($d[0]);
            $m->setFirstName($d[1]);
            $m->setLastName($d[2]);
            $m->setEmail($d[3]);
            $m->setLastPaidMembershipOn(new \DateTime($d[4].'T00:00'));
            $m->setRole($m::$ROLE_AUTO);
            self::$expected_members[] = $m;
        }
    }

    /**
     * @return \Iterator
     */
    public function getFiles($dir)
    {
        self::$root = vfsStream::setup('r', null, self::$data);

        foreach(self::$root->getChild($dir)->getChildren() as $c)
        {
            yield [$c->url()];
        }
    }

    public function getBadFiles() { return $this->getFiles('bad'); }
    public function getGoodFiles() { return $this->getFiles('good'); }

    /**
     * @dataProvider getBadFiles
     */
    public function testBadFiles($f)
    {
        $parser = new MembersReportParser();
        $this->setExpectedException('Exception');
        iterator_to_array($parser->generateMembersFromCSV($f));
    }

    /**
     * @dataProvider getGoodFiles
     */
    public function testGoodFiles($f)
    {
        $parser = new MembersReportParser();
        $this->assertEquals(iterator_to_array($parser->generateMembersFromCSV($f)), self::$expected_members);
    }
}
 