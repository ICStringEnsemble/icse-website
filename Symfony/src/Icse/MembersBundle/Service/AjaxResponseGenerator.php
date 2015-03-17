<?php
namespace Icse\MembersBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Common\Tools;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializationContext;


class FormResponse extends Response
{
    private $form_success;

    public function __construct($success, $content = '')
    {
        parent::__construct($content);
        $this->form_success = $success;
    }

    public function formSuccessful()
    {
        return $this->form_success;
    }
}


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
        return new FormResponse(true, $this->serializer->serialize($return_content, 'json', SerializationContext::create()->setGroups(['Default'])));
    }

    public function returnFail($errors, $partial_success = false)
    {
        if (is_object($errors) && is_a($errors, "Symfony\\Component\\Form\\Form")) {
            $error_array = Tools::getErrorMessages($errors);
        } else if (is_array($errors)){
            $error_array = $errors;
            foreach ($error_array as $key => $value) {
                if (!is_array($value)) {
                    $error_array[$key] = [$value];
                }
            }
        } else if (is_string($errors)) {
            $error_array = [[$errors]];
        } else {
            $error_array = [];
        }
        if ($partial_success) {
            $status_code = "partial";
        } else {
            $status_code = "fail";
        }
        
        return new FormResponse(false, json_encode(['status' => $status_code, 'errors' => $error_array]));
    }

    public function addErrorToResponse(Response $response, $new_error, $partial_success = false) {
        $decoded_response = json_decode($response->getContent(), true);
        
        if (isset($decoded_response['errors']) && is_array($decoded_response['errors'])) {
            $errors = $decoded_response['errors'];
        } else {
            $errors = [];
        }
        array_push($errors, $new_error);
        return $this->returnFail($errors, $partial_success);
    }
} 
