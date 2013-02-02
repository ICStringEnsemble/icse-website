<?php
namespace Icse\MembersBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Common\Tools;

class AjaxResponseGenerator
{
    public function __construct()
    {
    }
 
    public function returnSuccess()
    {
        return new Response(json_encode(array('status' => "success")));
    }

    public function returnFail($errors, $partial_success = false)
    {
        if (is_object($errors) && is_a($errors, "Symfony\Component\Form\Form")) {
            $error_array = Tools::getErrorMessages($errors);
        } else if (is_array($errors)){
            $error_array = $errors;
            foreach ($error_array as $key => $value) {
                if (!is_array($value)) {
                    $error_array[$key] = array($value);
                }
            }
        } else if (is_string($errors)) {
            $error_array = array(array($errors));
        } else {
            $error_array = array();
        }
        if ($partial_success) {
            $status_code = "partial";
        } else {
            $status_code = "fail";
        }
        
        return new Response(json_encode(array('status' => $status_code, 'errors' => $error_array)));
    }

    public function isSuccessResponse($response) {
        $decoded_response = json_decode($response->getContent(), true);
        return $decoded_response['status'] == "success";
    }

    public function addErrorToResponse($response, $new_error, $partial_success = false) {
        $decoded_response = json_decode($response->getContent(), true);
        
        if (isset($decoded_response['errors']) && is_array($decoded_response['errors'])) {
            $errors = $decoded_response['errors'];
        } else {
            $errors = array();
        }
        array_push($errors, $new_error);
        return $this->returnFail($errors, $partial_success);
    }
} 
