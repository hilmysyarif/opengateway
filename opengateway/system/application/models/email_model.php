<?php
/**
* Email Model 
*
* Contains all the methods used to create, update, and delete client Emails.
*
* @version 1.0
* @author David Ryan
* @package OpenGateway

*/
class Email_model extends Model
{
	function Email_model()
	{
		parent::Model();
	}
	
	function GetTriggerId($trigger_name)
	{
		$this->db->where('system_name', $trigger_name);
		$query = $this->db->get('email_triggers');
		if($query->num_rows() > 0) {
			return $query->row()->email_trigger_id;
		} else {
			return FALSE;
		}
	}
	
	function SaveEmail($client_id, $trigger_id, $params)
	{
		$insert_data['client_id'] = $client_id;
		$insert_data['trigger_id'] = $trigger_id;
		$insert_data['email_subject'] = $params['email_subject'];
		
		$insert_data['from_name'] = $params['from_name'];
		$insert_data['from_email'] = $params['from_email'];
		$insert_data['active'] = 1;
		
		if(is_array($params['email_body'])) {
			die($this->response->Error(8002));
		} else {
			$insert_data['email_body'] = $params['email_body'];
		}
		
		if(isset($params['plan'])) {
			$insert_data['plan_id'] = $params['plan'];
		} else {
			$insert_data['plan_id'] = -1;
		}
		
		if(isset($params['is_html'])) {
			$insert_data['is_html'] = $params['is_html'];
		} else {
			$insert_data['is_html'] = 0;
		}
		
		if(isset($params['bcc_client'])) {
			$insert_data['bcc_client'] = $params['bcc_client'];
		} else {
			$insert_data['is_html'] = 0;
		}
		
		$this->db->insert('client_emails', $insert_data);
		
		return $this->db->insert_id();
	}
	
	function UpdateEmail($client_id, $email_id, $params, $trigger_id = FALSE)
	{
		
		if($trigger_id) {
			$update_data['trigger_id'] = $trigger_id;
		}
		
		if(isset($params['plan'])) {
			$update_data['plan_id'] = $params['plan'];
		}
		
		if(isset($params['email_subject'])) {
			$update_data['email_subject'] = $params['email_subject'];
		}
	
		if(isset($params['email_body'])) {
			if(is_array($params['email_body'])) {
				die($this->response->Error(8002));
			} else {
				$update_data['email_body'] = $params['email_body'];
			}
		}
		
		if(isset($params['from_name'])) {
			$update_data['from_name'] = $params['from_name'];
		}
		
		if(isset($params['from_email'])) {
			$update_data['from_email'] = $params['from_email'];
		}
		
		if(isset($params['is_html'])) {
			$update_data['is_html'] = $params['is_html'];
		}
		
		if(isset($params['bcc_client'])) {
			$update_data['bcc_client'] = $params['bcc_client'];
		}
		
		$this->db->where('client_email_id', $email_id);
		$this->db->where('client_id', $client_id);
		
		$this->db->update('client_emails', $update_data);

		return TRUE;
	}
	
	function DeleteEmail($client_id, $email_id)
	{
		$update_data['active'] = 0;
		
		$this->db->where('client_id', $client_id);
		$this->db->where('client_email_id', $email_id);
		
		$this->db->update('client_emails', $update_data);
	}
	
}