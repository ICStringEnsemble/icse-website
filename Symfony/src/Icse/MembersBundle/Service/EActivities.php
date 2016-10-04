<?php
namespace Icse\MembersBundle\Service;

use Common\Tools;
use Icse\MembersBundle\Entity\CommitteeRole;
use Icse\MembersBundle\Entity\Member;
use Icse\MembersBundle\Entity\MembershipProduct;


class NoMembershipProductException extends \Exception
{}


class EActivities
{
    private $base_url;
    private $csp_path;
    private $api_key;
    private $membership_product;

    public function __construct($club_id, $api_key)
    {
        $this->base_url = "https://eactivities.union.ic.ac.uk/API/";
        $this->csp_path = "/CSP/" . $club_id;
        $this->api_key = $api_key;
        $this->membership_product = [];
    }

    private function get($path)
    {
        $url = $this->base_url . $path;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-API-Key:' . $this->api_key]);
        $result=curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true);
    }

    private function get_report($type, $year=null)
    {
        $path = $this->csp_path . "/Reports/" . $type;
        if ($year !== null) $path .= "?year=".$year;
        return $this->get($path);
    }

    public function get_membership_product($year=null)
    {
        if (isset($this->membership_product[$year]))
        {
            return $this->membership_product[$year];
        }
        else
        {
            $raw_report = $this->get_report("Products", $year);
            $query = "Membership";
            foreach ($raw_report as $product_info)
            {
                $type = Tools::arrayGet($product_info, "Type");
                if (substr($type, 0, strlen($query)) === $query)
                {
                    $all_skus = Tools::arrayGet($product_info, 'ProductLines');
                    if (count($all_skus) !== 1) throw new \Exception("ProductLines has ". count($all_skus) . " elements");
                    $sku_info = $all_skus[0];
                    
                    $product = new MembershipProduct();
                    $product->setId(Tools::arrayGet($product_info, 'ID'));
                    $product->setStartsAt(new \DateTime(Tools::arrayGet($product_info, 'SellingDateStart')));
                    $product->setEndsAt(new \DateTime(Tools::arrayGet($product_info, 'SellingDateEnd')));
                    $product->setUrl(Tools::arrayGet($product_info, 'URL'));
                    $product->setPrice(Tools::arrayGet($sku_info, 'Price'));
                    $product->setLastSyncedAt(new \DateTime());

                    $this->membership_product[$year] = $product;
                    return $product;
                }
            }
            throw new NoMembershipProductException('No membership product exists');
        }
    }

    public function get_sales_report($year=null)
    {
        $raw_report = $this->get_report("OnlineSales", $year);

        $report = [];
        foreach ($raw_report as $sale)
        {
            $oid = Tools::arrayGet($sale, "OrderNumber");
            $pid = Tools::arrayGet($sale, "ProductID");
            if (isset($report[$oid])) $report[$oid][$pid] = $sale;
            else                      $report[$oid] = [$pid => $sale];
        }

        return $report;
    }

    public function iter_members($year = null)
    {
        $product_id = $this->get_membership_product($year)->getId();
        $sales_report = $this->get_sales_report($year);
        $members_list = $this->get_report("Members", $year);
        try
        {
            foreach ($members_list as $m)
            {
                $member = new Member();
                $member->setFirstName(Tools::arrayGet($m, 'FirstName')); 
                $member->setLastName(Tools::arrayGet($m, 'Surname'));
                $member->setUsername(Tools::arrayGet($m, 'Login'));
                $member->setEmail(Tools::arrayGet($m, 'Email'));
                $member->setPasswordOperation(Member::PASSWORD_IMPERIAL);

                $order_id    = Tools::arrayGet($m, 'OrderNo');
                $order_items = Tools::arrayGet($sales_report, $order_id);
                $order       = Tools::arrayGet($order_items, $product_id);
                $sale_time   = Tools::arrayGet($order, 'SaleDateTime');
                $member->setLastPaidMembershipOn(new \DateTime($sale_time));

                yield $member;
            }            
        }
        catch (\OutOfBoundsException $e)
        {
            throw new \Exception("Error: " . $e->getMessage());
        }
    }

    public function get_committee_members($year = null)
    {
        $role_list = $this->get_report("Committee", $year);
        $results = [];

        if (!isset($role_list[0])) return $results;

        foreach ($role_list as $r)
        {
            $role = new CommitteeRole();
            $role->setPositionName(Tools::arrayGet($r, 'PostName'));

            $start_time = new \DateTime(Tools::arrayGet($r, 'StartDate'));
            $role->setStartYear(intval($start_time->format('Y')));

            $login = Tools::arrayGet($r, 'Login');
            $results[$login][] = $role;
        }

        return $results;
    }
} 
