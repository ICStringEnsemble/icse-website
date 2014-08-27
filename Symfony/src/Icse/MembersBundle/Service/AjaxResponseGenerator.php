<?php
namespace Icse\MembersBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Common\Tools;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;

class AjaxResponseGenerator
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }
 
    public function returnSuccess($extra_data = null)
    {
        $return_content = ['status' => 'success'];
        if (!is_null($extra_data)) $return_content = array_merge($return_content, $extra_data);
        return new Response($this->serializer->serialize($return_content, 'json', SerializationContext::create()->setGroups(['Default'])));
    }

    public function returnFail($errors, $partial_success = false)
    {
        if (is_object($errors) && is_a($errors, "Symfony\\Component\\Form\\Form")) {
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
